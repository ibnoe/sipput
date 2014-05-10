<?php
prado::using ('Application.MainPageSA');
class CDaftarSIUP extends MainPageSA {    
	public function onLoad($param) {		
		parent::onLoad($param);		        
        $this->showDaftarIzin=true; 
        $this->showDaftarSIUP=true;                 
        $this->createObj('Perizinan');
		if (!$this->IsPostBack&&!$this->IsCallBack) {
            if (!isset($_SESSION['currentPageDaftarSIUP'])||$_SESSION['currentPageDaftarSIUP']['page_name']!='sa.perizinan.DaftarSIUP') {
                $_SESSION['currentPageDaftarSIUP']=array('page_name'=>'sa.perizinan.DaftarSIUP','page_num'=>0,'search'=>false,'iduptd'=>$this->Pengguna->getDataUser('iduptd'),'jenispengajuan'=>'none');	                
            }           
		}
	}
    public function renderCallback ($sender,$param) {
		$this->RepeaterS->render($param->NewWriter);	
	}	
	public function Page_Changed ($sender,$param) {
		$_SESSION['currentPageDaftarSIUP']['page_num']=$param->NewPageIndex;
		$this->populateData($_SESSION['currentPageDaftarSIUP']['search']);
	}    
    protected function populateData ($search=false) {
        $iduptd=$_SESSION['currentPageDaftarSIUP']['iduptd'];       
    }
    public function printOut ($sender,$param) {        
//        $recnosiup=$this->getDataKeyField($sender,$this->RepeaterS);        
        $dataReport['outputmode']='pdf';
        $dataReport['recnosiup']=$recnosiup;
        $dataReport['linkoutput']=$this->linkOutput;        
        $this->Perizinan->setDataReport($dataReport);
        $this->Perizinan->printSIUP($recnosiup,'pdf');        
    }
    
}
		