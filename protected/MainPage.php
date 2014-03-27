<?php

class MainPage extends TPage {   
	/**
	* id process
	*/
	public $idProcess;	
	/**
	* Object Variable "Database"
	*
	*/
	public $DB;		
	/**
	* Object Variable "Setup"
	*
	*/
	public $setup;		
	/**
	* Object Variable "Tanggal"
	*
	*/
	public $TGL;	  
    /**
	* Object Variable "User"
	*
	*/
	public $Pengguna;    
	public function OnPreInit ($param) {	
		parent::onPreInit ($param);
		//instantiasi database		
		$this->DB = $this->Application->getModule ('db')->getLink();		
        //instantiasi fungsi setup
        $this->setup = $this->getLogic('Setup');                        
        //setting templaces yang aktif
        $this->MasterClass='Application.layouts.DefaultTemplate';		
		$this->Theme='default';
	}
	public function onLoad ($param) {		
		parent::onLoad($param);				
		//instantiasi user
		$this->Pengguna = $this->getLogic('Users');
        //mengecek akses user terhadap halaman tertentu
        $datauser = $this->Pengguna->getDataUser();
        if (isset($datauser['page'])) {	
            $currentPage=$this->Page->getPagePath();
            if ($currentPage != 'Logout') {
                $page=$datauser['page'];
                $currentPage=explode('.',$currentPage);	                
                if ($currentPage[1] != $page) {					                                                 
                    $this->redirect("$page.Home");
                }
            }
		}		    
		//instantiasi fungsi tanggal
		$this->TGL = $this->getLogic ('Penanggalan');        
	}
	/**
	* mendapatkan lo object
	* @return obj	
	*/
	public function getLogic ($_class=null) {
		if ($_class === null)
			return $this->Application->getModule ('logic');
		else 
			return $this->Application->getModule ('logic')->getInstanceOfClass($_class);	
	}
	/**
	* id proses tambah, delete, update,show
	*/
	protected function setIdProcess ($sender,$param) {		
		$this->idProcess=$sender->getId();
	}
	
	/**
	* add panel
	* @return boolean
	*/
	protected function getAddProcess ($disabletoolbars=true) {
		if ($this->idProcess == 'add') {			
			if ($disabletoolbars)$this->disableToolbars();
			return true;
		}else {
			return false;
		}
	}
	
	/**
	* edit panel
	* @return boolean
	*/
	protected function getEditProcess ($disabletoolbars=true) {
		if ($this->idProcess == 'edit') {			
			if ($disabletoolbars)$this->disableToolbars();
			return true;
		}else {
			return false;
		}

	}
	
	/**
	* view panel
	* @return boolean
	*/
	protected function getViewProcess ($disabletoolbars=true) {
		if ($this->idProcess == 'view') {
			if ($disabletoolbars)$this->disableToolbars();			
			return true;
		}else {
			return false;
		}

	}
	
	/**
	* default panel
	* @return boolean
	*/
	protected function getDefaultProcess () {
		if ($this->idProcess == 'add' || $this->idProcess == 'edit'|| $this->idProcess == 'view') {
			return false;
		}else {
			return true;
		}
	}	
	/**
	* digunakan untuk mendapatkan sebuah data key dari repeater
	* @return data key
	*/
	protected function getDataKeyField($sender,$repeater) {
		$item=$sender->getNamingContainer();
		return $repeater->DataKeys[$item->getItemIndex()];
	}    
    /**
	* Redirect
	*/
	protected function redirect ($page,$param=array()) {
		$this->Response->Redirect($this->constructUrl($page,$param));	
	}	  
    /**
     * digunakan untuk membuat url
     */
    public function constructUrl($page,$param=array()) {
        $theme=$this->Theme->getName();
        return $this->Service->constructUrL("$theme.$page",$param);
    }
}
?>