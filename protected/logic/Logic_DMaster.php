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
     * digunakan untuk mendapatkan daftar kode jenis alat
     */
    public function getSkalaPelabuhan ($id=null) {        
        $skalapelabuhan=array('none'=>' ','lokal'=>'LOKAL/DALAM DAERAH','nasional'=>'NASIONAL/DOMESTIK','internasional'=>'INTERNASIONAL');
        if ($id===NULL) {
            return $skalapelabuhan;
        }else{
            return $skalapelabuhan[$id];
        }
    }
    /**
     * digunakan untuk mendapatkan daftar kode jenis alat
     */
    public function getJenisPelabuhan ($id=null) {        
        $jenispelabuhan=array('none'=>' ','pangkalan'=>'PANGKALAN','singgah_muat'=>'SINGGAH/MUAT','tujuan'=>'TUJUAN');
        if ($id===NULL) {
            return $jenispelabuhan;
        }else{
            return $jenispelabuhan[$id];
        }
    }
    /**
     * digunakan untuk mendapatkan daftar kode jenis alat
     */
    public function getKodeJenisAlat ($id=null) {        
        $kodejenisalat=array('none'=>' ','budidaya'=>'BUDI DAYA','tangkap'=>'ALAT TANGKAP','olahan'=>'OLAHAN','jeniskapal'=>'JENIS KAPAL','tipekapal'=>'TIPE KAPAL');
        if ($id===NULL) {
            return $kodejenisalat;
        }else{
            return $kodejenisalat[$id];
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
                $dataitem['none']='Pilih UPTD';    
                $this->Application->Cache->set('listuptd',$dataitem);
            }
        }else {                        
            $dataitem=$this->getList('uptd WHERE enabled=1',array('iduptd','nama_uptd'),'nama_uptd',null,1);
            $dataitem['none']='Pilih UPTD';            
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
                $dataitem['none']='Pilih Jenis Izin Usaha';    
                $this->Application->Cache->set('listizinusaha',$dataitem);
            }
        }else {                        
            $dataitem=$this->getList('jenisizinusaha WHERE enabled=1',array('RecNoIzin','InsIzin'),'nama_uptd',null,1);
            $dataitem['none']='Pilih Jenis Izin Usaha';    
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
}
?>