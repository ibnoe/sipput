<?php
prado::using ('Application.MainPageSA');
class CAddSIPI extends MainPageSA {    
	public function onLoad($param) {		
		parent::onLoad($param);		        
        $this->showPerizinanBaru=true; 
        $this->showAddIzinNewSIPI=true;           
        $this->createObj('Pemohon');
        $this->createObj('DMaster');
		if (!$this->IsPostBack&&!$this->IsCallBack) {
            if (!isset($_SESSION['currentPageAddSIPI'])||$_SESSION['currentPageAddSIPI']['page_name']!='ad.perizinan.AddSIPI') {
                $_SESSION['currentPageAddSIPI']=array('page_name'=>'ad.perizinan.AddSIPI','page_num'=>0,'iduptd'=>$this->Pengguna->getDataUser('iduptd'));	                
            } 
            $listpemohon=$this->Pemohon->getListPemohon($_SESSION['currentPageAddSIPI']['iduptd']);
            $this->cmbAddPemohon->DataSource=$listpemohon;            
            $this->cmbAddPemohon->DataBind();  
            
            $this->cmbAddJenisBidangUsaha->DataSource=$this->DMaster->getBidangBidangIzinUsaha(1);
            $this->cmbAddJenisBidangUsaha->dataBind();
		}
	}
    private function setDataSource () {
        $areapenangkapan=$this->DMaster->getList('areapenangkapan WHERE enabled=1',array('RecNoArea','AreaTangkap'),'AreaTangkap',null,1);
        $areapenangkapan['none']='Pilih Area/Daerah Penangkapan';
        $this->cmbAddAreaPenangkapan->DataSource=$areapenangkapan;
        $this->cmbAddAreaPenangkapan->dataBind();
        
        $lokasiusaha=$this->DMaster->getList('lokasiusaha WHERE enabled=1',array('RecNoLokasi','NamaLokasi'),'NamaLokasi',null,1);
        $lokasiusaha['none']='Pilih Lokasi Usaha';
        $this->cmbAddLokasiUsaha->DataSource=$lokasiusaha;
        $this->cmbAddLokasiUsaha->dataBind();
        
        $jenisalat=$this->DMaster->getList('jenisalat WHERE RecNoIzin=1 AND enabled=1',array('RecNoJns','NmJenisAlat'),'NmJenisAlat',null,1);
        $jenisalat['none']='Pilih Jenis Alat Penangkapan';
        $this->cmbAddJenisAlat->DataSource=$jenisalat;
        $this->cmbAddJenisAlat->dataBind();
        
        $bahanalat=$this->DMaster->getList('bahanjenisalat WHERE enabled=1',array('RecNoBhn','NmBahan'),'NmBahan',null,1);
        $bahanalat['none']='Pilih Bahan Jenis Alat';
        $this->cmbAddBahanAlat->DataSource=$bahanalat;
        $this->cmbAddBahanAlat->dataBind();            
        
    }
    public function processNextButton($sender,$param) {              
        $RecNoPem=$this->cmbAddPemohon->Text;        
        $this->Pemohon->setRecNoPem($RecNoPem,true);  
		if ($param->CurrentStepIndex ==1) {            
            if ($this->Pemohon->DataPemohon['Status']=='perorangan') {                
                $this->newsipiwizard->ActiveStepIndex=3;
                $this->setDataSource();
            }else{
                $this->Pemohon->setRecNoPem($RecNoPem);
                $this->cmbAddDaftarPerusahaan->DataSource=$this->Pemohon->getDataPerusahaan (0);
                $this->cmbAddDaftarPerusahaan->DataBind();
            }
        }elseif ($param->CurrentStepIndex ==2) {
            $this->setDataSource();
        }
	}    
    public function changePerusahaanPemohon ($sender,$param) {
        $id=$this->cmbAddDaftarPerusahaan->Text;
        $str = "SELECT RecStsCom,NmCom,NoAkte,TglAkte,NPWPCom,AlmtCom,TelCom,FaxComp,AlmtComCab FROM perusahaan WHERE IdCom='$id'";
        $this->DB->setFieldTable(array('RecStsCom','NmCom','NoAkte','TglAkte','NPWPCom','AlmtCom','TelCom','FaxComp','AlmtComCab'));
        $r=$this->DB->getRecord($str);     
        if (isset($r[1])) {
            $this->hiddenidperusahaan->Value=$id;
            $this->lblStatusPerusahaan->Text=$r[1]['RecStsCom'];                            
            $this->lblNamaPerusahaan->Text=$r[1]['NmCom'];
            $this->lblNoAktePerusahaan->Text=$r[1]['NoAkte'];
            $this->lblTglAktePendirianPerusahaan->Text=$this->TGL->tanggal('d F Y',$r[1]['TglAkte']);
            $this->lblNoNPWPPerusahaan->Text=$r[1]['NPWPCom'];
            $this->lblAlamatPerusahaan->Text=$r[1]['AlmtCom'];
            $this->lblNoTelpPerusahaan->Text=$r[1]['TelCom'];
            $this->lblNoFaxPerusahaan->Text=$r[1]['FaxComp'];
            $this->lblAlamatKantorCabangPerusahaan->Text=$r[1]['AlmtComCab'];              
        }else {
            $this->hiddenidperusahaan->Value='';
            $this->lblStatusPerusahaan->Text='-';
            $this->lblNamaPerusahaan->Text='-';
            $this->lblNoAktePerusahaan->Text='-';
            $this->lblTglAktePendirianPerusahaan->Text='-';
            $this->lblNoNPWPPerusahaan->Text='-';
            $this->lblAlamatPerusahaan->Text='-';
            $this->lblNoTelpPerusahaan->Text='-';
            $this->lblNoFaxPerusahaan->Text='-';
            $this->lblAlamatKantorCabangPerusahaan->Text='-';
        }
    }
    public function addNewSIPICompleted ($sender,$param) {
        
    }
    public function saveData ($sender,$param) {
        if ($this->IsValid) {
            $this->createObj('Perizinan');
            $this->DB->query('BEGIN');      
            
            //insert siup            
            $NoRecPem=$this->cmbAddPemohon->Text;
            $no_surat=addslashes($this->txtAddNomorSurat->Text);
            $tgl_surat= date('Y-m-d',$this->cmbAddTanggalSurat->TimeStamp);          
            $statuspemohon=$this->hiddenstatuspemohon->Value;
            $iduptd=$this->Pengguna->getDataUser('iduptd');
            $nama_uptd=$this->Pengguna->getDataUser('nama_uptd');            
            $this->Perizinan->setRecNoIzin(1);
            $noregsiup=$this->Perizinan->createNewNoRegSIUP($iduptd);
            $modalsiup=$this->setup->toInteger($this->txtAddModalUsaha->Text); 
            $recnolokasi=addslashes($this->cmbAddLokasiUsaha->Text);
            $str = "INSERT INTO siup (RecNoSiup,iduptd,nama_uptd,RecNoIzin,JnsDtSIUP,NoRegSiup,NoUrutSiup,NoSrtSiup,TglSrtSiup,JnsDtPemSIUP,RecNoPem,ModalSiup,RecNoLokasi,date_added,date_modified) VALUES (NULL,$iduptd,'$nama_uptd',1,'baru','{$noregsiup['noreg']}','{$noregsiup['nourut']}','$no_surat','$tgl_surat','$statuspemohon','$NoRecPem','$modalsiup','$recnolokasi',NOW(),NOW())";
            if ($this->DB->insertRecord($str)) {
                //insert pemohon{                
                $recnosiup=$this->DB->getLastInsertID ();
                if ($statuspemohon == 'perorangan') {
                    //insert siup_data_pemohon
                    $str="INSERT INTO siup_data_pemohon (RecNoSiup,NmPem,KtpPem,AlmtPem,TelpPem,NpwpPem,Foto) SELECT '$recnosiup',NmPem,KtpPem,AlmtPem,TelpPem,NpwpPem,Foto FROM pemohon WHERE RecNoPem=$NoRecPem";
                    $this->DB->insertRecord($str);
                }else {
                    //insert siup_data_pemohon
                    $str="INSERT INTO siup_data_pemohon (RecNoSiup,NmPem,KtpPem,AlmtPem,TelpPem,NpwpPem,Foto) SELECT '$recnosiup',NmPem,KtpPem,AlmtPem,TelpPem,NpwpPem,Foto FROM pemohon WHERE RecNoPem=$NoRecPem";
                    $this->DB->insertRecord($str);
                    //insert siup_data_perusahaan
                    $idcom=$this->cmbAddDaftarPerusahaan->Text;
                    $str="INSERT INTO siup_data_perusahaan (IdCom,RecNoSiup,RecStsCom,NmCom,NoAkte,TglAkte,NPWPCom,AlmtCom,TelCom,FaxComp,AlmtComCab,iduptd,nama_uptd) SELECT IdCom,'$recnosiup',RecStsCom,NmCom,NoAkte,TglAkte,NPWPCom,AlmtCom,TelCom,FaxComp,AlmtComCab,iduptd,nama_uptd FROM perusahaan WHERE RecNoPem=$NoRecPem AND IdCom=$idcom";                    
                    $this->DB->insertRecord($str);
                }    
                
                //insert data bup
                $idbidangizin=$this->cmbAddJenisBidangUsaha->Text; 
                $str = "INSERT INTO bup (RecNoBup,RecNoSiup,idbidangizin,JnsDtBUP,NoRegBup,NoSrtBup,TglSrtBup,date_added,date_modified) VALUES (NULL,$recnosiup,$idbidangizin,'baru','{$noregsiup['noreg']}','$no_surat','$tgl_surat',NOW(),NOW())";
                $this->DB->insertRecord($str);                
                
                $recnobup=$this->DB->getLastInsertID ();
                $recnoarea=$this->cmbAddAreaPenangkapan->Text;
                $koordinatpenangkapan=addslashes($this->txtAddKoordinatPenangkapan->Text);
                $str = "INSERT INTO relasi_sipi (idrelasi_sipi,RecNoBup,RecNoKpl,RecNoArea,KoordTkp) VALUES (NULL,$recnobup,'',$recnoarea,'$koordinatpenangkapan')";                
                $this->DB->insertRecord($str);   
                
                $recnojns=$this->cmbAddJenisAlat->Text;
                $jumlahalat=addslashes($this->txtAddJumlahAlat->Text);
                $recnobahan=$this->cmbAddBahanAlat->Text;
                $ukuranpanjangalat=addslashes($this->txtAddUkuranPanjangAlat->Text); 
                $ukuranlebaralat=addslashes($this->txtAddUkuranLebarAlat->Text); 			                            
                $ukurantinggialat=addslashes($this->txtAddUkuranTinggiAlat->Text); 			                            
                $ukuransizemataalat=addslashes($this->txtAddUkuranSizeMataAlat->Text); 
                $jumlahtenagakerja=addslashes($this->txtAddJumlahTenagaKerja->Text);
                $pembagianhasilusahabagihasil=addslashes($this->txtAddPembagianHasilUsahaBagiHasil->Text); 
                $pembagianhasilusahaupah=addslashes($this->txtAddPembagianHasilUsahaUpah->Text); 
                $pembagianhasilusahagaji=addslashes($this->txtAddPembagianHasilUsahaGaji->Text);
                $pemasaranhasilusahalokal=addslashes($this->txtAddPemasaranHasilUsahaLokal->Text);   			                                                    
                $pemasaranhasilusahadomestik=addslashes($this->txtAddPemasaranHasilUsahaDomestik->Text);   			                                                    
                $pemasaranhasilusahainternasional=addslashes($this->txtAddPemasaranHasilUsahaInternasional->Text); 
                $str = "INSERT INTO valuebidangusaha (RecNoValue,RecNoBup,RecNoJns,JmlJns,RecNoBhn,UkrPjg,UkrLbr,UkrTgiDlm,UkrMata,JmlAbkTk,hasilusaha_bagihasil,hasilusaha_gaji,hasilusaha_upah,pemasaran_lokal,pemasaran_nasional,pemasaran_internasional)
                        VALUE (NULL,$recnobup,$recnojns,'$jumlahalat',$recnobahan,'$ukuranpanjangalat','$ukuranlebaralat','$ukurantinggialat','$ukuransizemataalat','$jumlahtenagakerja','$pembagianhasilusahabagihasil','$pembagianhasilusahagaji','$pembagianhasilusahaupah','$pemasaranhasilusahalokal','$pemasaranhasilusahadomestik','$pemasaranhasilusahainternasional')";
                $this->DB->insertRecord($str);   
                
                $this->DB->query('COMMIT');
                $this->redirect('perizinan.AddSIPI',true);
            }else {
                $this->DB->query('ROLLBACK');
            } 
        }
    }
}
		