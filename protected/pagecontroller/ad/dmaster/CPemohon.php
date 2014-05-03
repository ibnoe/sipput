<?php
prado::using ('Application.MainPageSA');
class CPemohon extends MainPageSA {
	public function onLoad($param) {		
		parent::onLoad($param);		
        $this->showDMaster=true;
        $this->showPemohon=true;        
        $this->createObj('DMaster');
		if (!$this->IsPostBack&&!$this->IsCallBack) {
            if (!isset($_SESSION['currentPagePemohon'])||$_SESSION['currentPagePemohon']['page_name']!='ad.dmaster.Pemohon') {
                $_SESSION['currentPagePemohon']=array('page_name'=>'ad.dmaster.Pemohon','page_num'=>0,'search'=>false,'iduptd'=>$this->Pengguna->getDataUser('iduptd'));	                
            }       
            $_SESSION['currentPagePemohon']['search']=false;                        
            $this->populateData();
		}
	}    
    public function renderCallback ($sender,$param) {
		$this->RepeaterS->render($param->NewWriter);	
	}	
	public function Page_Changed ($sender,$param) {
		$_SESSION['currentPagePemohon']['page_num']=$param->NewPageIndex;
		$this->populateData($_SESSION['currentPagePemohon']['search']);
	} 
    public function filterRecord ($sender,$param) {
		$_SESSION['currentPagePemohon']['search']=true;
        $this->populateData($_SESSION['currentPagePemohon']['search']);
	}
    public function filterUPTD ($sender,$param) {		
        if ($this->IsValid) {
            $_SESSION['currentPagePemohon']['iduptd']=$this->cmbUPTD->Text;
            $this->populateData($_SESSION['currentPagePemohon']['search']);
        }
	}
    private function populateData ($search=false) {    
        $iduptd=$_SESSION['currentPagePemohon']['iduptd'];
        $str = "SELECT RecNoPem,NmPem,KtpPem,AlmtPem,TelpPem,Foto,DateAdded FROM pemohon WHERE iduptd=$iduptd";
        if ($search) {                        
            $txtsearch=$this->txtKriteria->Text;
            switch ($this->cmbKriteria->Text) {
                case 'kode' :
                    $cluasa=" AND RecNoPem='$txtsearch'";
                    $jumlah_baris=$this->DB->getCountRowsOfTable ("pemohon WHERE iduptd=$iduptd $cluasa",'RecNoPem');
                    $str = "$str $cluasa";
                break;
                case 'nama_pemohon' :
                    $cluasa=" AND NmPem  LIKE '%$txtsearch%'";
                    $jumlah_baris=$this->DB->getCountRowsOfTable ("pemohon WHERE iduptd=$iduptd $cluasa",'RecNoPem');
                    $str = "$str $cluasa";
                break;
            }
        }else {                        
            $jumlah_baris=$this->DB->getCountRowsOfTable ("pemohon WHERE iduptd=$iduptd",'RecNoPem');			
        }
        $this->RepeaterS->CurrentPageIndex=$_SESSION['currentPagePemohon']['page_num'];		
		$this->RepeaterS->VirtualItemCount=$jumlah_baris;
		$currentPage=$this->RepeaterS->CurrentPageIndex;
		$offset=$currentPage*$this->RepeaterS->PageSize;		
		$itemcount=$this->RepeaterS->VirtualItemCount;
		$limit=$this->RepeaterS->PageSize;
		if (($offset+$limit)>$itemcount) {
			$limit=$itemcount-$offset;
		}
		if ($limit <= 0) {$offset=0;$limit=10;$_SESSION['currentPagePemohon']['page_num']=0;}
        $str = "$str ORDER BY DateAdded DESC LIMIT $offset,$limit";        
        $this->DB->setFieldTable(array('RecNoPem','NmPem','KtpPem','AlmtPem','TelpPem','Foto','DateAdded'));
		$r=$this->DB->getRecord($str,$offset+1);        
		$this->RepeaterS->DataSource=$r;
		$this->RepeaterS->dataBind();      		
    }
    public function checkId ($sender,$param) {
		$this->idProcess=$sender->getId()=='addKodeUPDT'?'add':'edit';
        $idpemohon=$param->Value;		
        if ($idpemohon != '') {
            try {   
                if ($this->hiddenidpemohon->Value!=$idpemohon) {                    
                    if ($this->DB->checkRecordIsExist('RecNoPem','pemohon',$idpemohon)) {                                
                        throw new Exception ("ID Pemohon ($idpemohon) sudah tidak tersedia silahkan ganti dengan yang lain.");		
                    }                               
                }                
            }catch (Exception $e) {
                $param->IsValid=false;
                $sender->ErrorMessage=$e->getMessage();
            }	
        }	
    }
    public function checkTypeFile ($sender,$param) {
        $this->idProcess=$sender->getId()=='addFileFoto'?'add':'edit';     
        if ($this->FileFoto->HasFile) {
            if ($this->FileFoto->FileType!='image/png' && $this->FileFoto->FileType!='image/jpeg')            
                $param->IsValid=false;
        }
    }
    public function processNextButton($sender,$param) {
        $this->idProcess='add';                    
		if ($param->CurrentStepIndex ==0) {            
            if ($this->rdAddStatusPemohonPerorangan->Checked) {
                $this->newpemohonwizard->ActiveStepIndex=2; 
            }
		}
	}
    public function addNewPemohonCompleted ($sender,$param) {
        $this->idProcess='add'; 
        $filename=substr(hash('sha512',rand()),0,8);
        $name=$this->FileFoto->FileName;
        $part=$this->setup->cleanFileNameString($name);                                
        $path=$this->setup->getSettingValue('dir_userimages')."/$filename-$part";                
        $this->path_userimages->Value=$path;
        $this->FileFoto->saveAs("./$path");      
    }    
    public function saveData($sender,$param) {		
        if ($this->Page->IsValid) {		                                                                
            $status_pemohon=$this->rdAddStatusPemohonPerorangan->Checked==true?'perorangan':'perusahaan';
            $kode_pemohon=$this->txtAddKodePemohon->Text;
            $nama_pemohon=$this->txtAddNamaPemohon->Text;
            $no_ktp_pemohon=$this->txtAddNoKTP->Text;
            $alamat_pemohon=$this->txtAddAlamatPemohon->Text;
            $npwp_pemohon=$this->txtAddNoNPWPPemohon->Text;
            $notelp_pemohon=$this->txtAddNoTelp->Text;     
            $iduptd=$_SESSION['currentPagePemohon']['iduptd'];
            $path_userimages=$this->path_userimages->Value;
            
            $str = "INSERT INTO pemohon (RecNoPem,NmPem,KtpPem,AlmtPem,TelpPem,NpwpPem,Foto,Status,iduptd,DateAdded,DateModified) VALUES ('$kode_pemohon','$nama_pemohon','$no_ktp_pemohon','$alamat_pemohon','$notelp_pemohon','$npwp_pemohon','$path_userimages','$status_pemohon','$iduptd',NOW(),NOW())";
            $this->DB->query('BEGIN');
            if ($this->DB->insertRecord($str)) {
                if ($status_pemohon=='perusahaan') {
                    $status_perusahaan=$this->rdAddStatusPerusahaanPusat->Checked==true?'pusat':'cabang';
                    $nama_perusahaan=$this->txtAddNamaPerusahaan->Text;
                    $no_akte=$this->txtAddNoAktePerusahaan->Text;
                    $tgl_akte=date('Y-m-d',$this->cmbAddTGLAktePendirian->TimeStamp);
                    $npwp=$this->txtAddNoNPWP->Text;
                    $alamat=$this->txtAddAlamatPerusahaan->Text;
                    $notelepon=$this->txtAddNoTelpPerusahaan->Text;
                    $nofax=$this->txtAddNoFaxPerusahaan->Text;
                    $alamatcabang=$this->txtAddAlamatCabang->Text; 
                    $iduptd=$_SESSION['currentPagePemohon']['iduptd'];
                    $nama_uptd=$this->Pengguna->getDataUser('nama_uptd');
                    $str = "INSERT INTO perusahaan (RecNoPem,RecStsCom,NmCom,NoAkte,TglAkte,NPWPCom,AlmtCom,TelCom,FaxComp,AlmtComCab,iduptd,nama_uptd) VALUES ($kode_pemohon,'$status_perusahaan','$nama_perusahaan','$no_akte','$tgl_akte','$npwp','$alamat','$notelepon','$nofax','$alamatcabang',$iduptd,'$nama_uptd')";
                    $this->DB->insertRecord($str);                             
                }
                $this->DB->query('COMMIT');            
                $this->redirect('dmaster.Pemohon',true);
            }else {
                $this->DB->query('ROLLBACK');
            }          
        }
	}
    public function editRecord ($sender,$param) {		
		$this->idProcess='edit';
		
	}
    public function updateData($sender,$param) {		
        if ($this->Page->IsValid) {		
            
        }
	}
    public function deleteRecord ($sender,$param) {
        $id=$this->getDataKeyField($sender,$this->RepeaterS);
        $this->DB->query('BEGIN');
        if ($this->DB->deleteRecord("pemohon WHERE RecNoPem='$id'")) {                                    
            $this->DB->query('COMMIT');
            $this->redirect('dmaster.Pemohon',true);					
        }else{
            $this->DB->query('ROLLBACK');
        }
    }
}
?>