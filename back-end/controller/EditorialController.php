<?php
require_once(MODEL . 'editorialDAO/EditorialMYSQL.php');
require_once(VIEW . 'ResponseView.php');
require_once(VALIDATOR . 'EditorialValidator.php');
require_once(HELPER . 'Helper.php');

class EditorialController
{
	private $dao;
	private $view;

	public function __construct()
	{
		$this->dao = new EditorialMYSQL();
		$this->responseView = new ResponseView();
    }

    public function get($id = null)
    {
        if($id)
        {
            $result = $this->dao->selectById($id);
            if($result == NULL) { $this->responseView->s404(); }
            else { $this->responseView->s200($result); }
        }
        else
        {
            $result = $this->dao->selectAll();
            $this->responseView->s200($result);
        }
    }

    public function post()
    {
        $obj = Helper::getInputs();
        $response = EditorialValidator::tryValidate($obj);

        if($response === true)
        {
            $editorial = Helper::cast('Editorial', $obj);
            $this->responseView->s201(array('id' => $this->dao->insert($editorial)));
        }
        else { $this->responseView->s400($response); }
    }

    public function put($id = null)
    {
        if($id == null) { $this->responseView->s501(); }
        else
        {
            $obj = Helper::getInputs();
            $response = EditorialValidator::tryValidate($obj);

            if($response === true)
            {
                $editorial = Helper::cast('Editorial', $obj);
                $editorial->setId($id);
                $this->responseView->s201($this->dao->update($editorial));
            }
            else { $this->responseView->s400($response); }
        }
    }
    public function delete($id = null)
    {
        if($id == null) { $this->responseView->s501(); }
        else { $editorial = new Editorial($id); $this->responseView->s200($this->dao->delete($editorial)); }
    }
}
