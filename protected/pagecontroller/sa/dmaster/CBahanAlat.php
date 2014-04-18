<?php
prado::using ('Application.MainPageSA');
class CBahanAlat extends MainPageSA {
	public function onLoad($param) {		
		parent::onLoad($param);		        
        $this->showDMaster=true;
        $this->showBahanAlat=true;        
		if (!$this->IsPostBack&&!$this->IsCallBack) {
            if (!isset($_SESSION['currentPageBahanAlat'])||$_SESSION['currentPageBahanAlat']['page_name']!='sa.dmaster.BahanAlat') {
                $_SESSION['currentPageBahanAlat']=array('page_name'=>'sa.dmaster.BahanAlat','page_num'=>0,'search'=>false);												
			}     
            $_SESSION['currentPageBahanAlat']['search']=false;            
			$this->populateData ();			
		}
	}    
    public function renderCallback ($sender,$param) {
		$this->RepeaterS->render($param->NewWriter);	
	}	
	public function Page_Changed ($sender,$param) {
		$_SESSION['currentPageBahanAlat']['page_num']=$param->NewPageIndex;
		$this->populateData();
	}    
    public function filterRecord ($sender,$param) {
		$_SESSION['currentPageBahanAlat']['search']=true;
        $_SESSION['currentPageBahanAlat']['page_num']=0;        
        $this->populateData($_SESSION['currentPageBahanAlat']['search']);
	}
    private function populateData ($search=false) {                
        if ($search) {
            $str = "SELECT RecNoBhn,NmBahan,enabled FROM bahanjenisalat";                
            $txtsearch=addslashes($this->txtKriteria->Text);
            switch ($this->cmbKriteria->Text) {                
                case 'namabahan' :
                    $cluasa=" WHERE NmBahan LIKE '%$txtsearch%'";
                    $jumlah_baris=$this->DB->getCountRowsOfTable ("bahanjenisalat $cluasa",'RecNoBhn');
                    $str = "$str $cluasa";
                break;                
            }
        }else {            
            $str = "SELECT RecNoBhn,NmBahan,enabled FROM bahanjenisalat";        
            $jumlah_baris=$this->DB->getCountRowsOfTable ('bahanjenisalat','RecNoBhn');		
        }		
        $this->RepeaterS->CurrentPageIndex=$_SESSION['currentPageBahanAlat']['page_num'];
		$this->RepeaterS->VirtualItemCount=$jumlah_baris;
		$currentPage=$this->RepeaterS->CurrentPageIndex;
		$offset=$currentPage*$this->RepeaterS->PageSize;		
		$itemcount=$this->RepeaterS->VirtualItemCount;
		$limit=$this->RepeaterS->PageSize;
		if (($offset+$limit)>$itemcount) {
			$limit=$itemcount-$offset;
		}
		if ($limit < 0) {$offset=0;$limit=10;$_SESSION['currentPageBahanAlat']['page_num']=0;}
        $str = "$str ORDER BY NmBahan ASC LIMIT $offset,$limit";        
		$this->DB->setFieldTable(array('RecNoBhn','NmBahan','enabled'));
		$r=$this->DB->getRecord($str);                    
		$this->RepeaterS->DataSource=$r;
		$this->RepeaterS->dataBind();      		
    }    
    public function addProcess ($sender,$param) {
		$this->idProcess='add';                                   
    }        
    public function saveData($sender,$param) {		
        if ($this->Page->IsValid) {		
            $namabahan=addslashes($this->txtAddBahanAlat->Text);                            
            $str = "INSERT INTO bahanjenisalat (RecNoBhn,NmBahan) VALUES (NULL,'$namabahan')";
            $this->DB->insertRecord($str);
            $this->redirect('dmaster.BahanAlat',true);
        }
	}
    public function editRecord ($sender,$param) {		
		$this->idProcess='edit';
		$id=$this->getDataKeyField($sender,$this->RepeaterS);        
		$this->hiddenid->Value=$id;        
        $str = "SELECT RecNoBhn,NmBahan,enabled FROM bahanjenisalat WHERE RecNoBhn=$id";
        $this->DB->setFieldTable(array('RecNoBhn','NmBahan','enabled'));
        $r=$this->DB->getRecord($str);    
		$result = $r[1];        			        		                
        $this->txtEditBahanAlat->Text=$result['NmBahan'];         
        
	}
    public function updateData($sender,$param) {		
        if ($this->Page->IsValid) {		
            $id=$this->hiddenid->Value;            
            $namabahan=addslashes($this->txtEditBahanAlat->Text);              
            $str = "UPDATE bahanjenisalat SET NmBahan='$namabahan' WHERE RecNoBhn=$id";
            $this->DB->updateRecord($str);           
            $this->redirect('dmaster.BahanAlat',true);
        }
	}
    public function deleteRecord($sender,$param) {
        $id=$this->getDataKeyField($sender,$this->RepeaterS);
        $this->DB->deleteRecord("bahanjenisalat WHERE RecNoBhn=$id");
        $this->redirect('dmaster.BahanAlat',true);
    }
}
		