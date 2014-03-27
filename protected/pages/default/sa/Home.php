<?php
prado::using ('Application.pagecontroller.sa.CHome');
class Home extends CHome {
	public function onLoad($param) {		
		parent::onLoad($param);		            
		if (!$this->IsPostBack&&!$this->IsCallBack) {              
            if (!isset($_SESSION['currentPageHome'])||$_SESSION['currentPageHome']['page_name']!='sa.Home') {                                
            }                
		}        
	}   
}
?>		