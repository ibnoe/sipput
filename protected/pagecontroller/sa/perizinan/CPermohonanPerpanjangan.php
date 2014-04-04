<?php
prado::using ('Application.MainPageSA');
class CPermohonanPerpanjangan extends MainPageSA {
	public function onLoad($param) {		
		parent::onLoad($param);		
        $this->showPerizinan=true;
        $this->showPermohonanPerpanjangan=true;        
		if (!$this->IsPostBack&&!$this->IsCallBack) {
            
		}
	}
}
		