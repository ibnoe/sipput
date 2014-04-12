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
}
		