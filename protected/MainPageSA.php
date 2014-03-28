<?php

class MainPageSA extends MainPage {  
    /**     
     * show page cache
     */
    public $showUser=false;
    /**     
     * show page cache
     */
    public $showCache=false;
	public function onLoad ($param) {		
		parent::onLoad($param);				
        if (!$this->IsPostBack&&!$this->IsCallBack) {	
                                                              
        }
	}   
}
?>