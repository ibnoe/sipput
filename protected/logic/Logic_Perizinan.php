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
    /**
     * digunakan untuk mencetak sertifikat SIPI
     */
    public function printSIPI () {
        $headerLogo=BASEPATH.$this->report->setup->getSettingValue('config_logo');
        $nama_dinas=strtoupper($this->report->setup->getSettingValue('config_nama_dinas'));
        $nama_kabupaten=  strtoupper($this->report->setup->getSettingValue('config_nama_kabupaten'));
        switch ($this->dataReport['outputmode']) {
            case 'pdf' :                
                $this->report->setMode('pdf'); 
                $this->report->rpt->AddPage();             
                $row=6;
                $this->report->rpt->SetFont ('helvetica','B',10);
                $this->report->rpt->setXY(5,$row);
                $this->report->rpt->Cell (20,5,'SIUP',0,0,'L');
                $this->report->rpt->Cell (180,5,'SIUP',0,0,'R');
                
                $row+=15;
                $this->report->rpt->Image($headerLogo,90,$row,34,27);              
                
                $row+=28;
                $this->report->rpt->SetFont ('helvetica','B',12);
                $this->report->rpt->setXY(3,$row);
                $this->report->rpt->Cell (0,5,$nama_dinas,0,0,'C');
                $row+=5;
                $this->report->rpt->setXY(3,$row);
                $this->report->rpt->Cell (0,5,$nama_kabupaten,0,0,'C');
                $row+=10;
                $this->report->rpt->setXY(3,$row);
                $this->report->rpt->Cell (0,5,'SURAT IZIN USAHA PERIKANAN',0,0,'C');
                $row+=5;
                $this->report->rpt->setXY(3,$row);
                $this->report->rpt->Cell (0,5,'DI BIDANG PENANGKAPAN IKAN',0,0,'C');
                
                $row+=5;
                $this->report->rpt->setXY(3,$row);
                $this->report->rpt->SetFont ('helvetica','',12);
                $this->report->rpt->Cell (0,5,'NOMOR : 51/523.3.32.5/SIUP/DKP-BTN/2013',0,0,'C');
                
                $row+=10;
                $this->report->rpt->setXY(3,$row);
                $this->report->rpt->SetFont ('helvetica','B',10);
                $this->report->rpt->Cell (93,5,'PERUSAHAAN',1,0,'C');
                $this->report->rpt->Cell (110,5,'REFERENSI',1,0,'C');
                $row+=5;
                $this->report->rpt->SetFont ('helvetica','',8);
                $this->report->rpt->setXY(3,$row);                
                $this->report->rpt->Cell (35,5,'NAMA PERUSAHAAN /','L',0,'L');
                $this->report->rpt->MultiCell(3,10,':',0,'C',false,0,'','');
                $this->report->rpt->MultiCell(55,10,'MOCHAMMAD RIZKI ROMDONI','R','L',false,0,'','');
                $this->report->rpt->Cell (110,5,'SURAT PERMOHONAN SIUP','R',0,'L');                
                $row+=5;                
                $this->report->rpt->setXY(3,$row);                
                $this->report->rpt->Cell (35,5,'PERORANGAN','L',0,'L');                
                $this->report->rpt->setXY(96,$row);
                $this->report->rpt->Cell (35,5,'NOMOR SURAT','L',0,'L');         
                $this->report->rpt->Cell (3,5,':',0,0,'L');
                $this->report->rpt->Cell (72,5,'523.33/UPTD-PUP/BP-M/150','R',0,'L');
                $row+=5;                
                $this->report->rpt->setXY(3,$row);                
                $this->report->rpt->Cell (35,5,'ALAMAT','L',0,'L');
                $this->report->rpt->MultiCell(3,10,':',0,'C',false,0,'','');
                $this->report->rpt->MultiCell(55,10,'JL. BRIGJEND KATAMSON NO. 92 KM 2,5 TANJUNG PINANG',0,'L',false,0,'','');                                
                $this->report->rpt->Cell (35,5,'TANGGAL','L',0,'L');                 
                $this->report->rpt->Cell (3,5,':',0,0,'L');
                $this->report->rpt->Cell (72,5,'04 NOVEMBER 2014','R',0,'L');
                $row+=5;
                $this->report->rpt->setXY(3,$row);                
                $this->report->rpt->Cell (35,5,'','L',0,'L');
                $this->report->rpt->setXY(96,$row);
                $this->report->rpt->SetFont ('helvetica','B',10);
                $this->report->rpt->Cell (110,5,'JENIS KEGIATAN',1,0,'C');
                $row+=5;                
                $this->report->rpt->SetFont ('helvetica','',8);
                $this->report->rpt->setXY(3,$row); 
                $this->report->rpt->Cell (35,5,'NO.TELEFON','L',0,'L');
                $this->report->rpt->Cell (3,5,':',0,0,'L');
                $this->report->rpt->Cell (55,5,'(0771) 7002638',0,0,'L');                 
                $this->report->rpt->setXY(96,$row);
                $this->report->rpt->Cell (110,5,'USAHA PENANGKAPAN IKAN',1,0,'L');
                $row+=5;                
                $this->report->rpt->setXY(3,$row); 
                $this->report->rpt->Cell (35,5,'NO.FAX','L',0,'L');
                $this->report->rpt->Cell (3,5,':',0,0,'C');
                $this->report->rpt->Cell (55,5,'(0771) 7002638',0,0,'L');
                $this->report->rpt->setXY(96,$row);
                $this->report->rpt->SetFont ('helvetica','B',10);
                $this->report->rpt->Cell (110,5,'KAPAL DAN DEARAH USAHA',1,0,'C');
                $row+=5;                
                $this->report->rpt->setXY(3,$row); 
                $this->report->rpt->SetFont ('helvetica','',8);
                $this->report->rpt->Cell (35,5,'EMAIL','L',0,'L');
                $this->report->rpt->Cell (3,5,':',0,0,'L');
                $this->report->rpt->Cell (55,5,'cvyacanet@gmail.com','R',0,'L');
                $this->report->rpt->setXY(96,$row);
                $this->report->rpt->MultiCell(110,15,'JENIS KAPAL DAN UKURAN KAPAL,DAERAH USAHA, PELABUHAN   PANGKALAN  /  MUAT   DAN   DAERAH PENANGKAPAN,SEBAGAIMANA TERLAMPIR',1,'L',false,0,'','');
                $row+=5;                
                $this->report->rpt->setXY(3,$row); 
                $this->report->rpt->SetFont ('helvetica','',8);
                $this->report->rpt->Cell (35,5,'NPWP','L',0,'L');
                $this->report->rpt->Cell (3,5,':',0,0,'L');
                $this->report->rpt->Cell (55,5,'15.274.014.8-214.000','R',0,'L');                
                $row+=5;
                $this->report->rpt->SetFont ('helvetica','',8);
                $this->report->rpt->setXY(3,$row);                
                $this->report->rpt->Cell (35,5,'NO.AKTE PENDIRIAN /','L',0,'L');
                $this->report->rpt->MultiCell(3,10,':',0,'C',false,0,'','');
                $this->report->rpt->MultiCell(55,10,'0828282.9292','R','L',false,0,'','');                
                $row+=5;                
                $this->report->rpt->setXY(3,$row);                
                $this->report->rpt->Cell (35,5,'PERUBAHAN','L',0,'L');
                $this->report->rpt->setXY(96,$row);
                $this->report->rpt->SetFont ('helvetica','B',10);
                $this->report->rpt->Cell (110,5,'MASA BERLAKU IZIN',1,0,'C');                                
                $row+=5;
                $this->report->rpt->SetFont ('helvetica','',8);
                $this->report->rpt->setXY(3,$row);                
                $this->report->rpt->Cell (35,5,'NAMA PENANGGUNG','L',0,'L');
                $this->report->rpt->MultiCell(3,10,':',0,'C',false,0,'','');
                $this->report->rpt->MultiCell(55,10,'MOCHAMMAD RIZKI ROMDONI','R','L',false,0,'','');
                $this->report->rpt->setXY(96,$row);                
                $this->report->rpt->Cell (110,5,'SIUP INI BERLAKU SEJAK TANGGAL : 09 DESEMBER 2014','R',0,'L');                                                
                $row+=5;                
                $this->report->rpt->setXY(3,$row);                
                $this->report->rpt->Cell (35,5,'JAWAB','L',0,'L');
                $this->report->rpt->setXY(96,$row);                
                $this->report->rpt->Cell (110,5,'SAMPAI DENGAN TANGGAL : 09 DESEMBER 2015','R',0,'L');                                                
                $row+=5;
                $this->report->rpt->SetFont ('helvetica','',8);
                $this->report->rpt->setXY(3,$row);                
                $this->report->rpt->Cell (35,5,'NO.KTP PENANGGUNG','L',0,'L');
                $this->report->rpt->MultiCell(3,10,':',0,'C',false,0,'','');
                $this->report->rpt->MultiCell(55,10,'9292929292929229','R','L',false,0,'','');
                $this->report->rpt->setXY(96,$row);                
                $this->report->rpt->Cell (110,5,'','R',0,'L');                                                
                $row+=5;                
                $this->report->rpt->setXY(3,$row);                
                $this->report->rpt->Cell (35,5,'JAWAB','L',0,'L');
                $this->report->rpt->setXY(96,$row);                
                $this->report->rpt->Cell (110,5,'','R',0,'L');                                                
                $row+=5;
                $this->report->rpt->setXY(3,$row);                
                $this->report->rpt->SetFont ('helvetica','B',10);
                $this->report->rpt->Cell (93,5,'CATATAN',1,0,'C');
                $this->report->rpt->setXY(96,$row);               
                $this->report->rpt->SetFont ('helvetica','',8);
                $this->report->rpt->Cell (110,5,'BANDAR SERI BINTAN, 10 JULI 2014','RT',0,'L');                                                
                $row+=5;
                $this->report->rpt->setXY(3,$row);                                
                $this->report->rpt->Cell (5,5,'1.','L',0,'L');
                $this->report->rpt->Cell (88,5,'Memperhatikan UU Nomor 45 Tahun 2009 tentang Perubahan ','R',0,'L');
                $this->report->rpt->setXY(96,$row);                               
                $this->report->rpt->Cell (110,5,"An. BUPATI $nama_kabupaten",'R',0,'C');                                                
                $row+=5;
                $this->report->rpt->setXY(3,$row);                                
                $this->report->rpt->Cell (5,5,'','L',0,'L');
                $this->report->rpt->Cell (88,5,'Atas UU Nomor 31 Tahun 2004 Tentang Perikanan','R',0,'L');
                $this->report->rpt->setXY(96,$row);                                               
                $this->report->rpt->MultiCell(110,10,"KEPALA $nama_dinas $nama_kabupaten",'R','C',false,0,'','');
                $row+=5;
                $this->report->rpt->setXY(3,$row);                                
                $this->report->rpt->Cell (5,5,'2.','L',0,'L');
                $this->report->rpt->Cell (88,5,'Memperhatikan Perda Nomor 5 Tahun 2011 tentang Retribusi','R',0,'L');
                $this->report->rpt->setXY(96,$row);                
                $this->report->rpt->Cell (110,5,'','R',0,'L');                                                
                $row+=5;
                $this->report->rpt->setXY(3,$row);                                
                $this->report->rpt->Cell (5,5,'','L',0,'L');
                $this->report->rpt->Cell (88,5,'Perizinan Tertentu','R',0,'L');
                $this->report->rpt->setXY(96,$row);                
                $this->report->rpt->Cell (110,5,'','R',0,'L');                                                
                $row+=5;
                $this->report->rpt->setXY(3,$row);                                
                $this->report->rpt->Cell (5,5,'3.','L',0,'L');
                $this->report->rpt->Cell (88,5,'Tidak menggunakan bahan kimia, bahan peledak dan jenis alat','R',0,'L');
                $this->report->rpt->setXY(96,$row);                
                $this->report->rpt->Cell (110,5,'','R',0,'L');                                                
                $row+=5;
                $this->report->rpt->setXY(3,$row);                                
                $this->report->rpt->Cell (5,5,'','L',0,'L');
                $this->report->rpt->Cell (88,5,'Tangkap yang dilarang/ dapat merusak lingkungan','R',0,'L');
                $this->report->rpt->setXY(96,$row);                
                $this->report->rpt->Cell (110,5,'','R',0,'L');                                                
                $row+=5;
                $this->report->rpt->setXY(3,$row);                                
                $this->report->rpt->Cell (5,5,'4.','L',0,'L');
                $this->report->rpt->Cell (88,5,'Tidak mengambil bunga karang (Coral) dan jenis ikan yang ','R',0,'L');
                $this->report->rpt->setXY(96,$row);                
                $this->report->rpt->Cell (110,5,'','R',0,'L');                                                
                $row+=5;
                $this->report->rpt->setXY(3,$row);                                
                $this->report->rpt->Cell (5,5,'','L',0,'L');
                $this->report->rpt->Cell (88,5,'dilindungi','R',0,'L');
                $this->report->rpt->setXY(96,$row);                
                $this->report->rpt->Cell (110,5,'','R',0,'L');                                                
                $row+=5;
                $this->report->rpt->setXY(3,$row);                                
                $this->report->rpt->Cell (5,5,'5.','L',0,'L');
                $this->report->rpt->Cell (88,5,'Tidak untuk melakukan kegiatan selain kegiatan penangkapan ikan','R',0,'L');
                $this->report->rpt->setXY(96,$row);                
                $this->report->rpt->Cell (110,5,'','R',0,'L');                                                
                $row+=5;
                $this->report->rpt->setXY(3,$row);                                
                $this->report->rpt->Cell (5,5,'6.','L',0,'L');
                $this->report->rpt->Cell (88,5,'Menyampaikan laporan kegiatan penangkapan ikan setiap 3 Bulan','R',0,'L');
                $this->report->rpt->setXY(96,$row);                
                $this->report->rpt->Cell (110,5,'','R',0,'L');                                                
                $row+=5;
                $this->report->rpt->setXY(3,$row);                                
                $this->report->rpt->Cell (5,5,'','L',0,'L');
                $this->report->rpt->Cell (88,5,'sekali','R',0,'L');
                $this->report->rpt->setXY(96,$row);                
                $this->report->rpt->Cell (110,5,'','R',0,'L');                                                
                $row+=5;
                $this->report->rpt->setXY(3,$row);                
                $this->report->rpt->SetFont ('helvetica','B',10);
                $this->report->rpt->Cell (93,5,'DISTRIBUSI COPY',1,0,'C');
                $this->report->rpt->setXY(96,$row);                               
                $this->report->rpt->SetFont ('helvetica','',8);
                $this->report->rpt->Cell (12,5,'NAMA','L',0,'L');   
                $this->report->rpt->Cell (3,5,':',0,0,'C');   
                $this->report->rpt->Cell (95,5,'DR. MOCHAMMAD RIZKI ROMDONI, S.KOM., M.T','R',0,'L');                   
                $row+=5;
                $this->report->rpt->setXY(3,$row);                                
                $this->report->rpt->Cell (5,5,'1.','L',0,'L');
                $this->report->rpt->Cell (88,5,'Gubernur Kepulauan Riau di Tanjungpinang.','R',0,'L');
                $this->report->rpt->setXY(96,$row);                                               
                $this->report->rpt->Cell (12,5,'NIP','L',0,'L');   
                $this->report->rpt->Cell (3,5,':',0,0,'C');   
                $this->report->rpt->Cell (95,5,'202020202.202022.','R',0,'L');   
                $row+=5;
                $this->report->rpt->setXY(3,$row);                                
                $this->report->rpt->Cell (5,5,'2.','L',0,'L');
                $this->report->rpt->Cell (88,5,'Bupati  Bintan di Bandar Seri Bentan. (sebagai laporan)','R',0,'L');
                $this->report->rpt->setXY(96,$row);                
                $this->report->rpt->Cell (110,5,'','R',0,'L');                                                
                $row+=5;
                $this->report->rpt->setXY(3,$row);                                                
                $this->report->rpt->MultiCell(203,10,'Apabila ada data dan atau informasi dan atau dokumen pendukung penerbitan izin ini yang ternyata di kemudian hari terbukti tidak benar dan tidak absah, maka izin ini akan dicabut.',1,'L',false,0,'','');
            break;
        }
        $this->report->printOut($this->dataReport['recnosiup']);
        $this->report->setLink($this->dataReport['linkoutput'],'Sertifikat SIPI');
    }
}
?>