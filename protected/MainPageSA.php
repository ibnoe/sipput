<?php

class MainPageSA extends MainPage {      
    /**     
     * show page Satuan[dmaster]
     */
    public $showJabatan=false;
    /**     
     * show page Satuan[dmaster]
     */
    public $showSatuan=false;        
    /**     
     * show page Jenis Usaha [dmaster]
     */
    public $showJenisIzinUsaha=false;       
    /**     
     * show page Jenis Alat [dmaster]
     */
    public $showJenisAlat=false; 
    /**     
     * show page Bahan Alat [dmaster]
     */
    public $showBahanAlat=false; 
    /**     
     * show page lokasi [Lokasi]
    */
    public $showLokasi=false;    
    /**     
     * show page lokasi usaha [Lokasi]
     */
    public $showLokasiUsaha=false; 
    /**     
     * show page area penangkapan [Lokasi]
     */
    public $showAreaPenangkapan=false;    
    /**     
     * show page lokasi Negara [Lokasi]
     */
    public $showNegara=false;
    /**     
     * show page lokasi DT I [lokasi]
     */
    public $showDT1=false;
    /**     
     * show page lokasi DT II [lokasi]
     */
    public $showDT2=false;
    /**     
     * show page kecamatan [lokasi]
     */
    public $showKecamatan=false;
    /**     
     * show page pelabuhan [lokasi]
     */
    public $showPelabuhan=false;
    
    /**     
     * show page UPTD
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