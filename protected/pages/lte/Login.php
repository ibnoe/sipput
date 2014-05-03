<?php
class Login extends MainPage { 
    public function OnPreInit ($param) {	
		parent::onPreInit ($param);	
		$this->MasterClass="Application.layouts.LTELoginTemplate";				
	}
	public function onLoad($param) {		
		parent::onLoad($param);				
		if (!$this->IsPostBack&&!$this->IsCallBack) {            
		}
	}
	private function checkUsernameAndPassword($username,$userpassword) {		
		$auth = $this->Application->getModule ('auth');					
		if ($auth->login ($username,$userpassword)){			
			return true;			
		}else {
			throw new Exception ('Username atau password salah!.Silahkan ulangi kembali');						
		}
	}
    public function doLogin ($sender,$param) {
        if ($this->IsValid) {
            try {
                $username=addslashes(trim($this->txtUsername->Text));
                $userpassword=addslashes(trim($this->txtPassword->Text));
                $this->checkUsernameAndPassword($username,$userpassword);                
                $_SESSION['ta']=date('Y');
                $pengguna=$this->getLogic('Users');
                $userid=$pengguna->getDataUser('userid');
                $this->DB->updateRecord("UPDATE user SET logintime=NOW() WHERE userid=$userid");                        
                $page=$pengguna->getTipeUser ();
                $this->redirect("$page.Home"); 
            }catch (Exception $e) {		
                $message='<br /><div class="alert alert-danger">
                    <strong>Error!</strong>
                    '.$e->getMessage().'</div>';
				$this->errormessage->Text=$message;					
				$param->IsValid=false;		
			}
        }
    }
}
?>