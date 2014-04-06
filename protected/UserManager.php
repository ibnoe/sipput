<?php
class UserManager extends TAuthManager {
	/**
	* Obj DB
	*/
	private $db;		
	/**
	* Username
	*/
	private $username;				
	/**
	* page
	*/
	private $page;
	/**
	* data user
	*/
	private $dataUser=array('data_user'=>array(),'hak_akses'=>array());
	
	public function __construct () {
		$this->db = $this->Application->getModule('db')->getLink();						
	}
		
	/**
	* digunakan untuk mengeset username serta mensplit username dan page	
	*/
	public function setUser ($username) {
		$username = explode('/',$username);
		$this->username=$username[0];
		$this->page=$username[1];				
	}
	/**
	* get roles username	
	*/
	public function getDataUser () {				
		$username=$this->username;		
        $str = "SELECT userid,username,userpassword,salt,nama,mobile_phone,email,page,u.iduptd,nama_uptd,active,logintime FROM user u LEFT JOIN uptd ON (uptd.iduptd=u.iduptd) WHERE username='$username' AND active=1";
        $this->db->setFieldTable (array('userid','username','userpassword','salt','nama','mobile_phone','email','page','iduptd','nama_uptd','active','','logintime'));							
        $r = $this->db->getRecord($str);				
        $dataUser=$r[1];	
        $dataUser['logintime']=date('Y-m-d H:m:s');
        switch ($dataUser['page']) {
            case 'pns' :                            
               
            break;
            case 'ptt' :
               
            break;           
        }        
        $this->dataUser['data_user']=$dataUser;
		return $dataUser;
	}
	/**
	* digunakan untuk mendapatkan data user	
	*/
	public function getUser () {				
        $str = "SELECT userpassword,page,salt FROM user WHERE username='{$this->username}' AND active=1";
        $this->db->setFieldTable (array('userpassword','salt','page'));							
        $r = $this->db->getRecord($str);				        
        $result=array();
        if (isset($r[1]) ) {
            $result=$r[1];            
        }        
        return $result;
	}	
}

?>