<?php
/**
*
* digunakan untuk memproses setup aplikasi
*
*/
prado::using ('Application.logic.Logic_Global');
class Logic_DMaster extends Logic_Global {    
    /**
     * data pemohon     
     */
    public $DataPemohon;
	public function __construct ($db) {
		parent::__construct ($db);	                
	}	
    /**
     * digunakan untuk mendapatkan daftar uptd
     */
    public function getListUPTD () {
        if ($this->Application->Cache) {            
            $dataitem=$this->Application->Cache->get('listuptd');            
            if (!isset($dataitem['none'])) {
                $dataitem=$this->getList('uptd WHERE enabled=1',array('iduptd','nama_uptd'),'nama_uptd',null,1);
                $dataitem['none']='-------------- Seluruh UPTD --------------';    
                $this->Application->Cache->set('listuptd',$dataitem);
            }
        }else {                        
            $dataitem=$this->getList('uptd WHERE enabled=1',array('iduptd','nama_uptd'),'nama_uptd',null,1);
            $dataitem['none']='-------------- Seluruh UPTD --------------';            
        }
        return $dataitem;        
    }   
    /**
     * digunakan untuk memperoleh nama upt berdasarkan kode upt
     */
    public function getUPTName ($kode_upt,$fromcache=true) {
        if ($fromcache) {
            if ($this->Application->Cache) {
                $dataitem=$this->Application->Cache->get('listuptd');
                if (!isset($dataitem['none'])) {
                    $dataitem=$this->getListUPTD();
                }            
                return $dataitem[$kode_upt];
            }else{
                $dataitem=$this->getList("uptd WHERE iduptd=$kode_upt",array('nama_uptd'),'nama_uptd');            
                return $dataitem[1]['nama_uptd'];
            }        
        }else {
            $dataitem=$this->getList("uptd WHERE iduptd=$kode_upt",array('nama_uptd'),'nama_uptd');            
            return $dataitem[1]['nama_uptd'];
        }
    }
    /**
     * digunakan untuk mendapatkan daftar pemohon
     */
    public function getListPemohon ($iduptd=null,$active=1) {        
        $str_iduptd=$iduptd==null?'':"AND iduptd=$iduptd";
        $dataitem=$this->getList("pemohon WHERE active=$active $str_iduptd",array('RecNoPem','NmPem'),'NmPem',null,1);
        $dataitem['none']='-------------- Seluruh Pemohon --------------';    
        return $dataitem;
    }
    /**
     * digunakan untuk mendapatkan data pemohon
     */
    public function getDataPemohon () {
        
    }
}
?>