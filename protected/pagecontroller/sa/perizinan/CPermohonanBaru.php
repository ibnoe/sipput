<?php
prado::using ('Application.MainPageSA');
class CPermohonanBaru extends MainPageSA {
	public function onLoad($param) {		
		parent::onLoad($param);		
        $this->showPerizinan=true;
        $this->showPermohonanBaru=true;        
		if (!$this->IsPostBack&&!$this->IsCallBack) {
            if (!isset($_SESSION['currentPageUPDT'])||$_SESSION['currentPageUPDT']['page_name']!='sa.dmaster.UPDT') {
                $_SESSION['currentPageUPDT']=array('page_name'=>'sa.dmaster.UPDT','page_num'=>0,'search'=>false);	                
            }        
            $_SESSION['currentPageUPDT']['search']=false;
            $this->populateData();
		}
	}    
    public function renderCallback ($sender,$param) {
		$this->RepeaterS->render($param->NewWriter);	
	}	
	public function Page_Changed ($sender,$param) {
		$_SESSION['currentPageUPDT']['page_num']=$param->NewPageIndex;
		$this->populateData($_SESSION['currentPageUPDT']['search']);
	} 
    public function filterRecord ($sender,$param) {
		$_SESSION['currentPageUPDT']['search']=true;
        $this->populateData($_SESSION['currentPageUPDT']['search']);
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
        $this->RepeaterS->CurrentPageIndex=$_SESSION['currentPageUPDT']['page_num'];		
		$this->RepeaterS->VirtualItemCount=$jumlah_baris;
		$currentPage=$this->RepeaterS->CurrentPageIndex;
		$offset=$currentPage*$this->RepeaterS->PageSize;		
		$itemcount=$this->RepeaterS->VirtualItemCount;
		$limit=$this->RepeaterS->PageSize;
		if (($offset+$limit)>$itemcount) {
			$limit=$itemcount-$offset;
		}
		if ($limit <= 0) {$offset=0;$limit=10;$_SESSION['currentPageUPDT']['page_num']=0;}
        $str = "$str LIMIT $offset,$limit";        
		$this->DB->setFieldTable(array('idupdt','nama_updt','alamat_updt','enabled'));
		$r=$this->DB->getRecord($str,$offset+1);        
		$this->RepeaterS->DataSource=$r;
		$this->RepeaterS->dataBind();      		
    }
    public function checkId ($sender,$param) {
		$this->idProcess=$sender->getId()=='addKodeUPDT'?'add':'edit';
        $kode_updt=$param->Value;		
        if ($kode_updt != '') {
            try {   
                if ($this->hiddenkode_updt->Value!=$kode_updt) {                    
                    if ($this->DB->checkRecordIsExist('idupdt','updt',$kode_updt)) {                                
                        throw new Exception ("Kode UPDT ($kode_updt) sudah tidak tersedia silahkan ganti dengan yang lain.");		
                    }                               
                }                
            }catch (Exception $e) {
                $param->IsValid=false;
                $sender->ErrorMessage=$e->getMessage();
            }	
        }	
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
		