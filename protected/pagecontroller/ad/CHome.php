<?php
prado::using ('Application.MainPageAD');
class CHome extends MainPageAD {
	public function onLoad($param) {		
		parent::onLoad($param);		          
        $this->showDashboard=true;
		if (!$this->IsPostBack&&!$this->IsCallBack) {              
            if (!isset($_SESSION['currentPageHome'])||$_SESSION['currentPageHome']['page_name']!='ad.Home') {                                
                
            }                
		}        
	}   
}
?>		