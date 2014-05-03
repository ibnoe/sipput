<?php

class MainPageSA extends MainPage {  
     /**     
     * show page lokasi [dmaster]
     */
    public $showLokasi=false;
    /**     
     * show page lokasi Negara [dmaster]
     */
    public $showNegara=false;
    /**     
     * show page lokasi DT I [dmaster]
     */
    public $showDT1=false;
    /**     
     * show page lokasi DT II [dmaster]
     */
    public $showDT2=false;
     /**     
     * show page Satuan[dmaster]
     */
    public $showSatuan=false;        
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
     * show page Bahan Alat [dmaster]
     */
    public $showBahanAlat=false; 
    /**     
     * show page lokasi usaha [dmaster]
     */
    public $showLokasiUsaha=false; 
    /**     
     * show page area penangkapan [dmaster]
     */
    public $showAreaPenangkapan=false; 
    /**     
     * show page UPTD  [dmaster]
     */
    public $showUPTD=false;       
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