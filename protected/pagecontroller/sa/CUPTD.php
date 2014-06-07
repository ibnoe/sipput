<?php
prado::using ('Application.MainPageSA');
class CUPTD extends MainPageSA {
	public function onLoad($param) {		
		parent::onLoad($param);		        
        $this->showUPTD=true;        
		if (!$this->IsPostBack&&!$this->IsCallBack) {
            if (!isset($_SESSION['currentPageUPTD'])||$_SESSION['currentPageUPTD']['page_name']!='UPTD') {
                $_SESSION['currentPageUPTD']=array('page_name'=>'UPTD','page_num'=>0,'search'=>false);	                
            }        
            $_SESSION['currentPageUPTD']['search']=false;
            $this->populateData();
		}
	}    
    public function renderCallback ($sender,$param) {
		$this->RepeaterS->render($param->NewWriter);	
	}	
	public function Page_Changed ($sender,$param) {
		$_SESSION['currentPageUPTD']['page_num']=$param->NewPageIndex;
		$this->populateData($_SESSION['currentPageUPTD']['search']);
	} 
    public function filterRecord ($sender,$param) {
		$_SESSION['currentPageUPTD']['search']=true;
        $this->populateData($_SESSION['currentPageUPTD']['search']);
	}
    private function populateData ($search=false) {
        $str = "SELECT iduptd,nama_uptd,alamat_uptd,enabled FROM uptd";
        if ($search) {
            $txtsearch=$this->txtKriteria->Text;
            switch ($this->cmbKriteria->Text) {
                case 'kode' :
                    $cluasa="WHERE iduptd='$txtsearch'";
                    $jumlah_baris=$this->DB->getCountRowsOfTable ("uptd $cluasa",'iduptd');
                    $str = "$str $cluasa";
                break;
                case 'nama_uptd' :
                    $cluasa="WHERE nama_uptd LIKE '%$txtsearch%'";
                    $jumlah_baris=$this->DB->getCountRowsOfTable ("uptd $cluasa",'iduptd');
                    $str = "$str $cluasa";
                break;
            }
        }else {
            $jumlah_baris=$this->DB->getCountRowsOfTable ('uptd','iduptd');			
        }
        $this->RepeaterS->CurrentPageIndex=$_SESSION['currentPageUPTD']['page_num'];		
		$this->RepeaterS->VirtualItemCount=$jumlah_baris;
		$currentPage=$this->RepeaterS->CurrentPageIndex;
		$offset=$currentPage*$this->RepeaterS->PageSize;		
		$itemcount=$this->RepeaterS->VirtualItemCount;
		$limit=$this->RepeaterS->PageSize;
		if (($offset+$limit)>$itemcount) {
			$limit=$itemcount-$offset;
		}
		if ($limit <= 0) {$offset=0;$limit=10;$_SESSION['currentPageUPTD']['page_num']=0;}
        $str = "$str LIMIT $offset,$limit";        
		$this->DB->setFieldTable(array('iduptd','nama_uptd','alamat_uptd','enabled'));
		$r=$this->DB->getRecord($str,$offset+1);        
		$this->RepeaterS->DataSource=$r;
		$this->RepeaterS->dataBind();      		
    }
    public function checkId ($sender,$param) {
		$this->idProcess=$sender->getId()=='addKodeUPTD'?'add':'edit';
        $kode_uptd=$param->Value;		
        if ($kode_uptd != '') {
            try {   
                if ($this->hiddenkode_uptd->Value!=$kode_uptd) {                    
                    if ($this->DB->checkRecordIsExist('iduptd','uptd',$kode_uptd)) {                                
                        throw new Exception ("Kode UPTD ($kode_uptd) sudah tidak tersedia silahkan ganti dengan yang lain.");		
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
            $kodeuptd=$this->txtAddKodeUPTD->Text;
            $nama_uptd =  ucwords(addslashes($this->txtAddNamaUPTD->Text));
            $alamat_uptd =  addslashes($this->txtAddAlamatUPTD->Text);
            $enabled=$this->cmbAddStatus->Text;
            $str = "INSERT INTO uptd (iduptd,nama_uptd,alamat_uptd,enabled) VALUES ('$kodeuptd','$nama_uptd','$alamat_uptd',$enabled)";
            $this->DB->insertRecord($str);
            if ($this->Application->Cache) {
                $this->Application->Cache->delete('listuptd');
            }
            $this->redirect('UPTD',true);
        }
	}
    public function editRecord ($sender,$param) {		
		$this->idProcess='edit';
		$idunikerja=$this->getDataKeyField($sender,$this->RepeaterS);
		$this->hiddenkode_uptd->Value=$idunikerja;
        $str = "SELECT iduptd,nama_uptd,alamat_uptd,enabled FROM uptd WHERE iduptd='$idunikerja'";
        $this->DB->setFieldTable(array('iduptd','nama_uptd','alamat_uptd','enabled'));
        $r=$this->DB->getRecord($str);    
		$result = $r[1];        				
		$this->txtEditKodeUPTD->Text=$result['iduptd'];		
		$this->txtEditNamaUPTD->Text=$result['nama_uptd'];		        
		$this->txtEditAlamatUPTD->Text=$result['alamat_uptd'];		
        $this->cmbEditStatus->Text=$result['enabled'];		
	}
    public function updateData($sender,$param) {		
        if ($this->Page->IsValid) {		
            $iduptd=$this->hiddenkode_uptd->Value;
            $kodeuptd=$this->txtEditKodeUPTD->Text;
            $nama_uptd =  ucwords(addslashes($this->txtEditNamaUPTD->Text));
            $alamat_uptd =  addslashes($this->txtEditAlamatUPTD->Text);
            $enabled=$this->cmbEditStatus->Text;
            $str = "UPDATE uptd SET iduptd='$kodeuptd',nama_uptd='$nama_uptd',alamat_uptd='$alamat_uptd',enabled=$enabled WHERE iduptd=$iduptd";
            $this->DB->updateRecord($str);
            if ($this->Application->Cache) {
                $this->Application->Cache->delete('listuptd');
            }
            $this->redirect('UPTD',true);
        }
	}
    public function deleteRecord ($sender,$param) {
        $iduptd=$this->getDataKeyField($sender,$this->RepeaterS);
        $this->DB->query('BEGIN');
        if ($this->DB->deleteRecord("uptd WHERE iduptd='$iduptd'")) {    
            $this->DB->updateRecord("UPDATE pemohon SET iduptd=0 WHERE iduptd=$iduptd");
            $this->DB->updateRecord("UPDATE user SET iduptd=0,active=0 WHERE iduptd=$iduptd");
            if ($this->Application->Cache) {
                $this->Application->Cache->delete('listuptd');
            }
            $this->DB->query('COMMIT');
            $this->redirect('UPTD',true);					
        }else{
            $this->DB->query('ROLLBACK');
        }
    }
}
		