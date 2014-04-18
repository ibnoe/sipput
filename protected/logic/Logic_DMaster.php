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
    /**
     * data perusahaan pemohon
     */
    public $DataPerusahaanPemohon;
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
     * digunakan untuk mendapatkan daftar jenis izin usaha
     */
    public function getListJenisIzinUsaha () {
        if ($this->Application->Cache) {            
            $dataitem=$this->Application->Cache->get('listizinusaha');            
            if (!isset($dataitem['none'])) {                
                $dataitem=$this->getList('jenisizinusaha WHERE enabled=1',array('RecNoIzin','InsIzin'),'RecNoIzin',null,1);
                $dataitem['none']='- Seluruh Jenis Izin Usaha -';    
                $this->Application->Cache->set('listizinusaha',$dataitem);
            }
        }else {                        
            $dataitem=$this->getList('jenisizinusaha WHERE enabled=1',array('RecNoIzin','InsIzin'),'nama_uptd',null,1);
            $dataitem['none']='- Seluruh Jenis Izin Usaha -';    
        }
        return $dataitem;        
    }   
    /**
     * digunakan untuk memperoleh nama jenis izin
     */
    public function getJenisUsaha ($RecNoIzin,$fromcache=true) {
        if ($fromcache) {
            if ($this->Application->Cache) {
                $dataitem=$this->Application->Cache->get('listizinusaha');
                if (!isset($dataitem['none'])) {
                    $dataitem=$this->getListJenisIzinUsaha();
                }            
                return $dataitem[$RecNoIzin];
            }else{
                $dataitem=$this->getList("jenisizinusaha WHERE RecNoIzin=$RecNoIzin",array('InsIzin'),'InsIzin');            
                return $dataitem[1]['InsIzin'];
            }        
        }else {
            $dataitem=$this->getList("jenisizinusaha WHERE RecNoIzin=$RecNoIzin",array('InsIzin'),'InsIzin');            
            return $dataitem[1]['InsIzin'];
        }
    }
    /**
     * digunakan untuk memperoleh daftar bidang izin usaha
     */
    public function getBidangBidangIzinUsaha ($RecNoIzin) {
        $str = "SELECT idbidangizin,InsBidang,NmBidang FROM bidangizinusaha WHERE RecNoIzin='$RecNoIzin'";
        $this->db->setFieldTable(array('idbidangizin','InsBidang','NmBidang'));
        $r=$this->db->getRecord($str);
        $result=array('none'=>'- Daftar Bidang Izin Usaha -');
        while (list($k,$v)=each($r)) {
            $result[$v['idbidangizin']]=$v['InsBidang']. ' ('.$v['NmBidang'].')';
        }
        return $result;
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
    /**
     * digunakan untuk mendapatkan data perusahaan pemohon
     */
    public function getDataPerusahaan ($mode) {
        $result=array();		
        $id=$this->RecNoPem;
        switch($mode) {
			case 0 :
                $str = "SELECT IdCom,NmCom,RecStsCom FROM perusahaan WHERE RecNoPem=$id";
                $this->db->setFieldTable(array('IdCom','NmCom','RecStsCom'));
                $r=$this->db->getRecord($str);
                $result=array('none'=>' ');
                while (list($k,$v)=each($r)) {
                    $result[$v['IdCom']]=$v['NmCom'] . " ({$v['RecStsCom']})";
                }
            break;
        }
        $this->DataPerusahaanPemohon=$result;
        return $result;
    }
}
?>