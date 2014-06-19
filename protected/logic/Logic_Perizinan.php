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
     * digunakan untuk mendapatkan persyaratan dokumen pengajuan
     * @param type $group
     */
    public function getPersyaratanPengajuan ($group,$mode=5) {        
        $r = $this->getList("persyaratan_permohonan WHERE `group`='$group' AND active=1", array('idpersyaratan','nama_persyaratan'),null,null,$mode);
        $result = array();
        foreach ($r as $k=>$v) {
            $result[$k] = "&nbsp;&nbsp;$v";
        }
        return $result;
    }
    /**
     * digunakan untuk mendapatkan persyaratan dokumen pengajuan milik pemohon
     * @param type $group
     */
    public function getPersyaratanPengajuanMilikPemohon ($group,$recnosiup,$mode=5) {   
        $r = $this->getList("persyaratan_siup WHERE `group`='$group' AND RecNoSiup=$recnosiup", array('idpersyaratan','nama_persyaratan'),null,null,$mode);
        switch ($mode) {
            case 0 :
                $result=$r;
            break;
            case 5 :
                $result = array();
                foreach ($r as $k=>$v) {
                    $result[$k] = "&nbsp;&nbsp;$v";
                }
            break;
        }        
        return $result;
    }
    /**
     * digunakan untuk mencetak sertifikat SIUP
     */
    public function printSIUP () {    
        $nama_dinas=strtoupper($this->report->setup->getSettingValue('config_nama_dinas'));
        $nama_kabupaten=  strtoupper($this->report->setup->getSettingValue('config_nama_kabupaten'));
        
        $recnosiup=$this->dataReport['recnosiup'];
        $str = "SELECT NoSiup,TglStartSiup,	TglLastSiup FROM siup WHERE RecNoSiup=$recnosiup";
        $this->db->setFieldTable(array('NoSiup','TglStartSiup','TglLastSiup'));
        $r=$this->db->getRecord($str);
        $datasiup=$r[1];
        switch ($this->dataReport['outputmode']) {
            case 'pdf' :                
                $this->report->setMode('pdf'); 
                $this->report->setHeaderSertifikat('SIUP');
                
                $row=$this->report->currentRow;
                $this->report->rpt->setXY(3,$row);
                $this->report->rpt->Cell (203,5,'SURAT IZIN USAHA PERIKANAN',0,0,'C');
                $row+=5;
                $this->report->rpt->setXY(3,$row);
                $this->report->rpt->Cell (203,5,'DI BIDANG PENANGKAPAN IKAN',0,0,'C');
                
                $row+=5;
                $this->report->rpt->setXY(3,$row);
                $this->report->rpt->SetFont ('helvetica','',12);
                $this->report->rpt->Cell (203,5,'NOMOR : '. $datasiup['NoSiup'],0,0,'C');
                
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
                $this->report->rpt->MultiCell(55,10,$this->dataReport['NmPem'],'R','L',false,0,'','');
                $this->report->rpt->Cell (110,5,'SURAT PERMOHONAN SIUP','R',0,'L');                
                $row+=5;                
                $this->report->rpt->setXY(3,$row);                
                $this->report->rpt->Cell (35,5,'PERORANGAN','L',0,'L');                
                $this->report->rpt->setXY(96,$row);
                $this->report->rpt->Cell (35,5,'NOMOR SURAT','L',0,'L');         
                $this->report->rpt->Cell (3,5,':',0,0,'L');
                $this->report->rpt->Cell (72,5,'-','R',0,'L');
                $row+=5;                
                $this->report->rpt->setXY(3,$row);                
                $this->report->rpt->Cell (35,5,'ALAMAT','L',0,'L');
                $this->report->rpt->MultiCell(3,10,':',0,'C',false,0,'','');
                $this->report->rpt->MultiCell(55,10,$this->dataReport['AlmtPem'],0,'L',false,0,'','');                                
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
                $this->report->rpt->Cell (55,5,$this->dataReport['TelpPem'],0,0,'L');                 
                $this->report->rpt->setXY(96,$row);
                $this->report->rpt->Cell (110,5,'USAHA PENANGKAPAN IKAN',1,0,'L');
                $row+=5;                
                $this->report->rpt->setXY(3,$row); 
                $this->report->rpt->Cell (35,5,'NO.FAX','L',0,'L');
                $this->report->rpt->Cell (3,5,':',0,0,'C');
                $this->report->rpt->Cell (55,5,'-',0,0,'L');
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
                $this->report->rpt->Cell (55,5,$this->dataReport['NpwpPem'],'R',0,'L');                
                $row+=5;
                $this->report->rpt->SetFont ('helvetica','',8);
                $this->report->rpt->setXY(3,$row);                
                $this->report->rpt->Cell (35,5,'NO.AKTE PENDIRIAN /','L',0,'L');
                $this->report->rpt->MultiCell(3,10,':',0,'C',false,0,'','');
                $this->report->rpt->MultiCell(55,10,'-','R','L',false,0,'','');                
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
                $this->report->rpt->MultiCell(55,10,$this->dataReport['NmPem'],'R','L',false,0,'','');
                $this->report->rpt->setXY(96,$row);                
                $mulaiberlaku=$this->report->tgl->tanggal('j F Y',$datasiup['TglStartSiup']);
                $this->report->rpt->Cell (110,5,"SIUP INI BERLAKU SEJAK TANGGAL : $mulaiberlaku",'R',0,'L');                                                
                $row+=5;                
                $this->report->rpt->setXY(3,$row);                
                $this->report->rpt->Cell (35,5,'JAWAB','L',0,'L');
                $this->report->rpt->setXY(96,$row);                
                $selesaiberlaku=$this->report->tgl->tanggal('j F Y',$datasiup['TglLastSiup']);
                $this->report->rpt->Cell (110,5,"SAMPAI DENGAN TANGGAL : $selesaiberlaku",'R',0,'L');                                                
                $row+=5;
                $this->report->rpt->SetFont ('helvetica','',8);
                $this->report->rpt->setXY(3,$row);                
                $this->report->rpt->Cell (35,5,'NO.KTP PENANGGUNG','L',0,'L');
                $this->report->rpt->MultiCell(3,10,':',0,'C',false,0,'','');
                $this->report->rpt->MultiCell(55,10,$this->dataReport['KtpPem'],'R','L',false,0,'','');
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
        $this->report->setLink($this->dataReport['linkoutput'],'Sertifikat SIUP');
    }
    /**
     * digunakan untuk mencetak sertifikat SIPI
     */
    public function printSIPI () {
        $nama_dinas=strtoupper($this->report->setup->getSettingValue('config_nama_dinas'));
        $nama_kabupaten=  strtoupper($this->report->setup->getSettingValue('config_nama_kabupaten'));      
        
        $recnobup=$this->dataReport['recnobup'];
        echo $str = "SELECT s.NoSiup,s.TglSahSiup,bup.NoBUP,k.NoRegKpl,k.NmKpl,k.MrkMsnIdk,k.NoSrMsnIdk,k.DkMsnIdk,k.GrossKpl,k.status_kepemilikan,bup.TglLastBUP FROM siup s,bup,relasi_sipi rs,kapal k WHERE bup.RecNoSiup=s.RecNoSiup AND rs.RecNoBup=bup.RecNoBup AND k.RecNoKpl=rs.RecNoKpl AND bup.RecNoBup=$recnobup";
        $this->db->setFieldTable(array('NoSiup','TglSahSiup','NoBUP','NoRegKpl','NmKpl','MrkMsnIdk','NoSrMsnIdk','DkMsnIdk','GrossKpl','status_kepemilikan','TglLastBUP'));
        $r=$this->db->getRecord($str);
        $datasipi=$r[1];
        switch ($this->dataReport['outputmode']) {
            case 'pdf' :                
                $this->report->setMode('pdf'); 
                $this->report->setHeaderSertifikat('SIPI');
                $row=$this->report->currentRow;
                $this->report->rpt->setXY(3,$row);
                $this->report->rpt->Cell (203,5,'SURAT IZIN PENANGKAPAN IKAN',0,0,'C');              
                $row+=5;
                $this->report->rpt->setXY(3,$row);
                $this->report->rpt->SetFont ('helvetica','',12);
                $this->report->rpt->Cell (203,5,'NOMOR : '.$datasipi['NoBUP'],0,0,'C');
                
                $row+=10;
                $this->report->rpt->setXY(3,$row);
                $this->report->rpt->SetFont ('helvetica','B',10);
                $this->report->rpt->Cell (104,5,'PERUSAHAAN',1,0,'C');
                $this->report->rpt->Cell (99,5,'REFERENSI',1,0,'C');
                $row+=5;
                $this->report->rpt->SetFont ('helvetica','',8);
                $this->report->rpt->setXY(3,$row);                
                $this->report->rpt->Cell (42,5,'NAMA PERUSAHAAN /','L',0,'L');
                $this->report->rpt->MultiCell(3,10,':',0,'C',false,0,'','');
                $this->report->rpt->MultiCell(59,10,$this->dataReport['NmPem'],'R',1,false,0,'','');                
                $this->report->rpt->Cell (30,5,'NO.SIUP',0,0,'L');         
                $this->report->rpt->Cell (3,5,':',0,0,'L');
                $this->report->rpt->Cell (66,5,$datasipi['NoSiup'],'R',0,'L');
                $row+=5;                
                $this->report->rpt->setXY(3,$row);                
                $this->report->rpt->Cell (42,5,'PERORANGAN','L',0,'L');                
                $this->report->rpt->setXY(107,$row);
                $this->report->rpt->Cell (30,5,'TANGGAL SIUP','L',0,'L');         
                $this->report->rpt->Cell (3,5,':',0,0,'L');
                $this->report->rpt->Cell (66,5,$this->report->tgl->Tanggal ('d F Y',$datasipi['TglSahSiup']),'R',0,'L');
                $row+=5;                
                $this->report->rpt->setXY(3,$row);                
                $this->report->rpt->Cell (42,5,'ALAMAT','L',0,'L');
                $this->report->rpt->MultiCell(3,10,':',0,'C',false,0,'','');
                $this->report->rpt->MultiCell(59,10,$this->dataReport['AlmtPem'],'R','L',false,0,'','');                                
                $this->report->rpt->Cell (99,5,'','R',0,'L');                                 
                $row+=5;
                $this->report->rpt->setXY(3,$row);                
                $this->report->rpt->Cell (42,5,'','L',0,'L');
                $this->report->rpt->setXY(107,$row);                
                $this->report->rpt->Cell (99,5,'SURAT PERMOHONAN SIPI','R',0,'L');
                $row+=5;                
                $this->report->rpt->SetFont ('helvetica','',8);
                $this->report->rpt->setXY(3,$row); 
                $this->report->rpt->Cell (104,5,'','L',0,'L');          
                $this->report->rpt->Cell (30,5,'NOMOR/TGL','L',0,'L');         
                $this->report->rpt->Cell (3,5,':',0,0,'L');
                $this->report->rpt->Cell (66,5,'523.33/UPTD-PUP/BP-M/150','R',0,'L');
                $row+=5;
                $this->report->rpt->setXY(3,$row);
                $this->report->rpt->SetFont ('helvetica','B',10);
                $this->report->rpt->Cell (104,5,'IDENTITAS KAPAL',1,0,'C');
                $this->report->rpt->SetFont ('helvetica','',8);
                $this->report->rpt->Cell (30,5,'TANGGAL',0,0,'L');         
                $this->report->rpt->Cell (3,5,':',0,0,'L');
                $this->report->rpt->Cell (66,5,'06 MEI 2013','R',0,'L');
                $row+=5;
                $this->report->rpt->setXY(3,$row);                
                $this->report->rpt->Cell (5,5,'1.','L',0,'L');
                $this->report->rpt->Cell (37,5,'NAMA KAPAL',0,0,'L');
                $this->report->rpt->Cell (3,5,':',0,0,'L');
                $this->report->rpt->Cell (59,5,$datasipi['NmKpl'],'R',0,'L');                 
                $this->report->rpt->Cell (30,5,'TANDA TERIMA',0,0,'L');         
                $this->report->rpt->Cell (3,5,':',0,0,'L');
                $this->report->rpt->Cell (66,5,'-','R',0,'L');
                $row+=5;
                $this->report->rpt->setXY(3,$row);
                $this->report->rpt->Cell (5,5,'2.','L',0,'L');
                $this->report->rpt->Cell (37,5,'TEMPAT & NO.REGISTER/',0,0,'L');                
                $this->report->rpt->MultiCell(3,15,':',0,'C',false,0,'','');
                $this->report->rpt->MultiCell(59,15,$datasipi['NoRegKpl'],0,'L',false,0,'','');                                
                $this->report->rpt->Cell (30,5,'STATUS','L',0,'L');         
                $this->report->rpt->Cell (3,5,':',0,0,'L');
                $this->report->rpt->Cell (66,5,'-','R',0,'L');
                $row+=5;
                $this->report->rpt->setXY(3,$row);
                $this->report->rpt->Cell (5,5,'','L',0,'L');
                $this->report->rpt->Cell (37,5,'NO.GRESSE AKTE/BUKU',0,0,'L');                
                $this->report->rpt->setXY(107,$row);                
                $this->report->rpt->Cell (30,5,'NO.ARMADA','L',0,'L');         
                $this->report->rpt->Cell (3,5,':',0,0,'L');
                $this->report->rpt->Cell (66,5,'-','R',0,'L');
                $row+=5;
                $this->report->rpt->setXY(3,$row);
                $this->report->rpt->Cell (5,5,'','L',0,'L');
                $this->report->rpt->Cell (37,5,'KAPAL PERIKANAN',0,0,'L');
                $this->report->rpt->setXY(107,$row);                
                $this->report->rpt->Cell (30,5,'TANGGAL TERIMA','L',0,'L');         
                $this->report->rpt->MultiCell(3,10,':',0,'C',false,0,'','');
                $this->report->rpt->MultiCell(66,10,'-','R','L',false,0,'','');
                $row+=5;
                $this->report->rpt->setXY(3,$row);                
                $this->report->rpt->Cell (5,5,'3.','L',0,'L');
                $this->report->rpt->Cell (37,5,'NAMA PANGGILAN',0,0,'L');
                $this->report->rpt->Cell (3,5,':',0,0,'L');
                $this->report->rpt->Cell (59,5,'-','R',0,'L');
                $this->report->rpt->Cell (30,5,'SSBP LUNAS',0,0,'L');                         
                $row+=5;
                $this->report->rpt->setXY(3,$row);                
                $this->report->rpt->Cell (5,5,'4.','L',0,'L');
                $this->report->rpt->Cell (37,5,'ASAL KAPAL',0,0,'L');
                $this->report->rpt->Cell (3,5,':',0,0,'L');
                $this->report->rpt->Cell (59,5,'KM. RESTI',0,0,'L');                        
                $this->report->rpt->Cell (99,5,'',1,0,'C');
                $row+=5;
                $this->report->rpt->setXY(3,$row);                
                $this->report->rpt->SetFont ('helvetica','B',10);
                $this->report->rpt->Cell (104,5,'ALAT PENANGKAPAN IKAN/JENIS IKAN',1,0,'C');                                               
                $this->report->rpt->Cell (99,5,'DAERAH PENANGKAPAN',1,0,'C');
                
                $row+=5;
                $this->report->rpt->setXY(3,$row);          
                $this->report->rpt->SetFont ('helvetica','',8); 
                $this->report->rpt->Cell (42,5,'JENIS ALAT TANGKAP','L',0,'L');                
                $this->report->rpt->Cell (3,5,':',0,0,'C');
                $this->report->rpt->Cell (59,5,'BUBU',0,0,'L');                
                $this->report->rpt->Cell (99,5,'JALUR PENANGKAPAN 1.a',1,0,'C');                
                $row+=5;
                $this->report->rpt->setXY(3,$row);                    
                $this->report->rpt->Cell (42,5,'JUMLAH','L',0,'L');                
                $this->report->rpt->Cell (3,5,':',0,0,'C');
                $this->report->rpt->Cell (59,5,'15 BUAH',0,0,'L'); 
                $this->report->rpt->SetFont ('helvetica','B',10);
                $this->report->rpt->Cell (99,5,'DAERAH PENANGKAPAN TERLARANG',1,0,'C');                
                $row+=5;
                $this->report->rpt->setXY(3,$row);        
                $this->report->rpt->SetFont ('helvetica','',8);
                $this->report->rpt->Cell (42,5,'KAPAL','L',0,'L');                
                $this->report->rpt->Cell (3,5,':',0,0,'C');
                $this->report->rpt->Cell (59,5,'PENANGKAPAN IKAN',0,0,'L');                
                $this->report->rpt->Cell (99,5,'JALUR PENANGKAPAN 1.',1,0,'C');                
                $row+=5;
                $this->report->rpt->setXY(3,$row);                
                $this->report->rpt->SetFont ('helvetica','B',10);
                $this->report->rpt->Cell (104,5,'STATUS KAPAL',1,0,'C');                                
                $this->report->rpt->Cell (99,5,'PELABUHAN PANGKALAN',1,0,'C');                
                $row+=5;
                $this->report->rpt->setXY(3,$row);                                
                $this->report->rpt->SetFont ('helvetica','',8);
                $this->report->rpt->Cell (42,5,'MILIK SENDIRI','L',0,'L');                
                $this->report->rpt->Cell (3,5,':',0,0,'C');
                $this->report->rpt->Cell (59,5,$this->getStatusKepemilikanKapal($datasipi['status_kepemilikan']),0,0,'L');                                
                $this->report->rpt->Cell (99,5,'DESA KELONG – KEC.BINTAN PESISIR',1,0,'C');
                $row+=5;
                $this->report->rpt->setXY(3,$row);                                                              
                $this->report->rpt->Cell (42,5,'SEWA/KERJA SAMA','L',0,'L');                
                $this->report->rpt->Cell (3,5,':',0,0,'C');
                $this->report->rpt->Cell (59,5,'-',0,0,'L');                                
                $this->report->rpt->SetFont ('helvetica','B',10);
                $this->report->rpt->Cell (99,5,'ANAK BUAH KAPAL',1,0,'C');
                $row+=5;
                $this->report->rpt->setXY(3,$row);                                
                $this->report->rpt->Cell (104,5,'SPESIFIKASI KAPAL',1,0,'C');                
                $this->report->rpt->SetFont ('helvetica','',8);
                $this->report->rpt->Cell (99,5,'4 ORANG',1,0,'C');
                $row+=5;
                $this->report->rpt->setXY(3,$row);                               
                $this->report->rpt->Cell (5,5,'1.','L',0,'L');
                $this->report->rpt->Cell (37,5,'BERAT KOTOR (GT)',0,0,'L');
                $this->report->rpt->Cell (3,5,':',0,0,'L');
                $this->report->rpt->Cell (59,5,$datasipi['GrossKpl'],0,0,'L');                        
                $this->report->rpt->SetFont ('helvetica','B',10);
                $this->report->rpt->Cell (99,5,'MASA BERLAKU IZIN',1,0,'C');
                $row+=5;
                $this->report->rpt->setXY(3,$row);          
                $this->report->rpt->SetFont ('helvetica','',8);
                $this->report->rpt->Cell (5,5,'2.','L',0,'L');
                $this->report->rpt->Cell (37,5,'MUATAN BERSIH',0,0,'L');
                $this->report->rpt->Cell (3,5,':',0,0,'L');
                $this->report->rpt->Cell (59,5,'-','R',0,'L');                                           
                $this->report->rpt->Cell (99,5,'SURAT IZIN PENANGKAPAN IKAN INI BERLAKU','R',0,'L');                
                $row+=5;
                $this->report->rpt->setXY(3,$row);                               
                $this->report->rpt->Cell (5,5,'3.','L',0,'L');
                $this->report->rpt->Cell (37,5,'MEREK MESIN',0,0,'L');
                $this->report->rpt->Cell (3,5,':',0,0,'L');
                $this->report->rpt->Cell (59,5,$datasipi['MrkMsnIdk'],'R',0,'L');     
                $tanggal=$this->report->tgl->tanggal('d F Y',$datasipi['TglLastBUP']);
                $this->report->rpt->Cell (99,5,"SAMPAI DENGAN TANGGAL $tanggal",'R',0,'L');
                $row+=5;
                $this->report->rpt->setXY(3,$row);                               
                $this->report->rpt->Cell (5,5,'4.','L',0,'L');
                $this->report->rpt->Cell (37,5,'KEKUATAN MESIN',0,0,'L');
                $this->report->rpt->Cell (3,5,':',0,0,'L');
                $this->report->rpt->Cell (59,5,$datasipi['DkMsnIdk'],'R',0,'L');                              
                $this->report->rpt->SetFont ('helvetica','',8);
                $this->report->rpt->Cell (99,5,'','R',0,'C');
                $row+=5;
                $this->report->rpt->setXY(3,$row);                               
                $this->report->rpt->Cell (5,5,'5.','L',0,'L');
                $this->report->rpt->Cell (37,5,'NO.MESIN',0,0,'L');
                $this->report->rpt->Cell (3,5,':',0,0,'L');
                $this->report->rpt->Cell (59,5,$datasipi['NoSrMsnIdk'],'R',0,'L');                             
                $this->report->rpt->Cell (99,5,'','R',0,'C');
                $row+=5;
                $this->report->rpt->setXY(3,$row);                
                $this->report->rpt->SetFont ('helvetica','B',10);
                $this->report->rpt->Cell (104,5,'CATATAN',1,0,'C');
                $this->report->rpt->setXY(107,$row);               
                $this->report->rpt->SetFont('helvetica','',8);
                $this->report->rpt->Cell (99,5,'BANDAR SERI BINTAN, 10 JULI 2014','RT',1,'L');                                                
                $row+=5;
                $this->report->rpt->setXY(3,$row);                                
                $this->report->rpt->Cell (5,5,'1.','L',0,'L');
                $this->report->rpt->Cell (99,5,'Memperhatikan UU Nomor 45 Tahun 2009 tentang Perubahan Atas UU ','R',0,'L');
                $this->report->rpt->setXY(107,$row);                               
                $this->report->rpt->Cell (99,5,"An. BUPATI $nama_kabupaten",'R',0,'C');                                                
                $row+=5;
                $this->report->rpt->setXY(3,$row);                                
                $this->report->rpt->Cell (5,5,'','L',0,'L');
                $this->report->rpt->Cell (99,5,'Nomor 31 Tahun 2004 Tentang Perikanan','R',0,'L');
                $this->report->rpt->setXY(107,$row);                                               
                $this->report->rpt->MultiCell(99,10,"KEPALA $nama_dinas $nama_kabupaten",'R','C',false,0,'','');
                $row+=5;
                $this->report->rpt->setXY(3,$row);                                
                $this->report->rpt->Cell (5,5,'2.','L',0,'L');
                $this->report->rpt->Cell (99,5,'Memperhatikan Perda Nomor 5 Tahun 2011 tentang Retribusi Perizinan','R',0,'L');
                $this->report->rpt->setXY(107,$row);                
                $this->report->rpt->Cell (99,5,'','R',0,'L');                                                
                $row+=5;
                $this->report->rpt->setXY(3,$row);                                
                $this->report->rpt->Cell (5,5,'','L',0,'L');
                $this->report->rpt->Cell (99,5,'Tertentu','R',0,'L');
                $this->report->rpt->setXY(107,$row);                
                $this->report->rpt->Cell (99,5,'','R',0,'L');                                                
                $row+=5;
                $this->report->rpt->setXY(3,$row);                                
                $this->report->rpt->Cell (5,5,'3.','L',0,'L');
                $this->report->rpt->Cell (99,5,'Tidak menggunakan bahan kimia, bahan peledak dan jenis alat Tangkap','R',0,'L');
                $this->report->rpt->setXY(107,$row);                
                $this->report->rpt->Cell (99,5,'','R',0,'L');                                                
                $row+=5;
                $this->report->rpt->setXY(3,$row);                                
                $this->report->rpt->Cell (5,5,'','L',0,'L');
                $this->report->rpt->Cell (99,5,'yang dilarang/ dapat merusak lingkungan','R',0,'L');
                $this->report->rpt->setXY(107,$row);                
                $this->report->rpt->Cell (99,5,'','R',0,'L');                                                
                $row+=5;
                $this->report->rpt->setXY(3,$row);                                
                $this->report->rpt->Cell (5,5,'4.','L',0,'L');
                $this->report->rpt->Cell (99,5,'Tidak mengambil bunga karang (Coral) dan jenis ikan yang dilindungi','R',0,'L');
                $this->report->rpt->setXY(107,$row);                
                $this->report->rpt->Cell (20,5,'NAMA',0,0,'L');         
                $this->report->rpt->Cell (3,5,':',0,0,'L');
                $this->report->rpt->Cell (76,5,'Drs. WAN RUDY ISKANDAR, M.M','R',0,'L');                                                       
                $row+=5;
                $this->report->rpt->setXY(3,$row);                                
                $this->report->rpt->Cell (5,5,'5.','L',0,'L');
                $this->report->rpt->Cell (99,5,'Tidak untuk melakukan kegiatan selain kegiatan penangkapan ikan','R',0,'L');
                $this->report->rpt->setXY(107,$row);                
                $this->report->rpt->Cell (20,5,'NIP',0,0,'L');         
                $this->report->rpt->Cell (3,5,':',0,0,'L');
                $this->report->rpt->Cell (76,5,'191919191919191','R',0,'L');
                $row+=5;
                $this->report->rpt->setXY(3,$row);                                
                $this->report->rpt->Cell (5,5,'6.','L',0,'L');
                $this->report->rpt->Cell (99,5,'Menyampaikan laporan kegiatan penangkapan ikan setiap 3 Bulan sekali','R',0,'L');
                $this->report->rpt->setXY(107,$row);                
                $this->report->rpt->Cell (99,5,'','R',0,'L');                                                                
                $row+=5;
                $this->report->rpt->setXY(3,$row);                                
                $this->report->rpt->Cell (5,5,'7.','L',0,'L');
                $this->report->rpt->Cell (99,5,'Menyampaikan perpanjangan SIPI setelah masa berlakunya habis','R',0,'L');
                $this->report->rpt->setXY(107,$row);                
                $this->report->rpt->Cell (99,5,'','R',0,'L');    
                $row+=5;
                $this->report->rpt->setXY(3,$row);                                                
                $this->report->rpt->MultiCell(203,10,'Apabila ada data dan atau informasi dan atau dokumen pendukung penerbitan izin ini yang ternyata di kemudian hari terbukti tidak benar dan tidak absah, maka izin ini akan dicabut.',1,'L',false,0,'','');
            break;
        }
        $this->report->printOut($this->dataReport['recnobup']);
        $this->report->setLink($this->dataReport['linkoutput'],'Sertifikat SIPI');
    }
        
}
?>