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
                $_SESSION['currentPageJenisAlat']=array('page_name'=>'sa.dmaster.JenisAlat','page_num'=>0,'jenisizin'=>'none','search'=>false);												
			}     
            $_SESSION['currentPageJenisAlat']['search']=false;
            $listjenisizin=$this->DMaster->getListJenisIzinUsaha ();
            $listjenisizin['none']='All';
            $this->cmbJenisIzin->DataSource=$listjenisizin;
            $this->cmbJenisIzin->Text=$_SESSION['currentPageJenisAlat']['jenisizin'];
            $this->cmbJenisIzin->DataBind();
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
    public function changeJenisIzin ($sender,$param) {
        $_SESSION['currentPageJenisAlat']['page_num']=0;
        $_SESSION['currentPageJenisAlat']['search']=false;
		$_SESSION['currentPageJenisAlat']['jenisizin']=$this->cmbJenisIzin->Text;
		$this->populateData();
	}
    public function filterRecord ($sender,$param) {
		$_SESSION['currentPageJenisAlat']['search']=true;
        $_SESSION['currentPageJenisAlat']['page_num']=0;        
        $this->populateData($_SESSION['currentPageJenisAlat']['search']);
	}
    private function populateData ($search=false) {                
        if ($search) {
            $str = "SELECT RecNoJns,InsIzin,NmJenisAlat,ja.enabled FROM jenisalat ja,jenisizinusaha jiu WHERE ja.RecNoIzin=jiu.RecNoIzin";        
            $txtsearch=$this->txtKriteria->Text;
            switch ($this->cmbKriteria->Text) {
                case 'inisialbidang' :
                    $cluasa=" AND InsBidang LIKE '%$txtsearch%'";
                    $jumlah_baris=$this->DB->getCountRowsOfTable ("jenisalat ja,jenisizinusaha jiu WHERE ja.RecNoIzin=jiu.RecNoIzin $cluasa",'RecNoJns');
                    $str = "$str $cluasa";
                break;  
                case 'namabidang' :
                    $cluasa=" AND NmJenisAlat LIKE '%$txtsearch%'";
                    $jumlah_baris=$this->DB->getCountRowsOfTable ("jenisalat ja,jenisizinusaha jiu WHERE ja.RecNoIzin=jiu.RecNoIzin $cluasa",'RecNoJns');
                    $str = "$str $cluasa";
                break;                
            }
        }else {
            $recnoizin=$_SESSION['currentPageJenisAlat']['jenisizin'];
            $str_jenisizin=$recnoizin=='none'?'':" AND ja.RecNoIzin='$recnoizin'";
            $str = "SELECT RecNoJns,InsIzin,NmJenisAlat,ja.enabled FROM jenisalat ja,jenisizinusaha jiu WHERE ja.RecNoIzin=jiu.RecNoIzin $str_jenisizin";        
            $jumlah_baris=$this->DB->getCountRowsOfTable ("jenisalat ja,jenisizinusaha jiu WHERE ja.RecNoIzin=jiu.RecNoIzin $str_jenisizin",'RecNoJns');		
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
        $str = "$str ORDER BY ja.RecNoIzin ASC LIMIT $offset,$limit";        
		$this->DB->setFieldTable(array('RecNoJns','InsIzin','InsBidang','NmJenisAlat','enabled'));
		$r=$this->DB->getRecord($str);                    
		$this->RepeaterS->DataSource=$r;
		$this->RepeaterS->dataBind();      		
    }    
    public function addProcess ($sender,$param) {
		$this->idProcess='add';                        
        $listjenisizin=$this->DMaster->getListJenisIzinUsaha ();
        $this->cmbAddJenisIzin->DataSource=$listjenisizin;
        $this->cmbAddJenisIzin->Text=$_SESSION['currentPageJenisAlat']['jenisizin'];
        $this->cmbAddJenisIzin->DataBind();        
    }        
    public function saveData($sender,$param) {		
        if ($this->Page->IsValid) {		
            $jenisizin=$this->cmbAddJenisIzin->Text;                
            $namajenisalat=$this->txtAddNamaJenisAlat->Text;            
            $str = "INSERT INTO jenisalat (RecNoJns,RecNoIzin,NmJenisAlat) VALUES (NULL,'$jenisizin','$namajenisalat')";
            $this->DB->insertRecord($str);
            $this->redirect('dmaster.JenisAlat',true);
        }
	}
    public function editRecord ($sender,$param) {		
		$this->idProcess='edit';
		$id=$this->getDataKeyField($sender,$this->RepeaterS);        
		$this->hiddenid->Value=$id;        
        $str = "SELECT RecNoJns,RecNoIzin,NmJenisAlat,enabled FROM jenisalat WHERE RecNoJns=$id";
        $this->DB->setFieldTable(array('RecNoJns','RecNoIzin','InsBidang','NmJenisAlat','enabled'));
        $r=$this->DB->getRecord($str);    
		$result = $r[1];        			        		                
        $this->txtEditNamaJenisAlat->Text=$result['NmJenisAlat']; 
        
	}
    public function updateData($sender,$param) {		
        if ($this->Page->IsValid) {		
            $id=$this->hiddenid->Value;            
            $namajenisalat=$this->txtEditNamaJenisAlat->Text;            
            $str = "UPDATE jenisalat SET NmJenisAlat='$namajenisalat' WHERE RecNoJns=$id";
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
		