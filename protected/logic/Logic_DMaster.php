<?php
/**
*
* digunakan untuk memproses setup aplikasi
*
*/
prado::using ('Application.logic.Logic_Global');
class Logic_DMaster extends Logic_Global { 
    /**
     * Nomor ID Pemohon
     * @var integer
     */
    protected $RecNoPem=null;
    /**
     * data pemohon     
     */
    public $DataPemohon;
	public function __construct ($db) {
		parent::__construct ($db);	                
	}	
    /**
     * setter NIP
     * @param type $nip integer
     */
    public function setRecNoPem ($id,$load=false,$mode=0) {
        $this->RecNoPem=$id;
        if ($load){
            $this->getDataPemohon($mode);
        }
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
    public function getDataPemohon ($mode) {
        $result=array();		
        $id=$this->RecNoPem;
        switch($mode) {
			case 0 :
                $str = "SELECT RecNoPem,NmPem,KtpPem,AlmtPem,TelpPem,NpwpPem,Foto,Status,p.iduptd,uptd.nama_uptd,active,DateAdded FROM pemohon p LEFT JOIN uptd ON (uptd.iduptd=p.iduptd) WHERE RecNoPem=$id";
                $this->db->setFieldTable(array('RecNoPem','NmPem','KtpPem','AlmtPem','TelpPem','NpwpPem','Foto','Status','iduptd','nama_uptd','active','DateAdded'));
                $r=$this->db->getRecord($str);
                $result=isset($r[1])?$r[1]:array();
            break;
        }
        $this->DataPemohon=$result;
        return $result;
    }
}
?>