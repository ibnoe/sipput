<?php
prado::using ('Application.MainPageSA');
class CHome extends MainPageSA {
	public function onLoad($param) {		
		parent::onLoad($param);		            
        $this->showDashboard=true;
		if (!$this->IsPostBack&&!$this->IsCallBack) {              
            if (!isset($_SESSION['currentPageHome'])||$_SESSION['currentPageHome']['page_name']!='sa.Home') {                                
                
            }                
		}        
	}   
}
?>		