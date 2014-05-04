<?php
prado::using ('Application.MainPageSA');
class CPelabuhan extends MainPageSA {
	public function onLoad($param) {		
		parent::onLoad($param);		        
        $this->showLokasi=true;
        $this->showPelabuhan=true;
        $this->createObj('DMaster');
		if (!$this->IsPostBack&&!$this->IsCallBack) {
            if (!isset($_SESSION['CurrentPagePelabuhan'])||$_SESSION['CurrentPagePelabuhan']['page_name']!='sa.lokasi.Pelabuhan') {
                $_SESSION['CurrentPagePelabuhan']=array('page_name'=>'sa.lokasi.Pelabuhan','page_num'=>0,'jenispelabuhan'=>'none','search'=>false);												
			}     
            $_SESSION['CurrentPagePelabuhan']['search']=false;
            $listjenispelabuhan=$this->DMaster->getJenisPelabuhan ();
            $listjenispelabuhan['none']='All';
            $this->cmbJenisPelabuhan->DataSource=$listjenispelabuhan;
            $this->cmbJenisPelabuhan->Text=$_SESSION['CurrentPagePelabuhan']['jenispelabuhan'];
            $this->cmbJenisPelabuhan->DataBind();
			$this->populateData ();			
		}
	}    
    public function renderCallback ($sender,$param) {
		$this->RepeaterS->render($param->NewWriter);	
	}	
	public function Page_Changed ($sender,$param) {
		$_SESSION['CurrentPagePelabuhan']['page_num']=$param->NewPageIndex;
		$this->populateData();
	} 
    public function changeJenisPelabuhan ($sender,$param) {
        $_SESSION['CurrentPagePelabuhan']['page_num']=0;
        $_SESSION['CurrentPagePelabuhan']['search']=false;
		$_SESSION['CurrentPagePelabuhan']['jenispelabuhan']=$this->cmbJenisPelabuhan->Text;
		$this->populateData();
	}
    public function filterRecord ($sender,$param) {
		$_SESSION['CurrentPagePelabuhan']['search']=true;
        $_SESSION['CurrentPagePelabuhan']['page_num']=0;        
        $this->populateData($_SESSION['CurrentPagePelabuhan']['search']);
	}
    private function populateData ($search=false) {                
        if ($search) {
            $str = "SELECT RecNoPlbh,JenPlbh,NmPlbh,SkalaPlbh,enabled FROM pelabuhan";        
            $txtsearch=$this->txtKriteria->Text;
            switch ($this->cmbKriteria->Text) {                
                case 'namapelabuhan' :
                    $cluasa=" WHERE NmPlbh LIKE '%$txtsearch%'";
                    $jumlah_baris=$this->DB->getCountRowsOfTable ("pelabuhan$cluasa",'RecNoPlbh');
                    $str = "$str $cluasa";
                break;                
            }
        }else {
            $recnoizin=$_SESSION['CurrentPagePelabuhan']['jenispelabuhan'];
            $str_jenispelabuhan=$recnoizin=='none'?'':" WHERE JenPlbh='$recnoizin'";
            $str = "SELECT RecNoPlbh,JenPlbh,NmPlbh,SkalaPlbh,enabled FROM pelabuhan$str_jenispelabuhan";        
            $jumlah_baris=$this->DB->getCountRowsOfTable ("pelabuhan$str_jenispelabuhan",'RecNoPlbh');		
        }		
        $this->RepeaterS->CurrentPageIndex=$_SESSION['CurrentPagePelabuhan']['page_num'];
		$this->RepeaterS->VirtualItemCount=$jumlah_baris;
		$currentPage=$this->RepeaterS->CurrentPageIndex;
		$offset=$currentPage*$this->RepeaterS->PageSize;		
		$itemcount=$this->RepeaterS->VirtualItemCount;
		$limit=$this->RepeaterS->PageSize;
		if (($offset+$limit)>$itemcount) {
			$limit=$itemcount-$offset;
		}
		if ($limit < 0) {$offset=0;$limit=10;$_SESSION['CurrentPagePelabuhan']['page_num']=0;}
        $str = "$str ORDER BY JenPlbh LIMIT $offset,$limit";        
		$this->DB->setFieldTable(array('RecNoPlbh','JenPlbh','NmPlbh','SkalaPlbh','enabled'));
		$r=$this->DB->getRecord($str);                    
		$this->RepeaterS->DataSource=$r;
		$this->RepeaterS->dataBind();      		
    }    
    public function addProcess ($sender,$param) {
		$this->idProcess='add';                        
        $listjenispelabuhan=$this->DMaster->getJenisPelabuhan ();
        $listjenispelabuhan['none']='Pilih jenis pelabuhan';
        $this->cmbAddJenisPelabuhan->DataSource=$listjenispelabuhan;
        $this->cmbAddJenisPelabuhan->Text=$_SESSION['CurrentPagePelabuhan']['jenispelabuhan'];
        $this->cmbAddJenisPelabuhan->DataBind();      
        
        $listskalapelabuhan=$this->DMaster->getSkalaPelabuhan ();
        $listskalapelabuhan['none']='Pilih skala pelabuhan';
        $this->cmbAddSkalaPelabuhan->DataSource=$listskalapelabuhan;
        $this->cmbAddSkalaPelabuhan->Text=$_SESSION['CurrentPagePelabuhan']['skalapelabuhan'];
        $this->cmbAddSkalaPelabuhan->DataBind();
        
    }        
    public function saveData($sender,$param) {		
        if ($this->Page->IsValid) {		
            $jenispelabuhan=$this->cmbAddJenisPelabuhan->Text;            
            $namapelabuhan=$this->txtAddNamaPelabuhan->Text;            
            $skalapelabuhan=$this->cmbAddSkalaPelabuhan->Text;                        
            $str = "INSERT INTO pelabuhan (RecNoPlbh,JenPlbh,NmPlbh,SkalaPlbh) VALUES (NULL,'$jenispelabuhan','$namapelabuhan','$skalapelabuhan')";
            $this->DB->insertRecord($str);
            $this->redirect('lokasi.Pelabuhan',true);
        }
	}
    public function editRecord ($sender,$param) {		
		$this->idProcess='edit';
		$id=$this->getDataKeyField($sender,$this->RepeaterS);        
		$this->hiddenid->Value=$id;     
        $str = "SELECT RecNoPlbh,JenPlbh,NmPlbh,SkalaPlbh,enabled FROM pelabuhan WHERE RecNoPlbh=$id";        
        $this->DB->setFieldTable(array('RecNoPlbh','JenPlbh','NmPlbh','SkalaPlbh','enabled'));
        $r=$this->DB->getRecord($str);    
		$result = $r[1];        			
        $listjenispelabuhan=$this->DMaster->removeIdFromArray($this->DMaster->getJenisPelabuhan (),'none');
		$this->cmbEditJenisPelabuhan->DataSource=$listjenispelabuhan;
        $this->cmbEditJenisPelabuhan->Text=$result['JenPlbh'];
        $this->cmbEditJenisPelabuhan->DataBind();                 
        
        $this->txtEditNamaPelabuhan->Text=$result['NmPlbh']; 
        
        $listskalapelabuhan=$this->DMaster->removeIdFromArray($this->DMaster->getSkalaPelabuhan (),'none');
        $listskalapelabuhan['none']='Pilih skala pelabuhan';
        $this->cmbEditSkalaPelabuhan->DataSource=$listskalapelabuhan;
        $this->cmbEditSkalaPelabuhan->Text=$result['SkalaPlbh'];
        $this->cmbEditSkalaPelabuhan->DataBind();        

        
        
	}
    public function updateData($sender,$param) {		
        if ($this->Page->IsValid) {		
            $id=$this->hiddenid->Value;
            $jenispelabuhan=$this->cmbEditJenisPelabuhan->Text;            
            $namapelabuhan=$this->txtEditNamaPelabuhan->Text;            
            $skalapelabuhan=$this->cmbEditSkalaPelabuhan->Text;             
            $str = "UPDATE pelabuhan SET JenPlbh='$jenispelabuhan',NmPlbh='$namapelabuhan',SkalaPlbh='$skalapelabuhan' WHERE RecNoPlbh=$id";
            $this->DB->updateRecord($str);           
            $this->redirect('lokasi.Pelabuhan',true);
        }
	}
    public function deleteRecord($sender,$param) {
        $id=$this->getDataKeyField($sender,$this->RepeaterS);
        $this->DB->deleteRecord("pelabuhan WHERE RecNoPlbh=$id");
        $this->redirect('lokasi.Pelabuhan',true);
    }
}
		