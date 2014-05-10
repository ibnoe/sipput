<?php
prado::using ('Application.MainPageSA');
class CJenisAlat extends MainPageSA {
	public function onLoad($param) {		
		parent::onLoad($param);		        
        $this->showDMaster=true;
        $this->showJenisAlat=true;
        $this->createObj('DMaster');
		if (!$this->IsPostBack&&!$this->IsCallBack) {
            if (!isset($_SESSION['currentPageJenisAlat'])||$_SESSION['currentPageJenisAlat']['page_name']!='sa.dmaster.JenisAlat') {
                $_SESSION['currentPageJenisAlat']=array('page_name'=>'sa.dmaster.JenisAlat','page_num'=>0,'kodejenis'=>'none','search'=>false);												
			}     
            $_SESSION['currentPageJenisAlat']['search']=false;
            $listkodejenis=$this->DMaster->getKodeJenisAlat();
            $listkodejenis['none']='All';            
            $this->cmbJenisAlat->DataSource=$listkodejenis;
            $this->cmbJenisAlat->Text=$_SESSION['currentPageJenisAlat']['kodejenis'];
            $this->cmbJenisAlat->DataBind();
			$this->populateData ();			
		}
	}    
    public function renderCallback ($sender,$param) {
		$this->RepeaterS->render($param->NewWriter);	
	}	
	public function Page_Changed ($sender,$param) {
		$_SESSION['currentPageJenisAlat']['page_num']=$param->NewPageIndex;
		$this->populateData();
	} 
    public function changeJenisAlat ($sender,$param) {
        $_SESSION['currentPageJenisAlat']['page_num']=0;
        $_SESSION['currentPageJenisAlat']['search']=false;
		$_SESSION['currentPageJenisAlat']['kodejenis']=$this->cmbJenisAlat->Text;
		$this->populateData();
	}
    public function filterRecord ($sender,$param) {
		$_SESSION['currentPageJenisAlat']['search']=true;
        $_SESSION['currentPageJenisAlat']['page_num']=0;        
        $this->populateData($_SESSION['currentPageJenisAlat']['search']);
	}
    private function populateData ($search=false) {                
        if ($search) {
            $str = "SELECT RecNoJns,KdJns,NmJenisAlat,enabled FROM jenisalat";        
            $txtsearch=$this->txtKriteria->Text;
            switch ($this->cmbKriteria->Text) {                
                case 'namajenis' :
                    $cluasa=" AND NmJenisAlat LIKE '%$txtsearch%'";
                    $jumlah_baris=$this->DB->getCountRowsOfTable ("jenisalat WHERE $cluasa",'RecNoJns');
                    $str = "$str $cluasa";
                break;                
            }
        }else {
            $kodejenis=$_SESSION['currentPageJenisAlat']['kodejenis'];
            $str_kodejenis=$kodejenis=='none'?'':"WHERE KdJns='$kodejenis'";
            $str = "SELECT RecNoJns,KdJns,NmJenisAlat,enabled FROM jenisalat $str_kodejenis";        
            $jumlah_baris=$this->DB->getCountRowsOfTable ("jenisalat $str_kodejenis",'RecNoJns');		
        }		
        $this->RepeaterS->CurrentPageIndex=$_SESSION['currentPageJenisAlat']['page_num'];
		$this->RepeaterS->VirtualItemCount=$jumlah_baris;
		$currentPage=$this->RepeaterS->CurrentPageIndex;
		$offset=$currentPage*$this->RepeaterS->PageSize;		
		$itemcount=$this->RepeaterS->VirtualItemCount;
		$limit=$this->RepeaterS->PageSize;
		if (($offset+$limit)>$itemcount) {
			$limit=$itemcount-$offset;
		}
		if ($limit < 0) {$offset=0;$limit=10;$_SESSION['currentPageJenisAlat']['page_num']=0;}
        $str = "$str ORDER BY KdJns ASC LIMIT $offset,$limit";        
		$this->DB->setFieldTable(array('RecNoJns','KdJns','NmJenisAlat','enabled'));
		$r=$this->DB->getRecord($str);                    
		$this->RepeaterS->DataSource=$r;
		$this->RepeaterS->dataBind();      		
    }    
    public function addProcess ($sender,$param) {
		$this->idProcess='add';                
        $this->cmbAddKodeJenis->DataSource=$this->DMaster->getKodeJenisAlat();  
        $this->cmbAddKodeJenis->Text=$_SESSION['currentPageJenisAlat']['kodejenis'];
        $this->cmbAddKodeJenis->DataBind();
    }        
    public function saveData($sender,$param) {		
        if ($this->Page->IsValid) {		
            $kodejenis=$this->cmbAddKodeJenis->Text;                
            $namajenisalat=$this->txtAddNamaJenisAlat->Text;            
            $str = "INSERT INTO jenisalat (RecNoJns,KdJns,NmJenisAlat) VALUES (NULL,'$kodejenis','$namajenisalat')";
            $this->DB->insertRecord($str);
            $this->redirect('dmaster.JenisAlat',true);
        }
	}
    public function editRecord ($sender,$param) {		
		$this->idProcess='edit';
		$id=$this->getDataKeyField($sender,$this->RepeaterS);        
		$this->hiddenid->Value=$id;        
        $str = "SELECT RecNoJns,KdJns,NmJenisAlat,enabled FROM jenisalat WHERE RecNoJns=$id";
        $this->DB->setFieldTable(array('RecNoJns','KdJns','NmJenisAlat','enabled'));
        $r=$this->DB->getRecord($str);    
		$result = $r[1];        		
        $this->cmbEditKodeJenis->DataSource=$this->DMaster->removeIdFromArray($this->DMaster->getKodeJenisAlat(),'none');                
        $this->cmbEditKodeJenis->Text=$r[1]['KdJns'];
        $this->cmbEditKodeJenis->DataBind();
        $this->txtEditNamaJenisAlat->Text=$result['NmJenisAlat']; 
        
	}
    public function updateData($sender,$param) {		
        if ($this->Page->IsValid) {		
            $id=$this->hiddenid->Value;   
            $kodejenis=$this->cmbEditKodeJenis->Text;
            $namajenisalat=$this->txtEditNamaJenisAlat->Text;                        
            $str = "UPDATE jenisalat SET KdJns='$kodejenis',NmJenisAlat='$namajenisalat' WHERE RecNoJns=$id";
            $this->DB->updateRecord($str);           
            $this->redirect('dmaster.JenisAlat',true);
        }
	}
    public function deleteRecord($sender,$param) {
        $id=$this->getDataKeyField($sender,$this->RepeaterS);
        $this->DB->deleteRecord("jenisalat WHERE RecNoJns=$id");
        $this->redirect('dmaster.JenisAlat',true);
    }
}
		