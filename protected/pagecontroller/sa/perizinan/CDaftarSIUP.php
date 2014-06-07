<?php
prado::using ('Application.MainPageSA');
class CDaftarSIUP extends MainPageSA {    
	public function onLoad($param) {		
		parent::onLoad($param);		        
        $this->showDaftarIzin=true; 
        $this->showDaftarSIUP=true;   
        $this->createObj('DMaster');
        $this->createObj('Perizinan');
		if (!$this->IsPostBack&&!$this->IsCallBack) {
            if (!isset($_SESSION['currentPageDaftarSIUP'])||$_SESSION['currentPageDaftarSIUP']['page_name']!='sa.perizinan.DaftarSIUP') {
                $_SESSION['currentPageDaftarSIUP']=array('page_name'=>'sa.perizinan.DaftarSIUP','page_num'=>0,'search'=>false,'iduptd'=>'none','jenispengajuan'=>'none');	                
            }     
            $_SESSION['currentPagePemohon']['search']=false;
            $listuptd=$this->DMaster->getListUPTD();
            $this->cmbUPTD->DataSource=$listuptd;
            $this->cmbUPTD->Text=$_SESSION['currentPageDaftarSIUP']['iduptd'];
            $this->cmbUPTD->DataBind();
            $this->populateData();
		}
	}
    public function renderCallback ($sender,$param) {
		$this->RepeaterS->render($param->NewWriter);	
	}	
	public function Page_Changed ($sender,$param) {
		$_SESSION['currentPageDaftarSIUP']['page_num']=$param->NewPageIndex;
		$this->populateData($_SESSION['currentPageDaftarSIUP']['search']);
	}
    public function filterUPTD ($sender,$param) {		
        if ($this->IsValid) {
            $_SESSION['currentPageDaftarSIUP']['iduptd']=$this->cmbUPTD->Text;
            $this->populateData($_SESSION['currentPageDaftarSIUP']['search']);
        }
	}
    protected function populateData ($search=false) {
        $iduptd=$_SESSION['currentPageDaftarSIUP']['iduptd'];
        $str_iduptd=$iduptd=='none'?'':" AND s.iduptd=$iduptd";
        $str = "SELECT s.RecNoSiup,s.NoSiup,sdp.NmPem,s.nama_uptd,jiu.InsIzin FROM siup s,jenisizinusaha jiu,siup_data_pemohon sdp WHERE s.RecNoIzin=jiu.RecNoIzin AND s.StatusSiup='granted' AND sdp.RecNoPem=s.RecNoPem$str_iduptd";
        $jumlah_baris=$this->DB->getCountRowsOfTable ('siup','RecNoSiup');			
        $this->RepeaterS->CurrentPageIndex=$_SESSION['currentPageDaftarSIUP']['page_num'];		
		$this->RepeaterS->VirtualItemCount=$jumlah_baris;
		$currentPage=$this->RepeaterS->CurrentPageIndex;
		$offset=$currentPage*$this->RepeaterS->PageSize;		
		$itemcount=$this->RepeaterS->VirtualItemCount;
		$limit=$this->RepeaterS->PageSize;
		if (($offset+$limit)>$itemcount) {
			$limit=$itemcount-$offset;
		}
		if ($limit <= 0) {$offset=0;$limit=10;$_SESSION['currentPageDaftarSIUP']['page_num']=0;}
        $str = "$str ORDER BY date_added DESC LIMIT $offset,$limit";        
        $this->DB->setFieldTable(array('RecNoSiup','NoSiup','NmPem','nama_uptd','InsIzin'));
		$r=$this->DB->getRecord($str,$offset+1);                
        
		$this->RepeaterS->DataSource=$r;
		$this->RepeaterS->dataBind();      		
    }
    public function printOut ($sender,$param) {        
        $event=$sender->CommandParameter;    
        $this->lblPrintout->Text='Sertifikat SIUP';
        switch ($event) {
            case 'eventFromRepeater' :
                $recnosiup=$this->getDataKeyField($sender,$this->RepeaterS); 
                $this->Perizinan->setRecNoPem($recnosiup,true,1);
                $dataReport=$this->Perizinan->DataPemohon;                
            break;
        }        
        $dataReport['outputmode']='pdf';
        $dataReport['recnosiup']=$recnosiup;
        $dataReport['linkoutput']=$this->linkOutput;        
        $this->Perizinan->setDataReport($dataReport);
        $this->Perizinan->printSIUP($recnosiup,'pdf');   
        $this->modalPrintOut->show();
    }
    
}
		