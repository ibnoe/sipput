<?php
prado::using ('Application.pagecontroller.sa.CHome');
class Home extends CHome {
	public function onLoad($param) {		
		parent::onLoad($param);		            		        
	}   
    public function openModal ($sender,$param) {
        $this->modalPrintOut->show();
    }
}
?>		