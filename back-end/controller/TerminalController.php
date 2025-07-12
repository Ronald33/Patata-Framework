<?php
class TerminalController
{
    private $validator;
    private $dao;
    private $view;

    public function __construct()
    {
        $this->validator = Repository::getValidator();
        $this->dao = new TerminalDAO();
        $this->view = Repository::getResponse();
    }

    public function get($id = null)
    {
        if($id)
        {
            $result = $this->dao->selectById($id);
            if($result) { $this->view->j200($result); }
            else { $this->view->j404(); }
        }
        else { $this->view->j200($this->dao->selectAll()); }
    }

    public function post()
    {
        $payload = Helper::getPayload();
        TerminalHelper::fillValidator($this->validator, $payload);
        if($this->validator->hasErrors()) { $this->view->j400($this->validator->getInputsWithErrors()); }
        $terminal = TerminalHelper::castToTerminal($payload);
        if(!$this->dao->insert($terminal)) { $this->view->j500(); }
        $this->view->j201($terminal);
    }

    public function put($id = null)
    {
        if($id == null) { $this->view->j501(); }
        if(!$this->dao->selectById($id)) { $this->view->j404(); }

        $payload = Helper::getPayload();
        TerminalHelper::fillValidator($this->validator, $payload, $id);
        if($this->validator->hasErrors()) { $this->view->j400($this->validator->getInputsWithErrors()); }
        $terminal = TerminalHelper::castToTerminal($payload, $id);
        $terminal->setId($id);
        if(!$this->dao->update($terminal)) { $this->view->j500(); }
        $this->view->j200($terminal);
    }

    public function patch($id = null)
    {
        if($id == NULL) { $this->view->j501(); }
        if(!$this->dao->selectById($id)) { $this->view->j404(); }

        $payload = Helper::getPayload();
        if($payload)
        {
            if(isset($payload->habilitado))
            {
                if(!in_array($payload->habilitado, [true, false], true)) { $this->view->j400(); }
                if(!$this->dao->setHabilitado($id, $payload->habilitado)) { $this->view->j500(); }
                $this->view->j200(['habilitado' => $payload->habilitado]);
            }
        }

        $this->view->j501();
    }

    public function delete($id = null)
    {
        if($id == null) { $this->view->j501(); }
        if($id == 1) { $this->view->j423(); }
        if(!$this->dao->selectById($id)) { $this->view->j404(); }
        if(!$this->dao->delete($id)) { $this->view->j500(); }
        $this->view->j204();
    }
}