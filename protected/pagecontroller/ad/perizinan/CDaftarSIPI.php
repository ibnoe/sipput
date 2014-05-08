<?php
prado::using ('Application.MainPageSA');
class CDaftarSIPI extends MainPageSA {    
	public function onLoad($param) {		
		parent::onLoad($param);		        
        $this->showDaftarIzin=true; 
        $this->showDaftarSIPI=true;                 
        $this->createObj('Perizinan');
		if (!$this->IsPostBack&&!$this->IsCallBack) {
            if (!isset($_SESSION['currentPageDaftarSIPI'])||$_SESSION['currentPageDaftarSIPI']['page_name']!='ad.perizinan.DaftarSIPI') {
                $_SESSION['currentPageDaftarSIPI']=array('page_name'=>'ad.perizinan.DaftarSIPI','page_num'=>0,'search'=>false,'iduptd'=>$this->Pengguna->getDataUser('iduptd'),'jenispengajuan'=>'none');	                
            }           
		}
	}
    public function renderCallback ($sender,$param) {
		$this->RepeaterS->render($param->NewWriter);	
	}	
	public function Page_Changed ($sender,$param) {
		$_SESSION['currentPageDaftarSIPI']['page_num']=$param->NewPageIndex;
		$this->populateData($_SESSION['currentPageDaftarSIPI']['search']);
	}    
    protected function populateData ($search=false) {
        $iduptd=$_SESSION['currentPageDaftarSIPI']['iduptd'];       
    }
    public function printOut ($sender,$param) {        
//        $recnosiup=$this->getDataKeyField($sender,$this->RepeaterS);        
        $dataReport['outputmode']='pdf';
        $dataReport['recnosiup']=$recnosiup;
        $dataReport['linkoutput']=$this->linkOutput;        
        $this->Perizinan->setDataReport($dataReport);
        $this->Perizinan->printSIPI($recnosiup,'pdf');        
    }
    
}
		