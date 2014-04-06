<?php
prado::using ('Application.MainPageSA');
class CAddSIPI extends MainPageSA {
    
	public function onLoad($param) {		
		parent::onLoad($param);		
        $this->showPerizinan=true;
        $this->showPermohonanBaru=true; 
        $this->createObj('DMaster');
		if (!$this->IsPostBack&&!$this->IsCallBack) {
            if (!isset($_SESSION['currentPageAddSIPI'])||$_SESSION['currentPageAddSIPI']['page_name']!='ad.dmaster.Pemohon') {
                $_SESSION['currentPageAddSIPI']=array('page_name'=>'ad.dmaster.Pemohon','page_num'=>0,'search'=>false,'iduptd'=>$this->Pengguna->getDataUser('iduptd'));	                
            } 
            $listpemohon=$this->DMaster->getListPemohon($_SESSION['currentPageAddSIPI']['iduptd']);
            $this->cmbAddPemohon->DataSource=$listpemohon;            
            $this->cmbAddPemohon->DataBind();
		}
	}
    public function processNextButton($sender,$param) {        
		if ($param->CurrentStepIndex ==0) {            
            $RecNoPem=$this->cmbAddPemohon->Text;
		}
	}
}
		