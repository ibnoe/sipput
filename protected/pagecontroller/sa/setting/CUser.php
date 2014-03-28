<?php
prado::using ('Application.MainPageSA');
class CUser extends MainPageSA {
	public function onLoad($param) {		
		parent::onLoad($param);		        
        $this->showSetting=true;
        $this->showUser=true;
		if (!$this->IsPostBack&&!$this->IsCallBack) {
            if (!isset($_SESSION['currentPageUser'])||$_SESSION['currentPageUser']['page_name']!='sa.setting.User') {
                $_SESSION['currentPageUser']=array('page_name'=>'sa.setting.User','page_num'=>0,'roles'=>'none','search'=>false);												
			}     
            $_SESSION['currentPageUser']['search']=false;
            $listroles=$this->Pengguna->getListUserRoles();
            $listroles['none']='All';
            $this->cmbRoles->DataSource=$listroles;
            $this->cmbRoles->Text=$_SESSION['currentPageUser']['roles'];
            $this->cmbRoles->DataBind();
			$this->populateData ();			
		}
	}    
    public function renderCallback ($sender,$param) {
		$this->RepeaterS->render($param->NewWriter);	
	}	
	public function Page_Changed ($sender,$param) {
		$_SESSION['currentPageUser']['page_num']=$param->NewPageIndex;
		$this->populateData();
	} 
    public function changeRoles ($sender,$param) {
        $_SESSION['currentPageUser']['page_num']=0;
        $_SESSION['currentPageUser']['search']=false;
		$_SESSION['currentPageUser']['roles']=$this->cmbRoles->Text;
		$this->populateData();
	}
    public function filterRecord ($sender,$param) {
		$_SESSION['currentPageUser']['search']=true;
        $_SESSION['currentPageUser']['page_num']=0;        
        $this->populateData($_SESSION['currentPageUser']['search']);
	}
    private function populateData ($search=false) {                
        if ($search) {
            $str = "SELECT userid,username,page,email,active FROM user u";        
            $txtsearch=$this->txtKriteria->Text;
            switch ($this->cmbKriteria->Text) {
                case 'username' :
                    $cluasa=" WHERE username LIKE '%$txtsearch%'";
                    $jumlah_baris=$this->DB->getCountRowsOfTable ("user $cluasa",'userid');
                    $str = "$str $cluasa";
                break;
                case 'email' :
                    $cluasa=" WHERE email LIKE '%$txtsearch%'";
                    $jumlah_baris=$this->DB->getCountRowsOfTable ("user $cluasa",'userid');
                    $str = "$str $cluasa";
                break;
            }
        }else {
            $roles=$_SESSION['currentPageUser']['roles'];
            $str_roles=$roles=='none'?'':" WHERE page='$roles'";
            $str = "SELECT userid,username,page,email,active FROM user u $str_roles";        
            $jumlah_baris=$this->DB->getCountRowsOfTable ("user $str_roles",'userid');		
        }		
        $this->RepeaterS->CurrentPageIndex=$_SESSION['currentPageUser']['page_num'];
		$this->RepeaterS->VirtualItemCount=$jumlah_baris;
		$currentPage=$this->RepeaterS->CurrentPageIndex;
		$offset=$currentPage*$this->RepeaterS->PageSize;		
		$itemcount=$this->RepeaterS->VirtualItemCount;
		$limit=$this->RepeaterS->PageSize;
		if (($offset+$limit)>$itemcount) {
			$limit=$itemcount-$offset;
		}
		if ($limit < 0) {$offset=0;$limit=10;$_SESSION['currentPageUser']['page_num']=0;}
        $str = "$str ORDER BY username ASC, page ASC LIMIT $offset,$limit";        
		$this->DB->setFieldTable(array('userid','username','page','nama_uptd','email','active'));
		$r=$this->DB->getRecord($str);                
        $result=array();
        while (list($k,$v)=each($r)) {
            $v['nama_uptd']='N.A';
            $result[$k]=$v;
        }
		$this->RepeaterS->DataSource=$result;
		$this->RepeaterS->dataBind();      		
    }
    public function dataBound ($sender,$param) {
		$item=$param->Item;
		if ($item->ItemType === 'Item' || $item->ItemType === 'AlternatingItem') {	
            if ($item->DataItem['userid']==1){
                $item->btnDelete->Visible=false;                
            }
        }
    }
    public function addProcess ($sender,$param) {
		$this->idProcess='add';                
        $this->cmbAddRoles->DataSource=$this->Pengguna->getListUserRoles();
        $this->cmbAddRoles->Text=$_SESSION['currentPageUser']['roles'];
        $this->cmbAddRoles->DataBind();        
    }
    public function checkUsername ($sender,$param) {
		$this->idProcess=$sender->getId()=='CustomAddUsernameValidator'?'add':'edit';
        $username=$param->Value;		
        if ($username != '') {
            try {   
                if ($this->hiddenusername->Value!=$username) {                    
                    if ($this->DB->checkRecordIsExist('username','user',$username)) {                                
                        throw new Exception ("<span class='error'>Username ($username) sudah tidak tersedia silahkan ganti dengan yang lain.</span>");		
                    }                               
                }                
            }catch (Exception $e) {
                $param->IsValid=false;
                $sender->ErrorMessage=$e->getMessage();
            }	
        }	
    }
    public function checkEmail ($sender,$param) {
		$this->idProcess=$sender->getId()=='CustomAddEmailValidator'?'add':'edit';
        $email=$param->Value;		
        if ($email != '') {
            try {   
                if ($this->hiddenemail->Value!=$email) {                    
                    if ($this->DB->checkRecordIsExist('email','user',$email)) {                                
                        throw new Exception ("<span class='error'>Email ($email) sudah tidak tersedia silahkan ganti dengan yang lain.</span>");		
                    }                               
                }                
            }catch (Exception $e) {
                $param->IsValid=false;
                $sender->ErrorMessage=$e->getMessage();
            }	
        }	
    }    
    public function saveData($sender,$param) {		
        if ($this->Page->IsValid) {		
            $username=$this->txtAddUsername->Text;            
            $alamatemail=$this->txtAddAlamatEmail->Text;            
            $page=$this->cmbAddRoles->Text;
            $data=$this->Pengguna->createHashPassword($this->txtAddPassword->Text);
            $salt=$data['salt'];
            $password=$data['password'];
            $idunitkerja = $page=='ad'?$this->cmbAddUnitKerja->Text:0;
            $str = "INSERT INTO user (userid,username,userpassword,salt,page,idunitkerja,email,active) VALUES (NULL,'$username','$password','$salt','$page',$idunitkerja,'$alamatemail',1)";
            $this->DB->insertRecord($str);
            $this->redirect('sa.setting.User');
        }
	}
    public function editRecord ($sender,$param) {		
		$this->idProcess='edit';
		$id=$this->getDataKeyField($sender,$this->RepeaterS);        
		$this->hiddenuserid->Value=$id;
        $str = "SELECT username,email,page,active FROM user WHERE userid='$id'";
        $this->DB->setFieldTable(array('username','email','page','active'));
        $r=$this->DB->getRecord($str);    
		$result = $r[1];        				
        $this->hiddenusername->Value=$result['username'];
        $this->hiddenemail->Value=$result['email'];
		$this->txtEditUsername->Text=$result['username'];		
        $this->txtEditUsername->Enabled=$result['page']=='ptt'||$result['page']=='pns'?false:true;
		$this->txtEditAlamatEmail->Text=$result['email'];		        
        $this->cmbEditRoles->DataSource=$this->Pengguna->getListUserRoles();   
		$this->cmbEditRoles->Text=$result['page'];		        
        $this->cmbEditRoles->DataBind();     
        if ($id == 1) {
            $this->txtEditUsername->Enabled=false;
            $this->cmbEditRoles->Enabled=false;
        }
        $this->cmbEditStatus->Text=$result['active'];        
	}
    public function updateData($sender,$param) {		
        if ($this->Page->IsValid) {		
            $id=$this->hiddenuserid->Value;
            $username=$this->txtEditUsername->Text;            
            $alamatemail=$this->txtEditAlamatEmail->Text;            
            $page=$this->cmbEditRoles->Text;           
            $active=$this->cmbEditStatus->Text;
            if ($this->txtEditPassword->Text == '') {
                $str = "UPDATE user SET username='$username',page='$page',email='$alamatemail',active=$active WHERE userid=$id";
            }else {
                $data=$this->Pengguna->createHashPassword($this->txtEditPassword->Text);
                $salt=$data['salt'];
                $password=$data['password'];
                $str = "UPDATE user SET username='$username',userpassword='$password',salt='$salt',page='$page',email='$alamatemail',active=$active WHERE userid=$id";
            }
            $this->DB->updateRecord($str);           
            $this->redirect('sa.setting.User');
        }
	}
    public function deleteRecord($sender,$param) {
        $id=$this->getDataKeyField($sender,$this->RepeaterS);
        $this->DB->deleteRecord("user WHERE userid=$id");
        $this->redirect('sa.setting.User');
    }
}
		