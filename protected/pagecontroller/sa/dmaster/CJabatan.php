<?php
prado::using ('Application.MainPageSA');
class CJabatan extends MainPageSA {
	public function onLoad($param) {		
		parent::onLoad($param);		        
        $this->showDMaster=true;
        $this->showJabatan=true;        
		if (!$this->IsPostBack&&!$this->IsCallBack) {
            if (!isset($_SESSION['currentPageJabatan'])||$_SESSION['currentPageJabatan']['page_name']!='sa.dmaster.Jabatan') {
                $_SESSION['currentPageJabatan']=array('page_name'=>'sa.dmaster.Jabatan','page_num'=>0,'search'=>false);												
			}     
            $_SESSION['currentPageJabatan']['search']=false;            
			$this->populateData ();			
		}
	}    
    public function renderCallback ($sender,$param) {
		$this->RepeaterS->render($param->NewWriter);	
	}	
	public function Page_Changed ($sender,$param) {
		$_SESSION['currentPageJabatan']['page_num']=$param->NewPageIndex;
		$this->populateData();
	}    
    public function filterRecord ($sender,$param) {
		$_SESSION['currentPageJabatan']['search']=true;
        $_SESSION['currentPageJabatan']['page_num']=0;        
        $this->populateData($_SESSION['currentPageJabatan']['search']);
	}
    private function populateData ($search=false) {                
        if ($search) {
            $str = "SELECT RecNoJab,NmJabatan,enabled FROM jabatan";                
            $txtsearch=addslashes($this->txtKriteria->Text);
            switch ($this->cmbKriteria->Text) {                
                case 'namabahan' :
                    $cluasa=" WHERE NmJabatan LIKE '%$txtsearch%'";
                    $jumlah_baris=$this->DB->getCountRowsOfTable ("jabatan $cluasa",'RecNoJab');
                    $str = "$str $cluasa";
                break;                
            }
        }else {            
            $str = "SELECT RecNoJab,NmJabatan,enabled FROM jabatan";        
            $jumlah_baris=$this->DB->getCountRowsOfTable ('jabatan','RecNoJab');		
        }		
        $this->RepeaterS->CurrentPageIndex=$_SESSION['currentPageJabatan']['page_num'];
		$this->RepeaterS->VirtualItemCount=$jumlah_baris;
		$currentPage=$this->RepeaterS->CurrentPageIndex;
		$offset=$currentPage*$this->RepeaterS->PageSize;		
		$itemcount=$this->RepeaterS->VirtualItemCount;
		$limit=$this->RepeaterS->PageSize;
		if (($offset+$limit)>$itemcount) {
			$limit=$itemcount-$offset;
		}
		if ($limit < 0) {$offset=0;$limit=10;$_SESSION['currentPageJabatan']['page_num']=0;}
        $str = "$str ORDER BY NmJabatan ASC LIMIT $offset,$limit";        
		$this->DB->setFieldTable(array('RecNoJab','NmJabatan','enabled'));
		$r=$this->DB->getRecord($str);                    
		$this->RepeaterS->DataSource=$r;
		$this->RepeaterS->dataBind();      		
    }    
    public function addProcess ($sender,$param) {
		$this->idProcess='add';                                   
    }        
    public function saveData($sender,$param) {		
        if ($this->Page->IsValid) {		
            $namabahan=addslashes($this->txtAddJabatan->Text);                            
            $str = "INSERT INTO jabatan (RecNoJab,NmJabatan) VALUES (NULL,'$namabahan')";
            $this->DB->insertRecord($str);
            $this->redirect('dmaster.Jabatan',true);
        }
	}
    public function editRecord ($sender,$param) {		
		$this->idProcess='edit';
		$id=$this->getDataKeyField($sender,$this->RepeaterS);        
		$this->hiddenid->Value=$id;        
        $str = "SELECT RecNoJab,NmJabatan,enabled FROM jabatan WHERE RecNoJab=$id";
        $this->DB->setFieldTable(array('RecNoJab','NmJabatan','enabled'));
        $r=$this->DB->getRecord($str);    
		$result = $r[1];        			        		                
        $this->txtEditJabatan->Text=$result['NmJabatan'];         
        
	}
    public function updateData($sender,$param) {		
        if ($this->Page->IsValid) {		
            $id=$this->hiddenid->Value;            
            $namabahan=addslashes($this->txtEditJabatan->Text);              
            $str = "UPDATE jabatan SET NmJabatan='$namabahan' WHERE RecNoJab=$id";
            $this->DB->updateRecord($str);           
            $this->redirect('dmaster.Jabatan',true);
        }
	}
    public function deleteRecord($sender,$param) {
        $id=$this->getDataKeyField($sender,$this->RepeaterS);
        $this->DB->deleteRecord("jabatan WHERE RecNoJab=$id");
        $this->redirect('dmaster.Jabatan',true);
    }
}
		