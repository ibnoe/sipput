<?php
/**
*
* digunakan untuk memproses setup aplikasi
*
*/
prado::using ('Application.logic.Logic_Global');
class Logic_DMaster extends Logic_Global {       
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
}
?>