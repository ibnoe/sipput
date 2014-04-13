<?php
prado::using ('Application.MainPageSA');
class CBidangIzinUsaha extends MainPageSA {
	public function onLoad($param) {		
		parent::onLoad($param);		        
        $this->showDMaster=true;
        $this->showBidangIzinUsaha=true;
        $this->createObj('DMaster');
		if (!$this->IsPostBack&&!$this->IsCallBack) {
            if (!isset($_SESSION['currentPageBidangIzinUsaha'])||$_SESSION['currentPageBidangIzinUsaha']['page_name']!='sa.dmaster.BidangIzinUsaha') {
                $_SESSION['currentPageBidangIzinUsaha']=array('page_name'=>'sa.dmaster.BidangIzinUsaha','page_num'=>0,'jenisizin'=>'none','search'=>false);												
			}     
            $_SESSION['currentPageBidangIzinUsaha']['search']=false;
            $listjenisizin=$this->DMaster->getListJenisIzinUsaha ();
            $listjenisizin['none']='All';
            $this->cmbJenisIzin->DataSource=$listjenisizin;
            $this->cmbJenisIzin->Text=$_SESSION['currentPageBidangIzinUsaha']['jenisizin'];
            $this->cmbJenisIzin->DataBind();
			$this->populateData ();			
		}
	}    
    public function renderCallback ($sender,$param) {
		$this->RepeaterS->render($param->NewWriter);	
	}	
	public function Page_Changed ($sender,$param) {
		$_SESSION['currentPageBidangIzinUsaha']['page_num']=$param->NewPageIndex;
		$this->populateData();
	} 
    public function changeJenisIzin ($sender,$param) {
        $_SESSION['currentPageBidangIzinUsaha']['page_num']=0;
        $_SESSION['currentPageBidangIzinUsaha']['search']=false;
		$_SESSION['currentPageBidangIzinUsaha']['jenisizin']=$this->cmbJenisIzin->Text;
		$this->populateData();
	}
    public function filterRecord ($sender,$param) {
		$_SESSION['currentPageBidangIzinUsaha']['search']=true;
        $_SESSION['currentPageBidangIzinUsaha']['page_num']=0;        
        $this->populateData($_SESSION['currentPageBidangIzinUsaha']['search']);
	}
    private function populateData ($search=false) {                
        if ($search) {
            $str = "SELECT idbidangizin,InsIzin,InsBidang,NmBidang,bin.enabled FROM bidangizinusaha bin,jenisizinusaha jiu WHERE bin.RecNoIzin=jiu.RecNoIzin";        
            $txtsearch=$this->txtKriteria->Text;
            switch ($this->cmbKriteria->Text) {
                case 'inisialbidang' :
                    $cluasa=" AND InsBidang LIKE '%$txtsearch%'";
                    $jumlah_baris=$this->DB->getCountRowsOfTable ("bidangizinusaha bin,jenisizinusaha jiu WHERE bin.RecNoIzin=jiu.RecNoIzin $cluasa",'idbidangizin');
                    $str = "$str $cluasa";
                break;  
                case 'namabidang' :
                    $cluasa=" AND NmBidang LIKE '%$txtsearch%'";
                    $jumlah_baris=$this->DB->getCountRowsOfTable ("bidangizinusaha bin,jenisizinusaha jiu WHERE bin.RecNoIzin=jiu.RecNoIzin $cluasa",'idbidangizin');
                    $str = "$str $cluasa";
                break;                
            }
        }else {
            $recnoizin=$_SESSION['currentPageBidangIzinUsaha']['jenisizin'];
            $str_jenisizin=$recnoizin=='none'?'':" AND bin.RecNoIzin='$recnoizin'";
            $str = "SELECT idbidangizin,InsIzin,InsBidang,NmBidang,bin.enabled FROM bidangizinusaha bin,jenisizinusaha jiu WHERE bin.RecNoIzin=jiu.RecNoIzin $str_jenisizin";        
            $jumlah_baris=$this->DB->getCountRowsOfTable ("bidangizinusaha bin,jenisizinusaha jiu WHERE bin.RecNoIzin=jiu.RecNoIzin $str_jenisizin",'idbidangizin');		
        }		
        $this->RepeaterS->CurrentPageIndex=$_SESSION['currentPageBidangIzinUsaha']['page_num'];
		$this->RepeaterS->VirtualItemCount=$jumlah_baris;
		$currentPage=$this->RepeaterS->CurrentPageIndex;
		$offset=$currentPage*$this->RepeaterS->PageSize;		
		$itemcount=$this->RepeaterS->VirtualItemCount;
		$limit=$this->RepeaterS->PageSize;
		if (($offset+$limit)>$itemcount) {
			$limit=$itemcount-$offset;
		}
		if ($limit < 0) {$offset=0;$limit=10;$_SESSION['currentPageBidangIzinUsaha']['page_num']=0;}
        $str = "$str LIMIT $offset,$limit";        
		$this->DB->setFieldTable(array('idbidangizin','InsIzin','InsBidang','NmBidang','enabled'));
		$r=$this->DB->getRecord($str);                    
		$this->RepeaterS->DataSource=$r;
		$this->RepeaterS->dataBind();      		
    }    
    public function addProcess ($sender,$param) {
		$this->idProcess='add';                        
        $listjenisizin=$this->DMaster->getListJenisIzinUsaha ();
        $this->cmbAddJenisIzin->DataSource=$listjenisizin;
        $this->cmbAddJenisIzin->Text=$_SESSION['currentPageBidangIzinUsaha']['jenisizin'];
        $this->cmbAddJenisIzin->DataBind();        
    }        
    public function saveData($sender,$param) {		
        if ($this->Page->IsValid) {		
            $jenisizin=$this->cmbAddJenisIzin->Text;            
            $inisialbidang=$this->txtAddInsBidang->Text;            
            $namabidang=$this->txtAddNamaBidang->Text;            
            $str = "INSERT INTO bidangizinusaha (idbidangizin,RecNoIzin,InsBidang,NmBidang) VALUES (NULL,'$jenisizin','$inisialbidang','$namabidang')";
            $this->DB->insertRecord($str);
            $this->redirect('dmaster.BidangIzinUsaha',true);
        }
	}
    public function editRecord ($sender,$param) {		
		$this->idProcess='edit';
		$id=$this->getDataKeyField($sender,$this->RepeaterS);        
		$this->hiddenid->Value=$id;        
        $str = "SELECT idbidangizin,RecNoIzin,InsBidang,NmBidang,enabled FROM bidangizinusaha WHERE idbidangizin=$id";
        $this->DB->setFieldTable(array('idbidangizin','RecNoIzin','InsBidang','NmBidang','enabled'));
        $r=$this->DB->getRecord($str);    
		$result = $r[1];        			
        $listjenisizin=$this->DMaster->removeIdFromArray($this->DMaster->getListJenisIzinUsaha (),'none');
		$this->cmbEditJenisIzin->DataSource=$listjenisizin;
        $this->cmbEditJenisIzin->Text=$result['RecNoIzin'];
        $this->cmbEditJenisIzin->DataBind();             
        $this->txtEditInsBidang->Text=$result['InsBidang'];            
        $this->txtEditNamaBidang->Text=$result['NmBidang']; 
        
	}
    public function updateData($sender,$param) {		
        if ($this->Page->IsValid) {		
            $id=$this->hiddenid->Value;
            $jenisizin=$this->cmbEditJenisIzin->Text;            
            $inisialbidang=$this->txtEditInsBidang->Text;            
            $namabidang=$this->txtEditNamaBidang->Text;            
            $str = "UPDATE bidangizinusaha SET RecNoIzin='$jenisizin',InsBidang='$inisialbidang',NmBidang='$namabidang' WHERE idbidangizin=$id";
            $this->DB->updateRecord($str);           
            $this->redirect('dmaster.BidangIzinUsaha',true);
        }
	}
    public function deleteRecord($sender,$param) {
        $id=$this->getDataKeyField($sender,$this->RepeaterS);
        $this->DB->deleteRecord("bidangizinusaha WHERE idbidangizin=$id");
        $this->redirect('dmaster.BidangIzinUsaha',true);
    }
}
		