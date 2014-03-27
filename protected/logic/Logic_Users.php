<?php

prado::using ('Application.logic.Logic_Global');
class Logic_Users extends Logic_Global {	
	/**
	* object Users
	*/
	private $u;	
	/**
	* Roles
	*/
	private $roles;
	/**
	* Data User
	*/
	private $dataUser;	
	
	public function __construct ($db) {
		parent::__construct ($db);	
		$this->u = $this->User;
		if (method_exists($this->u,'getRoles')) {
			$dataUser=$this->u->getName();	
			if ($dataUser != 'Guest') {
				$this->roles=$this->u->getRoles();			
				$this->dataUser=$dataUser;		                
            }		
		}				
	}
	/**
	* digunakan untuk membuat hash password
	* @return array
	*/
	public function createHashPassword($password,$salt='',$new=true) {
		if ($new) {
			$salt = substr(md5(uniqid(rand(), true)), 0, 6);	
			$password = hash('sha256', $salt . hash('sha256', $password));
			$data =array('salt'=>$salt,'password'=>$password);			
		}else {
			$data = hash('sha256', $salt . hash('sha256', $password));	
			$data =array('salt'=>$salt,'password'=>$password);		
		}
		return $data;
	}	
	/**
	* digunakan untuk mendapatkan tipe user
	*/		
	public function getTipeUser () {
		return $this->dataUser['page'];
	}		
	/**
	* digunakan untuk mendapatkan data user
	*
	* @return datauser
	*/
	public function getDataUser ($id='all') {						
		if ($id=='all')
			return $this->dataUser;
		else
			return $this->dataUser[$id];
	}	
	/**
	* untuk mendapatkan userid dari user
	*
	*/
	public function getUserid () {			
		return $this->dataUser['userid'];		
	}		
	/**
	* untuk mendapatkan username dari user
	*
	*/
	public function getUsername () {		
		return $this->dataUser['username'];
	}
    /**
	* untuk mendapatkan role name dari user
	*
	*/
	public function getRolename ($role=null) {		        
        $roles='';
        $page=$role === null ? $this->dataUser['page']:$role;
		if ($page == 'sa')
            $roles='Super Admin';
        elseif ($page == 'ad')
            $roles='Admin';
        elseif ($page == 'u')
            $roles='User';
        elseif ($page == 'p')
            $roles='Pemohon';
        elseif ($page == 'pe')
            $roles='Pejabat';            
        return $roles;
	}	
}
?>