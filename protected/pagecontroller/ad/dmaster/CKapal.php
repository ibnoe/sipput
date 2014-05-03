<?php
prado::using ('Application.MainPageSA');
class CKapal extends MainPageSA {
	public function onLoad($param) {		
		parent::onLoad($param);		        
        $this->showDMaster=true;
        $this->showKapal=true;        
        $this->createObj('Pemohon');
        $this->createObj('DMaster');
		if (!$this->IsPostBack&&!$this->IsCallBack) {
            if (!isset($_SESSION['currentPageKapal'])||$_SESSION['currentPageKapal']['page_name']!='ad.dmaster.Kapal') {
                $_SESSION['currentPageKapal']=array('page_name'=>'ad.dmaster.Kapal','page_num'=>0,'search'=>false,'iduptd'=>$this->Pengguna->getDataUser('iduptd'),'dataKapal'=>array());												
			}     
            $_SESSION['currentPageKapal']['search']=false;            
			$this->populateData ();			
		}
	}    
    public function renderCallback ($sender,$param) {
		$this->RepeaterS->render($param->NewWriter);	
	}	
	public function Page_Changed ($sender,$param) {
		$_SESSION['currentPageKapal']['page_num']=$param->NewPageIndex;
		$this->populateData();
	}    
    public function filterRecord ($sender,$param) {
		$_SESSION['currentPageKapal']['search']=true;
        $_SESSION['currentPageKapal']['page_num']=0;        
        $this->populateData($_SESSION['currentPageKapal']['search']);
	}
    private function populateData ($search=false) {           
        $iduptd=$_SESSION['currentPageKapal']['iduptd'];
        if ($search) {
            $str = "SELECT RecNoKpl,NmPem,NoRegKpl,NmKpl,k.active FROM kapal k, pemohon p WHERE p.RecNoPem=k.RecNoPem AND p.iduptd=$iduptd";                        
            $txtsearch=addslashes($this->txtKriteria->Text);
            switch ($this->cmbKriteria->Text) {                
                case 'namabahan' :
                    $cluasa=" WHERE NmBahan LIKE '%$txtsearch%'";
                    $jumlah_baris=$this->DB->getCountRowsOfTable ("bahanjenisalat $cluasa",'RecNoBhn');
                    $str = "$str $cluasa";
                break;                
            }
        }else {            
            $str = "SELECT RecNoKpl,NmPem,NoRegKpl,NmKpl,k.active FROM kapal k, pemohon p WHERE p.RecNoPem=k.RecNoPem AND p.iduptd=$iduptd";        
            $jumlah_baris=$this->DB->getCountRowsOfTable ("kapal k, pemohon p WHERE p.RecNoPem=k.RecNoPem AND p.iduptd=$iduptd");		
        }		
        $this->RepeaterS->CurrentPageIndex=$_SESSION['currentPageKapal']['page_num'];
		$this->RepeaterS->VirtualItemCount=$jumlah_baris;
		$currentPage=$this->RepeaterS->CurrentPageIndex;
		$offset=$currentPage*$this->RepeaterS->PageSize;		
		$itemcount=$this->RepeaterS->VirtualItemCount;
		$limit=$this->RepeaterS->PageSize;
		if (($offset+$limit)>$itemcount) {
			$limit=$itemcount-$offset;
		}
		if ($limit < 0) {$offset=0;$limit=10;$_SESSION['currentPageKapal']['page_num']=0;}
        $str = "$str ORDER BY NoRegKpl ASC LIMIT $offset,$limit";        
		$this->DB->setFieldTable(array('RecNoKpl','NmPem','NoRegKpl','NmKpl'));
		$r=$this->DB->getRecord($str);                    
		$this->RepeaterS->DataSource=$r;
		$this->RepeaterS->dataBind();      		
    }    
    public function addProcess ($sender,$param) {
		$this->idProcess='add';   
        $listpemohon=$this->Pemohon->getListPemohon($_SESSION['currentPageKapal']['iduptd']);
        $this->cmbAddPemohon->DataSource=$listpemohon;            
        $this->cmbAddPemohon->DataBind();
    }   
    public function processNextButton($sender,$param) {              
        $this->idProcess='add';        
		if ($param->CurrentStepIndex ==0) {             
            $bahanalat=$this->DMaster->getList('bahanjenisalat WHERE enabled=1',array('RecNoBhn','NmBahan'),'NmBahan',null,1);            
            $bahanalat['none']='Pilih Bahan Kapal';
            $this->cmbAddBahanKapal->DataSource=$bahanalat;
            $this->cmbAddBahanKapal->dataBind();
            
            $tipekapal=$this->DMaster->getList("jenisalat WHERE KdJns='tipekapal' AND enabled=1",array('RecNoJns','NmJenisAlat'),'NmJenisAlat',null,1);            
            $tipekapal['none']='Pilih Tipe Kapal';
            $this->cmbAddTipeKapal->DataSource=$tipekapal;
            $this->cmbAddTipeKapal->dataBind();
            $jeniskapal=$this->DMaster->getList("jenisalat WHERE KdJns='jeniskapal' AND enabled=1",array('RecNoJns','NmJenisAlat'),'NmJenisAlat',null,1);            
            $jeniskapal['none']='Pilih Jenis Kapal';
            $this->cmbAddJenisKapal->DataSource=$jeniskapal;
            $this->cmbAddJenisKapal->dataBind();
        }
	}
    public function addNewKapalCompleted ($sender,$param) {
        $this->idProcess='add'; 
    }
    public function saveData($sender,$param) {		
        if ($this->Page->IsValid) {		
            $RecNoPem=$this->cmbAddPemohon->Text;
            $nomorregisterkapal=addslashes($this->txtAddNomorRegisterKapal->Text);
            $namakapal=addslashes($this->txtAddNamaKapal->Text);
            $namanakhoda=addslashes($this->txtAddNamaNakhoda->Text);             
            $panjangkapal=addslashes($this->txtAddPanjangKapal->Text); 			                            
            $lebarkapal=addslashes($this->txtAddLebarKapal->Text); 		
            $tinggikapal=addslashes($this->txtAddTinggiKapal->Text); 		                
            $ukurantonasekapal=addslashes($this->txtAddUkuranTonaseKapal->Text);
            $recnobhn=$this->cmbAddBahanKapal->Text;                            
            $merekmesininduk=addslashes($this->txtAddMerekMesinInduk->Text); 
            $dayamesininduk=addslashes($this->txtAddDayaMesinInduk->Text);
            $rpmmesininduk=addslashes($this->txtAddRPMMesinInduk->Text);
            $nomorserimesininduk=addslashes($this->txtAddNomorSeriMesinInduk->Text);            
            $merekmesinbantu=addslashes($this->txtAddMerekMesinBantu->Text);
            $dayamesinbantu=addslashes($this->txtAddDayaMesinBantu->Text);
            $nomorserimesinbantu=addslashes($this->txtAddNomorSeriMesinBantu->Text);
            $merekmesinpendingin=addslashes($this->txtAddMerekMesinPendingin->Text); 
            $nomorserimesinpendingin=addslashes($this->txtAddNomorSeriMesinPendingin->Text);            
            $tipekapal=addslashes($this->cmbAddTipeKapal->Text); 
            $jeniskapal=addslashes($this->cmbAddJenisKapal->Text);            
            $tempatpembuatan=addslashes($this->txtAddTempatPembuatan->Text);
            $tahunpembuatan=date('Y',$this->cmbAddTahunPembuatan->TimeStamp);
            $nomorpastahunankapal=addslashes($this->txtAddNomorPasTahunanKapaL->Text); 
            $nomorsuratukurkapal=addslashes($this->txtAddNomorSuratUkurKapal->Text);
            $nomorsertifikatkelaikandanpengawakan=addslashes($this->txtAddSertifikatKelaikandanPengawakan->Text);
            $tandaselarkapal=addslashes($this->txtAddTandaSelarKapal->Text);                     
            $benderakapal=addslashes($this->txtAddBenderaKapal->Text);
            $muatanbersih=addslashes($this->txtAddMuatanBersihKapal->Text);
            $statuskapal=$this->cmbAddStatusKapal->Text; 
            $namakapalawal=addslashes($this->txtAddNamaKapalSebelumnya->Text);
            $pergantiannama=$this->cmbAddPergantianNama->Text;
            $asalbendera=addslashes($this->txtAddAsalBendera->Text);    
            
            $str = "INSERT INTO kapal (RecNoKpl, RecNoPem,NoRegKpl,NmKpl,RecNakKpl,PjgKpl,LbrKpl,TgiKpl,GrossKpl,RecNoBhn,MrkMsnIdk,DkMsnIdk,RpmMsnIdk,NoSrMsnIdk,MrkMsnBtu,DkMsnBtu,NoSrMsnBtu,MrkMsnDgn,NoSrMsnDgn,RecNoJns_TypeKpl,RecNoJns_JnsKpl,TptBuat,ThnBuat,NoPasKpl,NoSrUkrKpl,NoStptKli,TndSlKpl,BdrKpl,muatan_bersih,status_kepemilikan,NmKplAwl,pergantian_kapal,AslBdr,date_added,date_modified) VALUES (NULL,$RecNoPem,'$nomorregisterkapal','$namakapal','$namanakhoda','$panjangkapal','$lebarkapal','$tinggikapal','$ukurantonasekapal','$recnobhn','$merekmesininduk',DkMsnIdk='$dayamesininduk','$rpmmesininduk','$nomorserimesininduk','$merekmesinbantu','$dayamesinbantu','$nomorserimesinbantu','$merekmesinpendingin','$nomorserimesinpendingin','$tipekapal','$jeniskapal','$tempatpembuatan','$tahunpembuatan','$nomorpastahunankapal','$nomorsuratukurkapal','$nomorsertifikatkelaikandanpengawakan','$tandaselarkapal','$benderakapal','$muatanbersih','$statuskapal','$namakapalawal','$pergantiannama','$asalbendera',NOW(),NOW())";
            $this->DB->insertRecord($str);
            $this->redirect('dmaster.Kapal',true);
        }
	}
    public function editRecord ($sender,$param) {		
		$this->idProcess='edit';
		$id=$this->getDataKeyField($sender,$this->RepeaterS);        
		$this->hiddenid->Value=$id;  
        
        $str = "SELECT RecNoKpl,RecNoPem,NoRegKpl,NmKpl,RecNakKpl,PjgKpl,LbrKpl,TgiKpl,GrossKpl,RecNoBhn,MrkMsnIdk,DkMsnIdk,RpmMsnIdk,NoSrMsnIdk,MrkMsnBtu,DkMsnBtu,NoSrMsnBtu,MrkMsnDgn,NoSrMsnDgn,RecNoJns_TypeKpl,RecNoJns_JnsKpl,TptBuat,ThnBuat,NoPasKpl,NoSrUkrKpl,NoStptKli,TndSlKpl,BdrKpl,muatan_bersih,status_kepemilikan,NmKplAwl,pergantian_kapal,AslBdr,active FROM kapal WHERE RecNoKpl=$id";
        $this->DB->setFieldTable(array('RecNoKpl','RecNoPem','NoRegKpl','NmKpl','RecNakKpl','PjgKpl','LbrKpl','TgiKpl','GrossKpl','RecNoBhn','MrkMsnIdk','DkMsnIdk','RpmMsnIdk','NoSrMsnIdk','MrkMsnBtu','DkMsnBtu','NoSrMsnBtu','MrkMsnDgn','NoSrMsnDgn','RecNoJns_TypeKpl','RecNoJns_JnsKpl','TptBuat','ThnBuat','NoPasKpl','NoSrUkrKpl','NoStptKli','TndSlKpl','BdrKpl','muatan_bersih','status_kepemilikan','NmKplAwl','pergantian_kapal','AslBdr','active'));
		$r=$this->DB->getRecord($str);
        $_SESSION['currentPageKapal']['dataKapal']=$r[1];
        $listpemohon=$this->Pemohon->getListPemohon($_SESSION['currentPageKapal']['iduptd']);
        $this->cmbEditPemohon->DataSource=$listpemohon;            
        $this->cmbEditPemohon->Text=$_SESSION['currentPageKapal']['dataKapal']['RecNoPem'];
        $this->cmbEditPemohon->DataBind();
        
    }
    public function processEditNextButton($sender,$param) {              
        $this->idProcess='edit';    
        $datakapal=$_SESSION['currentPageKapal']['dataKapal'];
		if ($param->CurrentStepIndex ==0) {              
            $this->txtEditNomorRegisterKapal->Text=$datakapal['NoRegKpl'];
            $this->txtEditNamaKapal->Text=$datakapal['NmKpl'];
            $this->txtEditNamaNakhoda->Text=$datakapal['RecNakKpl'];             
            $this->txtEditPanjangKapal->Text=$datakapal['PjgKpl']; 			                            
            $this->txtEditLebarKapal->Text=$datakapal['LbrKpl']; 		
            $this->txtEditTinggiKapal->Text=$datakapal['TgiKpl']; 		                
            $this->txtEditUkuranTonaseKapal->Text=$datakapal['GrossKpl'];  
            
            $bahanalat=$this->DMaster->getList('bahanjenisalat WHERE enabled=1',array('RecNoBhn','NmBahan'),'NmBahan',null,1);            
            $bahanalat['none']='Pilih Bahan Kapal';
            $this->cmbEditBahanKapal->DataSource=$bahanalat;   
            $this->cmbEditBahanKapal->Text=$datakapal['RecNoBhn'];
            $this->cmbEditBahanKapal->dataBind();
            
            $this->txtEditMerekMesinInduk->Text=$datakapal['MrkMsnIdk']; 
            $this->txtEditDayaMesinInduk->Text=$datakapal['DkMsnIdk'];
            $this->txtEditRPMMesinInduk->Text=$datakapal['RpmMsnIdk'];
            $this->txtEditNomorSeriMesinInduk->Text=$datakapal['NoSrMsnIdk'];            
            $this->txtEditMerekMesinBantu->Text=$datakapal['MrkMsnBtu'];
            $this->txtEditDayaMesinBantu->Text=$datakapal['DkMsnBtu'];
            $this->txtEditNomorSeriMesinBantu->Text=$datakapal['NoSrMsnBtu'];
            $this->txtEditMerekMesinPendingin->Text=$datakapal['MrkMsnDgn']; 
            $this->txtEditNomorSeriMesinPendingin->Text=$datakapal['NoSrMsnDgn'];    
            
            $tipekapal=$this->DMaster->getList("jenisalat WHERE KdJns='tipekapal' AND enabled=1",array('RecNoJns','NmJenisAlat'),'NmJenisAlat',null,1);            
            $tipekapal['none']='Pilih Tipe Kapal';
            $this->cmbEditTipeKapal->DataSource=$tipekapal;
            $this->cmbEditTipeKapal->Text=$datakapal['RecNoJns_TypeKpl']; 
            $this->cmbEditTipeKapal->dataBind();
            
            $jeniskapal=$this->DMaster->getList("jenisalat WHERE KdJns='jeniskapal' AND enabled=1",array('RecNoJns','NmJenisAlat'),'NmJenisAlat',null,1);            
            $jeniskapal['none']='Pilih Jenis Kapal';
            $this->cmbEditJenisKapal->DataSource=$jeniskapal;
            $this->cmbEditJenisKapal->Text=$datakapal['RecNoJns_JnsKpl'];            
            $this->cmbEditJenisKapal->dataBind();           
            
            $this->txtEditTempatPembuatan->Text=$datakapal['TptBuat'];
            $this->cmbEditTahunPembuatan->Text=$datakapal['ThnBuat'];
            $this->txtEditNomorPasTahunanKapaL->Text=$datakapal['NoPasKpl']; 
            $this->txtEditNomorSuratUkurKapal->Text=$datakapal['NoSrUkrKpl'];
            $this->txtEditSertifikatKelaikandanPengawakan->Text=$datakapal['NoStptKli'];
            $this->txtEditTandaSelarKapal->Text=$datakapal['TndSlKpl'];                     
            $this->txtEditBenderaKapal->Text=$datakapal['BdrKpl'];
            $this->txtEditMuatanBersihKapal->Text=$datakapal['muatan_bersih'];
            $this->cmbEditStatusKapal->Text=$datakapal['status_kepemilikan'];           
            
        }elseif ($param->CurrentStepIndex ==1){
            $this->txtEditNamaKapalSebelumnya->Text=$datakapal['NmKplAwl'];
            $this->cmbEditPergantianNama->Text=$datakapal['pergantian_kapal'];
            $this->txtEditAsalBendera->Text=$datakapal['AslBdr'];
        }
	}
    public function editKapalCompleted ($sender,$param) {
        $this->idProcess='edit'; 
    }
    public function updateData($sender,$param) {		
        if ($this->Page->IsValid) {		
            $id=$this->hiddenid->Value;            
            $RecNoPem=$this->cmbEditPemohon->Text;
            $nomorregisterkapal=addslashes($this->txtEditNomorRegisterKapal->Text);
            $namakapal=addslashes($this->txtEditNamaKapal->Text);
            $namanakhoda=addslashes($this->txtEditNamaNakhoda->Text);             
            $tandaselarkapal=addslashes($this->txtEditTandaSelarKapal->Text);                     
            $benderakapal=addslashes($this->txtEditBenderaKapal->Text);
            $ukurantonasekapal=addslashes($this->txtEditUkuranTonaseKapal->Text);
            $recnobhn=$this->cmbEditBahanKapal->Text;                
            $panjangkapal=addslashes($this->txtEditPanjangKapal->Text); 			                            
            $lebarkapal=addslashes($this->txtEditLebarKapal->Text); 		
            $tinggikapal=addslashes($this->txtEditTinggiKapal->Text); 		                
            $merekmesininduk=addslashes($this->txtEditMerekMesinInduk->Text); 
            $nomorserimesininduk=addslashes($this->txtEditNomorSeriMesinInduk->Text);
            $dayamesininduk=addslashes($this->txtEditDayaMesinInduk->Text);
            $rpmmesininduk=addslashes($this->txtEditRPMMesinInduk->Text);
            $merekmesinbantu=addslashes($this->txtEditMerekMesinBantu->Text);
            $nomorserimesinbantu=addslashes($this->txtEditNomorSeriMesinBantu->Text);
            $dayamesinbantu=addslashes($this->txtEditDayaMesinBantu->Text);
            $merekmesinpendingin=addslashes($this->txtEditMerekMesinPendingin->Text); 
            $nomorserimesinpendingin=addslashes($this->txtEditNomorSeriMesinPendingin->Text);
            $tipekapal=addslashes($this->cmbEditTipeKapal->Text); 
            $jeniskapal=addslashes($this->cmbEditJenisKapal->Text); 
            $tempatpembuatan=addslashes($this->txtEditTempatPembuatan->Text);
            $tahunpembuatan=$this->cmbEditTahunPembuatan->TimeStamp;
            $nomorpastahunankapal=addslashes($this->txtEditNomorPasTahunanKapaL->Text); 
            $nomorsertifikatkelaikandanpengawakan=addslashes($this->txtEditSertifikatKelaikandanPengawakan->Text); 
            $nomorsuratukurkapal=addslashes($this->txtEditNomorSuratUkurKapal->Text);
            $muatanbersih=addslashes($this->txtEditMuatanBersihKapal->Text);
            $statuskapal=$this->cmbEditStatusKapal->Text; 
            $namakapalawal=addslashes($this->txtEditNamaKapalSebelumnya->Text);
            $pergantiannama=$this->cmbEditPergantianNama->Text;
            $asalbendera=addslashes($this->txtEditAsalBendera->Text);
            
            $str="UPDATE kapal SET RecNoPem='$RecNoPem',NoRegKpl='$nomorregisterkapal',NmKpl='$namakapal',RecNakKpl='$namanakhoda',PjgKpl='$panjangkapal',LbrKpl='$lebarkapal',TgiKpl='$tinggikapal',GrossKpl='$ukurantonasekapal',RecNoBhn='$recnobhn',MrkMsnIdk='$merekmesininduk',DkMsnIdk='$dayamesininduk',RpmMsnIdk='$rpmmesininduk',NoSrMsnIdk='$nomorserimesininduk',MrkMsnBtu='$merekmesinbantu',DkMsnBtu='$dayamesinbantu',NoSrMsnBtu='$nomorserimesinbantu',MrkMsnDgn='$merekmesinpendingin',NoSrMsnDgn='$nomorserimesinpendingin',RecNoJns_TypeKpl='$tipekapal',RecNoJns_JnsKpl='$jeniskapal',TptBuat='$tempatpembuatan',ThnBuat='$tahunpembuatan',NoPasKpl='$nomorpastahunankapal',NoSrUkrKpl='$nomorsuratukurkapal',NoStptKli='$nomorsertifikatkelaikandanpengawakan',TndSlKpl='$tandaselarkapal',BdrKpl='$benderakapal',muatan_bersih='$muatanbersih',status_kepemilikan='$statuskapal',NmKplAwl='$namakapalawal',pergantian_kapal='$pergantiannama',AslBdr='$asalbendera',date_modified=NOW() WHERE RecNoKpl=$id";
            $this->DB->updateRecord($str);           
            $this->redirect('dmaster.Kapal',true);
        }
	}
    public function deleteRecord($sender,$param) {
        $id=$this->getDataKeyField($sender,$this->RepeaterS);
        $this->DB->deleteRecord("kapal WHERE RecNoKpl=$id");
        $this->redirect('dmaster.Kapal',true);
    }
}
		
