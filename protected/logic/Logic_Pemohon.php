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
        $id=$this->RecNoPem; //bisa siup atau id pemohon
        switch($mode) {
			case 0 :
                $str = "SELECT RecNoPem,NmPem,KtpPem,AlmtPem,TelpPem,NpwpPem,Foto,Status,p.iduptd,uptd.nama_uptd,active,DateAdded FROM pemohon p LEFT JOIN uptd ON (uptd.iduptd=p.iduptd) WHERE RecNoPem=$id";
                $this->db->setFieldTable(array('RecNoPem','NmPem','KtpPem','AlmtPem','TelpPem','NpwpPem','Foto','Status','iduptd','nama_uptd','active','DateAdded'));
                $r=$this->db->getRecord($str);
                $result=isset($r[1])?$r[1]:array();
            break;
            case 1 : //dapatkan data pemohon berdasarkan id siup
                $str = "SELECT sdp.RecNoPem,sdp.NmPem,sdp.KtpPem,sdp.AlmtPem,sdp.TelpPem,sdp.NpwpPem,sdp.Foto,sdp.Status,sdp.iduptd,sdp.nama_uptd,p.active,p.DateAdded AS date_added FROM siup_data_pemohon sdp LEFT JOIN pemohon p ON (p.RecNoPem=sdp.RecNoPem) WHERE sdp.RecNoSiup=$id";
                $this->db->setFieldTable(array('RecNoPem','NmPem','KtpPem','AlmtPem','TelpPem','NpwpPem','Foto','Status','iduptd','nama_uptd','active','date_added'));
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
        $recnobup=$this->dataReport['recnobup'];
        $nama_dinas=strtoupper($this->report->setup->getSettingValue('config_nama_dinas'));
        $nama_kabupaten=  strtoupper($this->report->setup->getSettingValue('config_nama_kabupaten'));
        
        $str = "SELECT NmKpl,RecNakKpl,PjgKpl,LbrKpl,TgiKpl,GrossKpl,MrkMsnIdk,DkMsnIdk,RpmMsnIdk,NoSrMsnIdk,MrkMsnBtu,DkMsnBtu,NoSrMsnBtu,MrkMsnDgn,NoSrMsnDgn,TptBuat,ThnBuat,NoPasKpl,NoSrUkrKpl,NoStptKli,TndSlKpl,status_kepemilikan,NmKplAwl,pergantian_kapal,AslBdr FROM relasi_sipi rs,bup,kapal k WHERE bup.RecNoBup=rs.RecNoBup AND rs.RecNoKpl=k.RecNoKpl AND rs.RecNoBup=$recnobup";
        $this->db->setFieldTable(array('NmKpl','RecNakKpl','PjgKpl','LbrKpl','TgiKpl','GrossKpl','MrkMsnIdk','DkMsnIdk','RpmMsnIdk','NoSrMsnIdk','MrkMsnBtu','DkMsnBtu','NoSrMsnBtu','MrkMsnDgn','NoSrMsnDgn','TptBuat','ThnBuat','NoPasKpl','NoSrUkrKpl','NoStptKli','TndSlKpl','status_kepemilikan','NmKplAwl','pergantian_kapal','AslBdr'));
        $r=$this->db->getRecord($str);       
        $data=$r[1];
        
        switch ($this->dataReport['outputmode']) {
            case 'pdf' :
                $this->report->setMode('pdf'); 
                $this->report->rpt->AddPage();
                                
                $row=6;
                $this->report->rpt->setXY(3,$row);
                $this->report->rpt->SetFont ('helvetica','B',12);
                $this->report->rpt->Cell (203,5,'DAFTAR ISIAN PEMERIKSAAN FISIK KAPAL PERIKANAN',0,0,'C');
                $row+=10;
                $this->report->rpt->setXY(3,$row);
                $this->report->rpt->SetFont ('helvetica','B',10);
                $this->report->rpt->Cell (5,5,'I.',0,0,'C');
                $this->report->rpt->Cell (198,5,'DATA PERUSAHAAN',0,0,'L');
                $row+=5;
                $this->report->rpt->setXY(8,$row);
                $this->report->rpt->SetFont ('helvetica','',8);
                $this->report->rpt->Cell (5,5,'1.',0,0,'C');
                $this->report->rpt->Cell (63,5,'Nama Perusahaan/Perorangan',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (125,5,$this->dataReport['NmPem'],0,0,'L');
                $row+=5;
                $this->report->rpt->setXY(8,$row);                
                $this->report->rpt->Cell (5,5,'2.',0,0,'C');
                $this->report->rpt->Cell (63,5,'Alamat Kantor Pusat',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (125,5,'',0,0,'L');
                $row+=5;
                $this->report->rpt->setXY(8,$row);                
                $this->report->rpt->Cell (5,5,'2.',0,0,'C');
                $this->report->rpt->Cell (63,5,'Nama Pimpinan Pusat',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (125,5,'',0,0,'L');
                $row+=5;
                $this->report->rpt->setXY(8,$row);                
                $this->report->rpt->Cell (5,5,'3.',0,0,'C');
                $this->report->rpt->Cell (63,5,'Telepon/Fax',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (125,5,'',0,0,'L');
                $row+=5;
                $this->report->rpt->setXY(8,$row);                
                $this->report->rpt->Cell (5,5,'4.',0,0,'C');
                $this->report->rpt->Cell (63,5,'Alamat Kantor Cabang',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (125,5,'',0,0,'L');
                $row+=5;
                $this->report->rpt->setXY(8,$row);                
                $this->report->rpt->Cell (5,5,'5.',0,0,'C');
                $this->report->rpt->Cell (63,5,'Nama Pimpinan Pusat',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (125,5,'',0,0,'L');
                $row+=5;
                $this->report->rpt->setXY(8,$row);                
                $this->report->rpt->Cell (5,5,'6.',0,0,'C');
                $this->report->rpt->Cell (63,5,'Pemegang IUP',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (125,5,'',0,0,'L');
                $row+=10;
                $this->report->rpt->setXY(3,$row);
                $this->report->rpt->SetFont ('helvetica','B',10);
                $this->report->rpt->Cell (5,5,'II.',0,0,'C');
                $this->report->rpt->Cell (198,5,'DATA KAPAL',0,0,'L');
                $row+=5;
                $this->report->rpt->setXY(8,$row);                
                $this->report->rpt->SetFont ('helvetica','',8);
                $this->report->rpt->Cell (5,5,'1.',0,0,'C');
                $this->report->rpt->Cell (63,5,'Nama Kapal',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (125,5,$data['NmKpl'],0,0,'L');
                $row+=5;
                $this->report->rpt->setXY(8,$row);                
                $this->report->rpt->Cell (5,5,'2.',0,0,'C');
                $this->report->rpt->Cell (63,5,'Nama Nakhoda',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (125,5,$data['RecNakKpl'],0,0,'L');
                $row+=5;
                $this->report->rpt->setXY(8,$row);                
                $this->report->rpt->Cell (5,5,'3.',0,0,'C');
                $this->report->rpt->Cell (63,5,'Panjang Maksimal Kapal(m)',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (125,5,$data['PjgKpl'],0,0,'L');
                $row+=5;
                $this->report->rpt->setXY(8,$row);                
                $this->report->rpt->Cell (5,5,'4.',0,0,'C');
                $this->report->rpt->Cell (63,5,'Lebar Maksimal Kapal(m)',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (125,5,$data['LbrKpl'],0,0,'L');
                $row+=5;
                $this->report->rpt->setXY(8,$row);                
                $this->report->rpt->Cell (5,5,'5.',0,0,'C');
                $this->report->rpt->Cell (63,5,'Tinggi Maksimal Kapal(m)',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (125,5,$data['TgiKpl'],0,0,'L');
                $row+=5;
                $this->report->rpt->setXY(8,$row);                
                $this->report->rpt->Cell (5,5,'6.',0,0,'C');
                $this->report->rpt->Cell (63,5,'Gross Ton',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (125,5,$data['GrossKpl'],0,0,'L');
                $row+=5;
                $this->report->rpt->setXY(8,$row);                
                $this->report->rpt->Cell (5,5,'7.',0,0,'C');
                $this->report->rpt->Cell (63,5,'Bahan Kapal',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (125,5,'',0,0,'L');
                $row+=5;
                $this->report->rpt->setXY(8,$row);                
                $this->report->rpt->Cell (5,5,'8.',0,0,'C');
                $this->report->rpt->Cell (63,5,'Merek Mesin Induk',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (125,5,$data['MrkMsnIdk'],0,0,'L');
                $row+=5;
                $this->report->rpt->setXY(8,$row);                
                $this->report->rpt->Cell (5,5,'9.',0,0,'C');
                $this->report->rpt->Cell (63,5,'Daya Mesin Induk',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (125,5,$data['DkMsnIdk'],0,0,'L');
                $row+=5;
                $this->report->rpt->setXY(8,$row);                
                $this->report->rpt->Cell (5,5,'10.',0,0,'C');
                $this->report->rpt->Cell (63,5,'Putaran Mesin (RPM)',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (125,5,$data['RpmMsnIdk'],0,0,'L');
                $row+=5;
                $this->report->rpt->setXY(8,$row);                
                $this->report->rpt->Cell (5,5,'11.',0,0,'C');
                $this->report->rpt->Cell (63,5,'Nomor Seri Mesin Induk',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (125,5,$data['NoSrMsnIdk'],0,0,'L');
                $row+=5;               
                $this->report->rpt->setXY(8,$row);                
                $this->report->rpt->Cell (5,5,'12.',0,0,'C');
                $this->report->rpt->Cell (63,5,'Merek Mesin Bantu',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (125,5,$data['MrkMsnBtu'],0,0,'L');
                $row+=5;
                $this->report->rpt->setXY(8,$row);                
                $this->report->rpt->Cell (5,5,'13.',0,0,'C');
                $this->report->rpt->Cell (63,5,'Daya Mesin Bantu',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (125,5,$data['DkMsnBtu'],0,0,'L');
                $row+=5;
                $this->report->rpt->setXY(8,$row);
                
                $this->report->rpt->Cell (5,5,'14.',0,0,'C');
                $this->report->rpt->Cell (63,5,'Nomor Seri Mesin Bantu',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (125,5,$data['NoSrMsnBtu'],0,0,'L');
                $row+=5;
                $this->report->rpt->setXY(8,$row);                
                $this->report->rpt->Cell (5,5,'15.',0,0,'C');
                $this->report->rpt->Cell (63,5,'Merek Mesin Pendingin',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (125,5,$data['MrkMsnDgn'],0,0,'L');
                $row+=5;
                $this->report->rpt->setXY(8,$row);                
                $this->report->rpt->Cell (5,5,'16.',0,0,'C');
                $this->report->rpt->Cell (63,5,'Nomor Seri Mesin Pendingin',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (125,5,$data['NoSrMsnDgn'],0,0,'L');
                $row+=5;
                $this->report->rpt->setXY(8,$row);                
                $this->report->rpt->Cell (5,5,'17.',0,0,'C');
                $this->report->rpt->Cell (63,5,'Tipe/Jenis Kapal',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (125,5,$data[''],0,0,'L');
                $row+=5;
                $this->report->rpt->setXY(8,$row);                
                $this->report->rpt->Cell (5,5,'18.',0,0,'C');
                $this->report->rpt->Cell (63,5,'Tempat/Tahun Pembuatan Kapal',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (125,5,$data['TptBuat'] . '/' . $data['ThnBuat'],0,0,'L');
                $row+=5;
                $this->report->rpt->setXY(8,$row);                
                $this->report->rpt->Cell (5,5,'19.',0,0,'C');
                $this->report->rpt->Cell (63,5,'Nomor Pas Tahunan Kapal Penangkap Ikan',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (125,5,$data['NoPasKpl'],0,0,'L');
                $row+=5;
                $this->report->rpt->setXY(8,$row);                
                $this->report->rpt->Cell (5,5,'20.',0,0,'C');
                $this->report->rpt->Cell (63,5,'No. Surat Ukur Tahunan Kapal Penangkap Ikan',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (125,5,$data['NoSrUkrKpl'],0,0,'L');
                $row+=5;
                $this->report->rpt->setXY(8,$row);                
                $this->report->rpt->Cell (5,5,'21.',0,0,'C');
                $this->report->rpt->Cell (63,5,'No. Sertifikat Kelaikan dan Pengawakan',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (125,5,$data['NoStptKli'],0,0,'L');
                $row+=5;
                $this->report->rpt->setXY(8,$row);                
                $this->report->rpt->Cell (5,5,'22.',0,0,'C');
                $this->report->rpt->Cell (63,5,'Tanda Selar',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (125,5,$data['TndSlKpl'],0,0,'L');
                $row+=10;
                $this->report->rpt->setXY(3,$row);
                $this->report->rpt->SetFont ('helvetica','B',10);
                $this->report->rpt->Cell (5,5,'III.',0,0,'C');
                $this->report->rpt->Cell (198,5,'RISALAH KAPAL',0,0,'L');
                $row+=5;
                $this->report->rpt->setXY(8,$row);
                $this->report->rpt->SetFont ('helvetica','',8);
                $this->report->rpt->Cell (5,5,'1.',0,0,'C');
                $this->report->rpt->Cell (63,5,'Nama Kapal Sebelumnya(awal)',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (125,5,$data[''],0,0,'L');
                $row+=5;
                $this->report->rpt->setXY(8,$row);
                $this->report->rpt->SetFont ('helvetica','',8);
                $this->report->rpt->Cell (5,5,'2.',0,0,'C');
                $this->report->rpt->Cell (63,5,'Pergantian/Balik Nama',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (125,5,$data[''],0,0,'L');
                $row+=5;
                $this->report->rpt->setXY(8,$row);
                $this->report->rpt->SetFont ('helvetica','',8);
                $this->report->rpt->Cell (5,5,'3.',0,0,'C');
                $this->report->rpt->Cell (63,5,'Asal Bendera',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (125,5,$data[''],0,0,'L');
                $row+=5;
                $this->report->rpt->setXY(8,$row);
                $this->report->rpt->SetFont ('helvetica','',8);
                $this->report->rpt->Cell (5,5,'4.',0,0,'C');
                $this->report->rpt->Cell (63,5,'Pemilik Awal',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (125,5,$data[''],0,0,'L');
                $row+=10;
                $this->report->rpt->setXY(3,$row);
                $this->report->rpt->SetFont ('helvetica','B',10);
                $this->report->rpt->Cell (5,5,'IV.',0,0,'C');
                $this->report->rpt->Cell (198,5,'ALAT PENANGKAP IKAN',0,0,'L');
                $row+=5;
                $this->report->rpt->setXY(8,$row);
                $this->report->rpt->SetFont ('helvetica','',8);
                $this->report->rpt->Cell (5,5,'1.',0,0,'C');
                $this->report->rpt->Cell (63,5,'Jenis Alat Tangkap Yang Digunakan',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (125,5,$data[''],0,0,'L');
                $row+=5;
                $this->report->rpt->setXY(8,$row);                
                $this->report->rpt->Cell (5,5,'2.',0,0,'C');
                $this->report->rpt->Cell (63,5,'Jumlah Alat Tangkap',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (125,5,$data[''],0,0,'L');
                $row+=5;
                $this->report->rpt->setXY(8,$row);                
                $this->report->rpt->Cell (5,5,'3.',0,0,'C');
                $this->report->rpt->Cell (63,5,'Bahan Alat Tangkap',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (125,5,$data[''],0,0,'L');
                $row+=5;
                $this->report->rpt->setXY(8,$row);                
                $this->report->rpt->Cell (5,5,'3.',0,0,'C');
                $this->report->rpt->Cell (63,5,'Bahan Alat Tangkap',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (125,5,$data[''],0,0,'L');
                $row+=5;
                $this->report->rpt->setXY(13,$row);                
                $this->report->rpt->Cell (5,5,'a.',0,0,'C');
                $this->report->rpt->Cell (58,5,'Panjang',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (125,5,$data[''],0,0,'L');
                $row+=5;
                $this->report->rpt->setXY(13,$row);                
                $this->report->rpt->Cell (5,5,'b.',0,0,'C');
                $this->report->rpt->Cell (58,5,'Lebar',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (125,5,$data[''],0,0,'L');
                $row+=5;
                $this->report->rpt->setXY(13,$row);                
                $this->report->rpt->Cell (5,5,'c.',0,0,'C');
                $this->report->rpt->Cell (58,5,'Tinggi',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (125,5,$data[''],0,0,'L');
                $row+=5;
                $this->report->rpt->setXY(13,$row);                
                $this->report->rpt->Cell (5,5,'d.',0,0,'C');
                $this->report->rpt->Cell (58,5,'Size Mata',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (125,5,$data[''],0,0,'L');
                $this->report->rpt->AddPage();
                                
                $row=6;
                $this->report->rpt->setXY(3,$row);
                $this->report->rpt->SetFont ('helvetica','B',12);
                $this->report->rpt->Cell (203,5,'DAFTAR ISIAN PEMERIKSAAN FISIK KAPAL PERIKANAN',0,0,'C');
                $row+=10;
                $this->report->rpt->setXY(3,$row);
                $this->report->rpt->SetFont ('helvetica','B',10);
                $this->report->rpt->Cell (5,5,'V.',0,0,'C');
                $this->report->rpt->Cell (198,5,'ANAK BUAH KAPAL (ABK)',0,0,'L');
                $row+=5;
                $this->report->rpt->setXY(8,$row);
                $this->report->rpt->SetFont ('helvetica','',8);
                $this->report->rpt->Cell (5,5,'1.',0,0,'C');
                $this->report->rpt->Cell (63,5,'Jumlah ABK (Orang)',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (125,5,$data[''],0,0,'L');
                $row+=5;
                $this->report->rpt->setXY(8,$row);
                $this->report->rpt->SetFont ('helvetica','',8);
                $this->report->rpt->Cell (5,5,'2.',0,0,'C');
                $this->report->rpt->Cell (63,5,'Asal ABK',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (125,5,$data[''],0,0,'L');
                $row+=5;
                $this->report->rpt->setXY(8,$row);
                $this->report->rpt->SetFont ('helvetica','',8);
                $this->report->rpt->Cell (5,5,'3.',0,0,'C');
                $this->report->rpt->Cell (63,5,'Domisili ABK',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (125,5,$data[''],0,0,'L');
                $row+=15;
                $this->report->rpt->setXY(3,$row);                
                $this->report->rpt->Cell (203,5,'Bintan, 12 Desember 2014',0,0,'C');
                $row+=5;
                $this->report->rpt->setXY(3,$row);                
                $this->report->rpt->Cell (100,5,'Menyetujui',0,0,'C');
                $this->report->rpt->Cell (103,5,'Petugas Pemeriksaan Fisik',0,0,'C');
                $row+=5;
                $this->report->rpt->setXY(3,$row);                
                $this->report->rpt->Cell (100,5,'Perusahaan/Perorangan',0,0,'C');
                $row+=25;
                $this->report->rpt->setXY(3,$row);                
                $this->report->rpt->Cell (100,5,'_________________________',0,0,'C');
                $this->report->rpt->Cell (103,5,'_________________________',0,0,'C');
                $row+=10;
                $this->report->rpt->setXY(3,$row);                
                $this->report->rpt->Cell (203,5,'Mengetahui;',0,0,'C');
                $row+=5;
                $this->report->rpt->SetFont ('helvetica','B',8);
                $this->report->rpt->setXY(3,$row);                
                $this->report->rpt->Cell (203,5,"KEPALA $nama_dinas",0,0,'C');
                $row+=5;
                $this->report->rpt->setXY(3,$row);                
                $this->report->rpt->Cell (203,5,$nama_kabupaten,0,0,'C');
                $row+=25;
                $this->report->rpt->setXY(3,$row);                
                $this->report->rpt->Cell (203,5,'_________________________',0,0,'C');
                $row+=5;
                $this->report->rpt->setXY(3,$row);                
                $this->report->rpt->Cell (203,5,'NIP:',0,0,'C');
                
            break;
        }
        $this->report->printOut($recnobup);        
        $this->report->setLink($this->dataReport['linkoutput'],'Form Pemeriksaan Fisik Kapal');
    }
    /**
     * mencetak form surat pengantar KA. UPT mengenai pengajuan
     */
    public function printSuratPengantarUPT () {
        $recnobup=$this->dataReport['recnobup'];
        $nama_dinas=strtoupper($this->report->setup->getSettingValue('config_nama_dinas'));
        $nama_kabupaten=  strtoupper($this->report->setup->getSettingValue('config_nama_kabupaten'));
        switch ($this->dataReport['outputmode']) {
            case 'pdf' :
                $this->report->setMode('pdf'); 
                $this->report->rpt->AddPage();
                
                $row=6;
                $this->report->rpt->setXY(3,$row);
                $this->report->rpt->SetFont ('helvetica','',10);
                $this->report->rpt->Cell (203,5,'Kijang, 16 Mei 2016',0,0,'R');
                $row+=10;
                $this->report->rpt->setXY(3,$row);                
                $this->report->rpt->Cell (20,5,'NOMOR',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (75,5,'523.33/DKP-UP/BT/',0,0,'L');
                $this->report->rpt->Cell (103,5,'Kepada Yth;',0,0,'L');
                $row+=5;
                $this->report->rpt->setXY(3,$row);                
                $this->report->rpt->Cell (20,5,'LAMPIRAN',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (75,5,'1 (Satu) berkas',0,0,'L');                
                $this->report->rpt->MultiCell(103,10,"Bpk. Kepala $nama_dinas $nama_kabupaten",0,'L',false,0,'','');                
                $row+=5;
                $this->report->rpt->setXY(3,$row);                
                $this->report->rpt->Cell (20,5,'PERIHAL',0,0,'L');
                $this->report->rpt->Cell (5,5,':',0,0,'C');
                $this->report->rpt->Cell (75,5,'PERMOHONAN BARU SIUP/SIPI',0,0,'L');                                
                $row+=5;
                $this->report->rpt->setXY(103,$row);
                $this->report->rpt->Cell (103,5,'Di Tanjung Pinang',0,0,'L');
                $row+=20;
                $this->report->rpt->setXY(28,$row);                
                $this->report->rpt->Cell (178,5,'Dengan Hormat,',0,0,'L');
                $row+=5;
                $this->report->rpt->setXY(28,$row);                                
                $this->report->rpt->MultiCell(178,10,'Bersama ini terlampir kami teruskan permohonan baru Izin Usaha Perikanan ( IUP ) / Surat Penangkapan Ikan (SPI) atas nama Sebagai Berikut :',0,'L',false,0,'','');
                $row+=15;
                $this->report->rpt->setXY(28,$row);                                
                $this->report->rpt->Cell (10,5,'No.',1,0,'C');
                $this->report->rpt->Cell (65,5,'Nama Pemohon',1,0,'C');
                $this->report->rpt->Cell (53,5,'Jenis Usaha',1,0,'C');
                $this->report->rpt->Cell (50,5,'Keterangan',1,0,'C');
                $row+=5;
                $this->report->rpt->setXY(28,$row);                                
                $this->report->rpt->Cell (10,5,'1',1,0,'C');
                $this->report->rpt->Cell (65,5,'APNAL JONY',1,0,'L');
                $this->report->rpt->Cell (53,5,'PENANGKAP IKAN (BUBU)',1,0,'C');
                $this->report->rpt->Cell (50,5,'Permohonan baru IUP/SPI',1,0,'L');
                $row+=10;
                $this->report->rpt->setXY(28,$row);                                                
                $this->report->rpt->MultiCell(178,10,'Permohonan yang bersangkutan telah kami teliti dan sesuai dengan Perda No. 22 tahun 2002 dan Keputusan Bupati Kepulauan Riau No. 80 tahun 2003.',0,'L',false,0,'','');
                $row+=10;
                $this->report->rpt->setXY(28,$row);                                
                $this->report->rpt->MultiCell(178,5,'Demikian disampaikan untuk mendapat proses lebih lanjut, atas persetujuan Bapak kami ucapkan terima kasih.',0,'L',false,0,'','');
                $row+=15;
                $this->report->rpt->setXY(135,$row);
                $this->report->rpt->Cell (70,5,'Plh. KEPALA UPTD. PELAYANAN USAHA',0,0,'C');
                $row+=5;
                $this->report->rpt->setXY(135,$row);
                $this->report->rpt->Cell (70,5,'PERIKANAN KEC. BINTAN TIMUR',0,0,'C');
                $row+=25;
                $this->report->rpt->setXY(135,$row);
                $this->report->rpt->Cell (70,5,'MASNIAR',0,0,'C');
                $row+=5;
                $this->report->rpt->setXY(135,$row);
                $this->report->rpt->Cell (70,5,'NIP. 10101010101',0,0,'C');
                $row+=10;
                $this->report->rpt->setXY(3,$row);
                $this->report->rpt->Cell (203,5,'Tembusan: Kepada Yth;',0,0,'L');
                $row+=5;
                $this->report->rpt->setXY(3,$row);
                $this->report->rpt->Cell (203,5,'Yang Bersangkutan di Kijang.',0,0,'L');
                
            break;
        }
        $this->report->printOut($recnobup);        
        $this->report->setLink($this->dataReport['linkoutput'],'Surat Pengantar KA. UPT');
    }
}
?>