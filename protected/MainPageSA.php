<?php

class MainPageSA extends MainPage {  
    /**     
     * show page UPTD  [dmaster]
     */
    public $showUPTD=false;    
    /**     
     * show page Jenis Usaha [dmaster]
     */
    public $showJenisIzinUsaha=false;    
    /**     
     * show page Bidang Usaha [dmaster]
     */
    public $showBidangIzinUsaha=false;
    /**     
     * show page Jenis Alat [dmaster]
     */
    public $showJenisAlat=false; 
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