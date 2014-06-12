<?php
prado::using ('Application.MainPageSA');
class CLokasiUsaha extends MainPageSA {
	public function onLoad($param) {		
		parent::onLoad($param);		        
        $this->showLokasi=true;
        $this->showLokasiUsaha=true;        
		if (!$this->IsPostBack&&!$this->IsCallBack) {
            if (!isset($_SESSION['currentPageLokasiUsaha'])||$_SESSION['currentPageLokasiUsaha']['page_name']!='sa.dmaster.LokasiUsaha') {
                $_SESSION['currentPageLokasiUsaha']=array('page_name'=>'sa.dmaster.LokasiUsaha','page_num'=>0,'search'=>false);												
			}     
            $_SESSION['currentPageLokasiUsaha']['search']=false;            
			$this->populateData ();			
		}
	}    
    public function renderCallback ($sender,$param) {
		$this->RepeaterS->render($param->NewWriter);	
	}	
	public function Page_Changed ($sender,$param) {
		$_SESSION['currentPageLokasiUsaha']['page_num']=$param->NewPageIndex;
		$this->populateData();
	}    
    public function filterRecord ($sender,$param) {
		$_SESSION['currentPageLokasiUsaha']['search']=true;
        $_SESSION['currentPageLokasiUsaha']['page_num']=0;        
        $this->populateData($_SESSION['currentPageLokasiUsaha']['search']);
	}
    private function populateData ($search=false) {                
        if ($search) {
            $str = "SELECT RecNoLokasi,NamaLokasi,KetLokasi,enabled FROM lokasiusaha";        
            $txtsearch=addslashes($this->txtKriteria->Text);
            switch ($this->cmbKriteria->Text) {                
                case 'namalokasi' :
                    $cluasa=" WHERE NamaLokasi LIKE '%$txtsearch%'";
                    $jumlah_baris=$this->DB->getCountRowsOfTable ("lokasiusaha $cluasa",'RecNoLokasi');
                    $str = "$str $cluasa";
                break;                
            }
        }else {            
            $str = "SELECT RecNoLokasi,NamaLokasi,KetLokasi,enabled FROM lokasiusaha";        
            $jumlah_baris=$this->DB->getCountRowsOfTable ('lokasiusaha','RecNoLokasi');		
        }		
        $this->RepeaterS->CurrentPageIndex=$_SESSION['currentPageLokasiUsaha']['page_num'];
		$this->RepeaterS->VirtualItemCount=$jumlah_baris;
		$currentPage=$this->RepeaterS->CurrentPageIndex;
		$offset=$currentPage*$this->RepeaterS->PageSize;		
		$itemcount=$this->RepeaterS->VirtualItemCount;
		$limit=$this->RepeaterS->PageSize;
		if (($offset+$limit)>$itemcount) {
			$limit=$itemcount-$offset;
		}
		if ($limit < 0) {$offset=0;$limit=10;$_SESSION['currentPageLokasiUsaha']['page_num']=0;}
        $str = "$str ORDER BY NamaLokasi ASC LIMIT $offset,$limit";        
		$this->DB->setFieldTable(array('RecNoLokasi','NamaLokasi','KetLokasi','enabled'));
		$r=$this->DB->getRecord($str);                    
		$this->RepeaterS->DataSource=$r;
		$this->RepeaterS->dataBind();      		
    }    
    public function addProcess ($sender,$param) {
		$this->idProcess='add';                                   
    }        
    public function saveData($sender,$param) {		
        if ($this->Page->IsValid) {		
            $namalokasi=addslashes($this->txtAddNamaLokasi->Text);                
            $ketlokasi=addslashes($this->txtAddKetLokasi->Text);            
            $str = "INSERT INTO lokasiusaha (RecNoLokasi,NamaLokasi,KetLokasi) VALUES (NULL,'$namalokasi','$ketlokasi')";
            $this->DB->insertRecord($str);
            $this->redirect('dmaster.LokasiUsaha',true);
        }
	}
    public function editRecord ($sender,$param) {		
		$this->idProcess='edit';
		$id=$this->getDataKeyField($sender,$this->RepeaterS);        
		$this->hiddenid->Value=$id;        
        $str = "SELECT RecNoLokasi,NamaLokasi,KetLokasi,enabled FROM lokasiusaha WHERE RecNoLokasi=$id";
        $this->DB->setFieldTable(array('RecNoLokasi','NamaLokasi','KetLokasi','enabled'));
        $r=$this->DB->getRecord($str);    
		$result = $r[1];        			        		                
        $this->txtEditNamaLokasi->Text=$result['NamaLokasi']; 
        $this->txtEditKetLokasi->Text=$result['KetLokasi']; 
        
	}
    public function updateData($sender,$param) {		
        if ($this->Page->IsValid) {		
            $id=$this->hiddenid->Value;            
            $namalokasi=  addslashes($this->txtEditNamaLokasi->Text);                
            $ketlokasi=addslashes($this->txtEditKetLokasi->Text);            
            $str = "UPDATE lokasiusaha SET NamaLokasi='$namalokasi',KetLokasi='$ketlokasi' WHERE RecNoLokasi=$id";
            $this->DB->updateRecord($str);           
            $this->redirect('dmaster.LokasiUsaha',true);
        }
	}
    public function deleteRecord($sender,$param) {
        $id=$this->getDataKeyField($sender,$this->RepeaterS);
        $this->DB->deleteRecord("lokasiusaha WHERE RecNoLokasi=$id");
        $this->redirect('dmaster.LokasiUsaha',true);
    }
}
		