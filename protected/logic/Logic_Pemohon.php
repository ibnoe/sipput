<?php
/**
*
* digunakan untuk memproses setup aplikasi
*
*/
prado::using ('Application.logic.Logic_Global');
class Logic_Pemohon extends Logic_Global {   
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
    /**
     * object report     
     */
    public $report;
	public function __construct ($db) {
		parent::__construct ($db);	            
        $this->report = $this->getLogic('Report');
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
     * digunakan untuk mendapatkan daftar pemohon
     */
    public function getListPemohon ($iduptd=null,$active=1) {        
        $str_iduptd=$iduptd==null?'':"AND iduptd=$iduptd";
        $dataitem=$this->getList("pemohon WHERE active=$active $str_iduptd",array('RecNoPem','NmPem'),'NmPem',null,1);
        $dataitem['none']='Pilih Pemohon';    
        return $dataitem;
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
    /**
     * mencetak form pemeriksaan fisik kapal
     */
    public function printFormPemeriksaanFisikKapal () {
        switch ($this->dataReport['outputmode']) {
            case 'pdf' :
                $this->report->setMode('pdf'); 
                $this->report->rpt->AddPage();
                                
                $row=6;
                $this->report->rpt->setXY(3,$row);
                $this->report->rpt->Cell (0,5,'DAFTAR ISIAN PEMERIKSAAN FISIK KAPAL PERIKANAN',1,0,'C');
                
            break;
        }
//        $this->report->printOut($this->dataReport['recnosiup']);
        $this->report->printOut(1);
        $this->report->setLink($this->dataReport['linkoutput'],'Form Pemeriksaan Fisik Kapal');
    }
}
?>