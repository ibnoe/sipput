<?php
/**
*
* digunakan untuk memproses setup aplikasi
*
*/
prado::using ('Application.logic.Logic_Pemohon');
class Logic_Perizinan extends Logic_Pemohon {  
    /**
     * nomor record izin usaha
     */
    private $RecNoIzin;
	public function __construct ($db) {
		parent::__construct ($db);	                
	}
    /**
     * setter nomor record izin usaha
     */
    public function setRecNoIzin ($recnoizin) {
        $this->RecNoIzin = $recnoizin;
    }
    /**
     * digunakan untuk mmebuat nomor registrasi SIUP
     * @return noregsiup array
     */
    public function createNewNoRegSIUP ($iduptd) {    
        $recnoizin=$this->RecNoIzin;
        $str = "SELECT NoUrutSiup FROM siup WHERE NoRegSiup IN (SELECT MAX(NoRegSiup) FROM siup WHERE iduptd=$iduptd AND RecNoIzin=$recnoizin)";
        $this->db->setFieldTable(array('NoUrutSiup'));
        $r=$this->db->getRecord($str);
        if (isset($r[1])) {
            $no_urut=$r[1]['NoUrutSiup']+1;
            $tahun_bulan=date('Y.m');            
            $noregsiup=array('noreg'=>"$iduptd.$tahun_bulan.$no_urut",'nourut'=>$no_urut);
        }else{
            $tahun_bulan=date('Y.m');            
            $noregsiup=array('noreg'=>"$iduptd.$tahun_bulan.1",'nourut'=>1);            
        }
        return $noregsiup;
    }    
}
?>