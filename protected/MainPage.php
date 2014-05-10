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
	*/
	public $Pengguna;  
    /**
	* Object Variable "Data Master"	
	*/
	public $DMaster;  
    /**
	* Object Variable "Pemohon"	
	*/
	public $Pemohon;  
    /**
	* Object Variable "Perizinan"	
	*/
	public $Perizinan;      
    
    /**     
     * show page dmaster
     */
    public $showDashboard=false;
    /**     
     * show page dmaster
     */
    public $showDMaster=false;        
    /**     
     * show page pemohon [dmaster]
     */
    public $showPemohon=false;    
    /**     
     * show page kapal [dmaster]
     */
    public $showKapal=false;    
    
    /**     
     * show page perizinan baru
     */
    public $showPerizinanBaru=false;
    /**     
     * show page tambah perizinan SIPI baru [perizinan baru]
     */
    public $showAddIzinNewSIPI=false;
    /**     
     * show page tambah perizinan SIKPI baru [perizinan baru]
     */
    public $showAddIzinNewSIKPI=false;
    /**     
     * show page tambah perizinan SIKPPI baru [perizinan baru]
     */
    public $showAddIzinNewSIKPPI=false;
    /**     
     * show page tambah perizinan Budi Daya baru [perizinan baru]
     */
    public $showAddIzinNewBudiDaya=false;
    /**     
     * show page tambah perizinan Pengolahan baru [perizinan baru]
     */
    public $showAddIzinNewPengolahan=false;
    
    /**     
     * show page perizinan baru
     */
    public $showDaftarPengajuan=false;
    /**     
     * show page tambah perizinan SIPI baru [perizinan baru]
     */
    public $showPengajuanSIPI=false;
    /**     
     * show page tambah perizinan SIKPI baru [perizinan baru]
     */
    public $showPengajuanSIKPI=false;
    /**     
     * show page tambah perizinan SIKPPI baru [perizinan baru]
     */
    public $showPengajuanSIKPPI=false;
    /**     
     * show page tambah perizinan Budi Daya baru [perizinan baru]
     */
    public $showPengajuanBudiDaya=false;
    /**     
     * show page tambah perizinan Pengolahan baru [perizinan baru]
     */
    public $showPengajuanPengolahan=false;
    
    /**     
     * show page daftar izin
     */
    public $showDaftarIzin=false;
    /**     
     * show page daftar perizinan SIUP [daftar izin]
     */
    public $showDaftarSIUP=false;
    /**     
     * show page daftar perizinan SIPI [daftar izin]
     */
    public $showDaftarSIPI=false;
    /**     
     * show page daftar perizinan SIKPI [daftar izin]
     */
    public $showDaftarSIKPI=false;
    /**     
     * show page daftar perizinan SIKPPI [daftar izin]
     */
    public $showDaftarSIKPPI=false;
    /**     
     * show page daftar perizinan Budi Daya [daftar izin]
     */
    public $showDaftarBudiDaya=false;
    /**     
     * show page daftar perizinan Pengolahan [daftar izin]
     */
    public $showDaftarPengolahan=false;
    
    /**     
     * show page setting
     */
    public $showSetting=false;
    
	public function OnPreInit ($param) {	
		parent::onPreInit ($param);
		//instantiasi database		
		$this->DB = $this->Application->getModule ('db')->getLink();		
        //instantiasi fungsi setup
        $this->setup = $this->getLogic('Setup');                        
        //setting templaces yang aktif
        $this->MasterClass='Application.layouts.LTETemplate';		
		$this->Theme='lte';
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
	protected function redirect ($page,$automaticpage=false,$param=array()) {
		$this->Response->Redirect($this->constructUrl($page,$automaticpage,$param));	
	}	  
    /**
     * digunakan untuk membuat url
     */
    public function constructUrl($page,$automaticpage=false,$param=array()) {
        $url=$this->Theme->getName();        
        if ($automaticpage) {
            $tipeuser=$this->Pengguna->getTipeUser();
            $url="$url.$tipeuser";
        }
        $url="$url.$page";
        return $this->Service->constructUrL($url,$param);
    }
    /**
     * digunakan untuk membuat berbagai macam object
     */
    public function createObj ($nama_object) {
        switch (strtolower($nama_object)) {
            case 'dmaster' :
                $this->DMaster = $this->getLogic('DMaster');
            break;
            case 'pemohon' :
                $this->Pemohon = $this->getLogic('Pemohon');
            break;
            case 'perizinan' :
                $this->Perizinan = $this->getLogic('Perizinan');
            break;
        }
    }
}
?>