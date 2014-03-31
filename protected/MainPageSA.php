<?php

class MainPageSA extends MainPage {  
    /**     
     * show page UPDT  [dmaster]
     */
    public $showUPDT=false;    
    /**     
     * show page cache [setting]
     */
    public $showUser=false;
    /**     
     * show page cache [setting]
     */
    public $showCache=false;
	public function onLoad ($param) {		
		parent::onLoad($param);				
        if (!$this->IsPostBack&&!$this->IsCallBack) {	
                                                              
        }
	}   
}
?>