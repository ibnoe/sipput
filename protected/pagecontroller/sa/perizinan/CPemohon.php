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
    private function populateData ($search=false) {
        $str = "SELECT idupdt,nama_updt,alamat_updt,enabled FROM updt";
        if ($search) {
            $txtsearch=$this->txtKriteria->Text;
            switch ($this->cmbKriteria->Text) {
                case 'kode' :
                    $cluasa="WHERE idupdt='$txtsearch'";
                    $jumlah_baris=$this->DB->getCountRowsOfTable ("updt $cluasa",'idupdt');
                    $str = "$str $cluasa";
                break;
                case 'nama_updt' :
                    $cluasa="WHERE nama_updt LIKE '%$txtsearch%'";
                    $jumlah_baris=$this->DB->getCountRowsOfTable ("updt $cluasa",'idupdt');
                    $str = "$str $cluasa";
                break;
            }
        }else {
            $jumlah_baris=$this->DB->getCountRowsOfTable ('updt','idupdt');			
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
        $str = "$str LIMIT $offset,$limit";        
		$this->DB->setFieldTable(array('idupdt','nama_updt','alamat_updt','enabled'));
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
    public function processNextButton($sender,$param) {
        $this->idProcess='add';            
        
		if ($param->CurrentStepIndex ==0) {       
            
		}elseif ($param->CurrentStepIndex ==1) {
            
        }elseif ($param->CurrentStepIndex ==2) {    
            
        }elseif ($param->CurrentStepIndex ==3) {         
            $this->imgAddPegawai->ImageUrl=$this->setup->getUrlPhotoPegawai().'no_photos.jpg';
            $this->imgAddPegawai->ImageUrl=$this->setup->getUrlPhotoPegawai().'no_photos.jpg';        
        }
	}
    public function addNewPegawaiCompleted ($sender,$param) {
        $this->idProcess='add';                
    }
    public function saveData($sender,$param) {		
        if ($this->Page->IsValid) {		
            $kodeupdt=$this->txtAddKodeUPDT->Text;
            $nama_updt =  ucwords(addslashes($this->txtAddNamaUPDT->Text));
            $alamat_updt =  addslashes($this->txtAddAlamatUPDT->Text);
            $enabled=$this->cmbAddStatus->Text;
            $str = "INSERT INTO updt (idupdt,nama_updt,alamat_updt,enabled) VALUES ('$kodeupdt','$nama_updt','$alamat_updt',$enabled)";
            $this->DB->insertRecord($str);
            if ($this->Application->Cache) {
                $dataitem=$this->Pengguna->getList('updt WHERE enabled=1',array('idupdt','nama_updt'),'nama_updt',null,1);
                $dataitem['none']='-------------- Seluruh UPDT --------------';    
                $this->Application->Cache->set('listupdt',$dataitem);
            }
            $this->redirect('sa.dmaster.UPDT');
        }
	}
    public function editRecord ($sender,$param) {		
		$this->idProcess='edit';
		$idunikerja=$this->getDataKeyField($sender,$this->RepeaterS);
		$this->hiddenkode_updt->Value=$idunikerja;
        $str = "SELECT idupdt,nama_updt,alamat_updt,enabled FROM updt WHERE idupdt='$idunikerja'";
        $this->DB->setFieldTable(array('idupdt','nama_updt','alamat_updt','enabled'));
        $r=$this->DB->getRecord($str);    
		$result = $r[1];        				
		$this->txtEditKodeUPDT->Text=$result['idupdt'];		
		$this->txtEditNamaUPDT->Text=$result['nama_updt'];		        
		$this->txtEditAlamatUPDT->Text=$result['alamat_updt'];		
        $this->cmbEditStatus->Text=$result['enabled'];		
	}
    public function updateData($sender,$param) {		
        if ($this->Page->IsValid) {		
            $idupdt=$this->hiddenkode_updt->Value;
            $kodeupdt=$this->txtEditKodeUPDT->Text;
            $nama_updt =  ucwords(addslashes($this->txtEditNamaUPDT->Text));
            $alamat_updt =  addslashes($this->txtEditAlamatUPDT->Text);
            $enabled=$this->cmbEditStatus->Text;
            $str = "UPDATE updt SET idupdt='$kodeupdt',nama_updt='$nama_updt',alamat_updt='$alamat_updt',enabled=$enabled WHERE idupdt=$idupdt";
            $this->DB->updateRecord($str);
            if ($this->Application->Cache) {
                $dataitem=$this->Pengguna->getList('updt WHERE enabled=1',array('idupdt','nama_updt'),'nama_updt',null,1);
                $dataitem['none']='-------------- Seluruh UPDT Kerja --------------';    
                $this->Application->Cache->set('listupdt',$dataitem);
            }
            $this->redirect('sa.dmaster.UPDT');
        }
	}
    public function deleteRecord ($sender,$param) {
        $idupdt=$this->getDataKeyField($sender,$this->RepeaterS);
        $this->DB->query('BEGIN');
        if ($this->DB->deleteRecord("updt WHERE idupdt='$idupdt'")) {                        
            if ($this->Application->Cache) {
                $dataitem=$this->Pengguna->getList('updt WHERE enabled=1',array('idupdt','nama_updt'),'nama_updt',null,1);
                $dataitem['none']='-------------- Seluruh UPDT Kerja --------------';    
                $this->Application->Cache->set('listupdt',$dataitem);
            }
            $this->DB->query('COMMIT');
            $this->redirect('sa.dmaster.UPDT');					
        }else{
            $this->DB->query('ROLLBACK');
        }
    }
}
		