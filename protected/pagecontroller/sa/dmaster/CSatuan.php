<?php
prado::using ('Application.MainPageSA');
class CSatuan extends MainPageSA {
	public function onLoad($param) {		
		parent::onLoad($param);		        
        $this->showDMaster=true;
        $this->showSatuan=true;        
		if (!$this->IsPostBack&&!$this->IsCallBack) {
            if (!isset($_SESSION['currentPageSatuan'])||$_SESSION['currentPageSatuan']['page_name']!='sa.dmaster.Satuan') {
                $_SESSION['currentPageSatuan']=array('page_name'=>'sa.dmaster.Satuan','page_num'=>0,'search'=>false);												
			}     
            $_SESSION['currentPageSatuan']['search']=false;            
			$this->populateData ();			
		}
	}    
    public function renderCallback ($sender,$param) {
		$this->RepeaterS->render($param->NewWriter);	
	}	
	public function Page_Changed ($sender,$param) {
		$_SESSION['currentPageSatuan']['page_num']=$param->NewPageIndex;
		$this->populateData();
	}    
    public function filterRecord ($sender,$param) {
		$_SESSION['currentPageSatuan']['search']=true;
        $_SESSION['currentPageSatuan']['page_num']=0;        
        $this->populateData($_SESSION['currentPageSatuan']['search']);
	}
    private function populateData ($search=false) {                
        if ($search) {
            $str = "SELECT RecNoSatuan,NmSatuan,enabled FROM satuan";                
            $txtsearch=addslashes($this->txtKriteria->Text);
            switch ($this->cmbKriteria->Text) {                
                case 'namasatuan' :
                    $cluasa=" WHERE NmSatuan LIKE '%$txtsearch%'";
                    $jumlah_baris=$this->DB->getCountRowsOfTable ("satuan $cluasa",'RecNoSatuan');
                    $str = "$str $cluasa";
                break;                
            }
        }else {            
            $str = "SELECT RecNoSatuan,NmSatuan,enabled FROM satuan";        
            $jumlah_baris=$this->DB->getCountRowsOfTable ('satuan','RecNoSatuan');		
        }		
        $this->RepeaterS->CurrentPageIndex=$_SESSION['currentPageSatuan']['page_num'];
		$this->RepeaterS->VirtualItemCount=$jumlah_baris;
		$currentPage=$this->RepeaterS->CurrentPageIndex;
		$offset=$currentPage*$this->RepeaterS->PageSize;		
		$itemcount=$this->RepeaterS->VirtualItemCount;
		$limit=$this->RepeaterS->PageSize;
		if (($offset+$limit)>$itemcount) {
			$limit=$itemcount-$offset;
		}
		if ($limit < 0) {$offset=0;$limit=10;$_SESSION['currentPageSatuan']['page_num']=0;}
        $str = "$str ORDER BY NmSatuan ASC LIMIT $offset,$limit";        
		$this->DB->setFieldTable(array('RecNoSatuan','NmSatuan','enabled'));
		$r=$this->DB->getRecord($str);                    
		$this->RepeaterS->DataSource=$r;
		$this->RepeaterS->dataBind();      		
    }    
    public function addProcess ($sender,$param) {
		$this->idProcess='add';                                   
    }        
    public function saveData($sender,$param) {		
        if ($this->Page->IsValid) {		
            $namabahan=addslashes($this->txtAddSatuan->Text);                            
            $str = "INSERT INTO satuan (RecNoSatuan,NmSatuan) VALUES (NULL,'$namabahan')";
            $this->DB->insertRecord($str);
            $this->redirect('dmaster.Satuan',true);
        }
	}
    public function editRecord ($sender,$param) {		
		$this->idProcess='edit';
		$id=$this->getDataKeyField($sender,$this->RepeaterS);        
		$this->hiddenid->Value=$id;        
        $str = "SELECT RecNoSatuan,NmSatuan,enabled FROM satuan WHERE RecNoSatuan=$id";
        $this->DB->setFieldTable(array('RecNoSatuan','NmSatuan','enabled'));
        $r=$this->DB->getRecord($str);    
		$result = $r[1];        			        		                
        $this->txtEditSatuan->Text=$result['NmSatuan'];         
        
	}
    public function updateData($sender,$param) {		
        if ($this->Page->IsValid) {		
            $id=$this->hiddenid->Value;            
            $namabahan=addslashes($this->txtEditSatuan->Text);              
            $str = "UPDATE satuan SET NmSatuan='$namabahan' WHERE RecNoSatuan=$id";
            $this->DB->updateRecord($str);           
            $this->redirect('dmaster.Satuan',true);
        }
	}
    public function deleteRecord($sender,$param) {
        $id=$this->getDataKeyField($sender,$this->RepeaterS);
        $this->DB->deleteRecord("satuan WHERE RecNoSatuan=$id");
        $this->redirect('dmaster.Satuan',true);
    }
}
		