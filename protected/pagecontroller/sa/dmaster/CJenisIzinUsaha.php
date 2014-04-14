<?php
prado::using ('Application.MainPageSA');
class CJenisIzinUsaha extends MainPageSA {
	public function onLoad($param) {		
		parent::onLoad($param);		
        $this->showDMaster=true;
        $this->showJenisIzinUsaha=true;        
		if (!$this->IsPostBack&&!$this->IsCallBack) {
            if (!isset($_SESSION['currentPageJenisIzinUsaha'])||$_SESSION['currentPageJenisIzinUsaha']['page_name']!='sa.dmaster.JenisIzinUsaha') {
                $_SESSION['currentPageJenisIzinUsaha']=array('page_name'=>'sa.dmaster.JenisIzinUsaha','page_num'=>0,'search'=>false);	                
            }         
            $this->populateData();
		}
	}    
    public function renderCallback ($sender,$param) {
		$this->RepeaterS->render($param->NewWriter);	
	}	
    private function populateData ($search=false) {
        $str = "SELECT RecNoIzin,InsIzin,NmIzin FROM jenisizinusaha";                
		$this->DB->setFieldTable(array('RecNoIzin','InsIzin','NmIzin'));
		$r=$this->DB->getRecord($str);        
		$this->RepeaterS->DataSource=$r;
		$this->RepeaterS->dataBind();      		
    }   
    public function editRecord ($sender,$param) {		
		$this->idProcess='edit';
		$id=$this->getDataKeyField($sender,$this->RepeaterS);        
		$this->hiddenid->Value=$id;        
        $str = "SELECT InsIzin,NmIzin FROM jenisizinusaha WHERE RecNoIzin=$id";
        $this->DB->setFieldTable(array('InsIzin','NmIzin'));
        $r=$this->DB->getRecord($str);    
		$result = $r[1];        			        		                
        $this->txtEditInsIzinUsaha->Text=$result['InsIzin']; 
        $this->txtEditNamaIzin->Text=$result['NmIzin'];        
	}
    public function updateData($sender,$param) {		
        if ($this->Page->IsValid) {		
            $id=$this->hiddenid->Value;            
            $insizin=  strtoupper($this->txtEditInsIzinUsaha->Text);            
            $namaizin=strtoupper($this->txtEditNamaIzin->Text);            
            $str = "UPDATE jenisizinusaha SET InsIzin='$insizin',NmIzin='$namaizin' WHERE RecNoIzin=$id";
            $this->DB->updateRecord($str);           
            $this->redirect('dmaster.JenisIzinUsaha',true);
        }
	}
}
		