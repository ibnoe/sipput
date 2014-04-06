<?php
prado::using ('Application.MainPageSA');
class CAddSIPI extends MainPageSA {
	public function onLoad($param) {		
		parent::onLoad($param);		
        $this->showPerizinan=true;
        $this->showPermohonanBaru=true;        
		if (!$this->IsPostBack&&!$this->IsCallBack) {
            $this->cmbAddUPTD->DataSource=$listuptd;
            $this->cmbAddUPTD->Text=$_SESSION['currentPagePemohon']['iduptd'];
            $this->cmbAddUPTD->DataBind(); 
		}
	}
}
		