<?php
prado::using ('Application.MainPageSA');
class CAreaPenangkapan extends MainPageSA {
	public function onLoad($param) {		
		parent::onLoad($param);		        
        $this->showDMaster=true;
        $this->showAreaPenangkapan=true;        
		if (!$this->IsPostBack&&!$this->IsCallBack) {
            if (!isset($_SESSION['currentPageAreaPenangkapan'])||$_SESSION['currentPageAreaPenangkapan']['page_name']!='sa.dmaster.AreaPenangkapan') {
                $_SESSION['currentPageAreaPenangkapan']=array('page_name'=>'sa.dmaster.AreaPenangkapan','page_num'=>0,'search'=>false);												
			}     
            $_SESSION['currentPageAreaPenangkapan']['search']=false;            
			$this->populateData ();			
		}
	}    
    public function renderCallback ($sender,$param) {
		$this->RepeaterS->render($param->NewWriter);	
	}	
	public function Page_Changed ($sender,$param) {
		$_SESSION['currentPageAreaPenangkapan']['page_num']=$param->NewPageIndex;
		$this->populateData();
	}    
    public function filterRecord ($sender,$param) {
		$_SESSION['currentPageAreaPenangkapan']['search']=true;
        $_SESSION['currentPageAreaPenangkapan']['page_num']=0;        
        $this->populateData($_SESSION['currentPageAreaPenangkapan']['search']);
	}
    private function populateData ($search=false) {                
        if ($search) {
            $str = "SELECT RecNoArea,AreaTangkap,KetArea,enabled FROM areapenangkapan";        
            $txtsearch=addslashes($this->txtKriteria->Text);
            switch ($this->cmbKriteria->Text) {                
                case 'namaarea' :
                    $cluasa=" WHERE AreaTangkap LIKE '%$txtsearch%'";
                    $jumlah_baris=$this->DB->getCountRowsOfTable ("areapenangkapan $cluasa",'RecNoArea');
                    $str = "$str $cluasa";
                break;                
            }
        }else {            
            $str = "SELECT RecNoArea,AreaTangkap,KetArea,enabled FROM areapenangkapan";        
            $jumlah_baris=$this->DB->getCountRowsOfTable ('areapenangkapan','RecNoArea');		
        }		
        $this->RepeaterS->CurrentPageIndex=$_SESSION['currentPageAreaPenangkapan']['page_num'];
		$this->RepeaterS->VirtualItemCount=$jumlah_baris;
		$currentPage=$this->RepeaterS->CurrentPageIndex;
		$offset=$currentPage*$this->RepeaterS->PageSize;		
		$itemcount=$this->RepeaterS->VirtualItemCount;
		$limit=$this->RepeaterS->PageSize;
		if (($offset+$limit)>$itemcount) {
			$limit=$itemcount-$offset;
		}
		if ($limit < 0) {$offset=0;$limit=10;$_SESSION['currentPageAreaPenangkapan']['page_num']=0;}
        $str = "$str ORDER BY AreaTangkap ASC LIMIT $offset,$limit";        
		$this->DB->setFieldTable(array('RecNoArea','AreaTangkap','KetArea','enabled'));
		$r=$this->DB->getRecord($str);                    
		$this->RepeaterS->DataSource=$r;
		$this->RepeaterS->dataBind();      		
    }    
    public function addProcess ($sender,$param) {
		$this->idProcess='add';                                   
    }        
    public function saveData($sender,$param) {		
        if ($this->Page->IsValid) {		
            $namaarea=addslashes($this->txtAddAreaTangkap->Text);                
            $ketarea=addslashes($this->txtAddKetArea->Text);            
            $str = "INSERT INTO areapenangkapan (RecNoArea,AreaTangkap,KetArea) VALUES (NULL,'$namaarea','$ketarea')";
            $this->DB->insertRecord($str);
            $this->redirect('dmaster.AreaPenangkapan',true);
        }
	}
    public function editRecord ($sender,$param) {		
		$this->idProcess='edit';
		$id=$this->getDataKeyField($sender,$this->RepeaterS);        
		$this->hiddenid->Value=$id;        
        $str = "SELECT RecNoArea,AreaTangkap,KetArea,enabled FROM areapenangkapan WHERE RecNoArea=$id";
        $this->DB->setFieldTable(array('RecNoArea','AreaTangkap','KetArea','enabled'));
        $r=$this->DB->getRecord($str);    
		$result = $r[1];        			        		                
        $this->txtEditAreaTangkap->Text=$result['AreaTangkap']; 
        $this->txtEditKetArea->Text=$result['KetArea']; 
        
	}
    public function updateData($sender,$param) {		
        if ($this->Page->IsValid) {		
            $id=$this->hiddenid->Value;            
            $namaarea=  addslashes($this->txtEditAreaTangkap->Text);                
            $ketarea=addslashes($this->txtEditKetArea->Text);            
            $str = "UPDATE areapenangkapan SET AreaTangkap='$namaarea',KetArea='$ketarea' WHERE RecNoArea=$id";
            $this->DB->updateRecord($str);           
            $this->redirect('dmaster.AreaPenangkapan',true);
        }
	}
    public function deleteRecord($sender,$param) {
        $id=$this->getDataKeyField($sender,$this->RepeaterS);
        $this->DB->deleteRecord("areapenangkapan WHERE RecNoArea=$id");
        $this->redirect('dmaster.AreaPenangkapan',true);
    }
}
		