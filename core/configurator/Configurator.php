<?php
namespace core\configurator;
class Configurator
{
    private $_inDevelopment;

    public function __construct($in_production)
    {
        $this->_inDevelopment = $in_production ? 0 : 1;
    }

    public function enableAssertions()
    {
        ini_set('zend.assertions', $this->_inDevelopment);
        ini_set('assert.exception', $this->_inDevelopment);
    }

    public function showAllErrors()
    {
        ini_set('display_errors', $this->_inDevelopment);
        ini_set('display_startup_errors', $this->_inDevelopment);
        if($this->_inDevelopment) { error_reporting(E_ALL); }
    }

    private function setErrorHandler() { set_error_handler([$this, 'showError']); }
    private function setExceptionHandler() { set_exception_handler([$this, 'showException']); }

    public function enableCustomErrors() { $this->setErrorHandler(); $this->setExceptionHandler(); }

    private function getTitle($code)
    {
        $error = 'Error';
        switch($code)
        {
            case E_PARSE:
            case E_ERROR:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
                $error = 'Fatal Error';
                break;
            case E_WARNING:
            case E_USER_WARNING:
            case E_COMPILE_WARNING:
            case E_RECOVERABLE_ERROR:
                $error = 'Warning';
                break;
            case E_NOTICE:
            case E_USER_NOTICE:
                $error = 'Notice';
                break;
            case E_STRICT:
                $error = 'Strict';
                break;
            case E_DEPRECATED:
            case E_USER_DEPRECATED:
                $error = 'Deprecated';
                break;
            default :
                break;
        }

        return $error;
    }

    public function showError($errno, $errstr, $errfile, $errline)
    {
        if(!(error_reporting() & $errno)) { return false; }

        $result = [];
        $result[] = sprintf('Error: [%d] %s', $errno, $errstr);
        $trace = debug_backtrace();
        $result[] = sprintf(' at %s:%s', $errfile, $errline);

        foreach ($trace as $index => $frame) {
            if($index == 0) {
                continue; // Saltar el primer frame ya que es esta misma función
            }

            $file = array_key_exists('file', $frame) ? $frame['file'] : 'Unknown Source';
            $line = array_key_exists('line', $frame) ? $frame['line'] : null;
            $class = array_key_exists('class', $frame) ? str_replace('\\', '.', $frame['class']) : '';
            $function = array_key_exists('function', $frame) ? str_replace('\\', '.', $frame['function']) : '(main)';

            $result[] = sprintf(
                ' at %s%s%s(%s%s%s)',
                $class,
                $class && $function ? '.' : '',
                $function,
                $line === null ? $file : basename($file),
                $line === null ? '' : ':',
                $line === null ? '' : $line
            );
        }

        $result = join("\n", $result);
        // error_log($result); // Registrar el error en el log

        if($this->_inDevelopment)
        {
            echo '<h2>' . $this->getTitle($errno) . ':</h2>';
            echo '<pre>' . $result . '</pre>';
        }

        return true;
    }

    /**
     * Stolen from: https://www.php.net/manual/en/exception.gettraceasstring.php
     * jTraceEx() - provide a Java style exception trace
     * @param $exception
     * @param $seen      - array passed to recursive calls to accumulate trace lines already seen
     *                     leave as NULL when calling this function
     * @return array of strings, one entry per trace line
     */
    private function jTraceEx($e, $seen = null)
    {
        $starter = $seen ? 'Caused by: ' : '';
        $result = [];
        if(!$seen) $seen = [];
        $trace  = $e->getTrace();
        $prev   = $e->getPrevious();
        $result[] = sprintf('%s%s: %s', $starter, get_class($e), $e->getMessage());
        $file = $e->getFile();
        $line = $e->getLine();
        while (true) {
            // $current = "$file:$line";
            // if(is_array($seen) && in_array($current, $seen)) {
            //     $result[] = sprintf(' ... %d more', count($trace)+1);
            //     break;
            // }
            $result[] = sprintf(' at %s%s%s(%s%s%s)',
                                        count($trace) && array_key_exists('class', $trace[0]) ? str_replace('\\', '.', $trace[0]['class']) : '',
                                        count($trace) && array_key_exists('class', $trace[0]) && array_key_exists('function', $trace[0]) ? '.' : '',
                                        count($trace) && array_key_exists('function', $trace[0]) ? str_replace('\\', '.', $trace[0]['function']) : '(main)',
                                        $line === null ? $file : basename($file),
                                        $line === null ? '' : ':',
                                        $line === null ? '' : $line);
            if(is_array($seen))
                $seen[] = "$file:$line";
            if(!count($trace))
                break;
            $file = array_key_exists('file', $trace[0]) ? $trace[0]['file'] : 'Unknown Source';
            $line = array_key_exists('file', $trace[0]) && array_key_exists('line', $trace[0]) && $trace[0]['line'] ? $trace[0]['line'] : null;
            array_shift($trace);
        }
        $result = join("\n", $result);
        if($prev)
            $result  .= "\n" . self::jTraceEx($prev, $seen);

        return $result;
    }

    public function showException(\Throwable $throwable)
    {
        http_response_code(500);
        if($this->_inDevelopment)
        {
            echo '<h2>Exception:</h2>';
            echo '<pre>' . self::jTraceEx($throwable) . '</pre>';
        }
        else
        {
		    header('Content-Type: application/json; charset=UTF-8');
        }
    }
}
