<?php
prado::using ('Application.MainPageSA');
class CPemohon extends MainPageSA {
	public function onLoad($param) {		
		parent::onLoad($param);		
        $this->showPerizinan=true;
        $this->showPemohon=true;        
		if (!$this->IsPostBack&&!$this->IsCallBack) {
            if (!isset($_SESSION['currentPagePemohon'])||$_SESSION['currentPagePemohon']['page_name']!='sa.perizinan.Pemohon') {
                $_SESSION['currentPagePemohon']=array('page_name'=>'sa.perizinan.Pemohon','page_num'=>0,'search'=>false);	                
            }        
            $_SESSION['currentPagePemohon']['search']=false;
//            $this->populateData();
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
    private function populateData ($search=false) {
        
    }
    public function checkId ($sender,$param) {
		$this->idProcess=$sender->getId()=='addKodeUPDT'?'add':'edit';
        $idpemohon=$param->Value;		
        if ($idpemohon != '') {
            try {   
                if ($this->hiddenidpemohon->Value!=$idpemohon) {                    
                    if ($this->DB->checkRecordIsExist('idupdt','updt',$idpemohon)) {                                
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
        if ($this->FileFoto->FileType!='image/png' && $this->FileFoto->FileType!='image/jpeg')            
            $param->IsValid=false;
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
        $RecNoPem=$this->DB->getMaxOfRecord('RecNoPem','pemohon')+1;
        $name=$this->FileFoto->FileName;
        $part=$this->setup->cleanFileNameString($name);                
        $path=$this->setup->getSettingValue('dir_temp')."/$RecNoPem-$part";                
        $this->path_temp_userimages->Value=$path;
        $path=$this->setup->getSettingValue('dir_userimages')."/$RecNoPem-$part";                
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
            $notelp_pemohon=$this->txtAddNoTelp->Text;
            $path_temp_userimages=$this->path_temp_userimages->Value;
            $path_userimages=$this->path_userimages->Value;
            
            $str = "INSERT INTO pemohon (RecNoPem,NmPem,KtpPem,AlmtPem,TelpPem,Foto,Status,DateAdded,DateModified) VALUES ('$kode_pemohon','$nama_pemohon','$no_ktp_pemohon','$alamat_pemohon','$notelp_pemohon','$path_userimages','$status_pemohon',NOW(),NOW())";
            $this->DB->insertRecord($str);
            
            $this->redirect('perizinan.Pemohon', true);
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
    }
}
		