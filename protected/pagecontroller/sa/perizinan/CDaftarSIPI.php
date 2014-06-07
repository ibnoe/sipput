<?php
prado::using ('Application.MainPageSA');
class CDaftarSIPI extends MainPageSA {    
	public function onLoad($param) {		
		parent::onLoad($param);		        
        $this->showDaftarIzin=true; 
        $this->showDaftarSIPI=true;                 
        $this->createObj('Perizinan');
		if (!$this->IsPostBack&&!$this->IsCallBack) {
            if (!isset($_SESSION['currentPageDaftarSIPI'])||$_SESSION['currentPageDaftarSIPI']['page_name']!='sa.perizinan.DaftarSIPI') {
                $_SESSION['currentPageDaftarSIPI']=array('page_name'=>'sa.perizinan.DaftarSIPI','page_num'=>0,'search'=>false,'iduptd'=>$this->Pengguna->getDataUser('iduptd'),'jenispengajuan'=>'none');	                
            }           
            $this->populateData();
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
        $str = "SELECT bup.RecNoSiup,bup.RecNoBup,bup.NoBUP,sdp.NmPem,s.nama_uptd,s.NoSiup FROM bup,siup s,siup_data_pemohon sdp WHERE bup.RecNoSiup=s.RecNoSiup AND sdp.RecNoSiup=bup.RecNoSiup AND bup.StatusBup='granted'";
        $jumlah_baris=$this->DB->getCountRowsOfTable ('bup','RecNoSiup');			
        $this->RepeaterS->CurrentPageIndex=$_SESSION['currentPageDaftarSIPI']['page_num'];		
		$this->RepeaterS->VirtualItemCount=$jumlah_baris;
		$currentPage=$this->RepeaterS->CurrentPageIndex;
		$offset=$currentPage*$this->RepeaterS->PageSize;		
		$itemcount=$this->RepeaterS->VirtualItemCount;
		$limit=$this->RepeaterS->PageSize;
		if (($offset+$limit)>$itemcount) {
			$limit=$itemcount-$offset;
		}
		if ($limit <= 0) {$offset=0;$limit=10;$_SESSION['currentPageDaftarSIPI']['page_num']=0;}
        $str = "$str ORDER BY bup.date_added DESC,bup.NoBup DESC LIMIT $offset,$limit";        
        $this->DB->setFieldTable(array('RecNoSiup','RecNoBup','NoBUP','NoSiup','NmPem','nama_uptd'));
		$r=$this->DB->getRecord($str,$offset+1);                
        
		$this->RepeaterS->DataSource=$r;
		$this->RepeaterS->dataBind();
    }
    public function printOut ($sender,$param) {                
        $event=$sender->getId();    
        $recnosiup=$sender->CommandParameter;
        $this->lblPrintout->Text='Sertifikat SIPI';
        switch ($event) {
            case 'btnPrintOutRepeater' :
                $recnobup=$this->getDataKeyField($sender,$this->RepeaterS);                 
                $this->Perizinan->setRecNoPem($recnosiup,true,1);
                $dataReport=$this->Perizinan->DataPemohon;                
            break;
        }        
        $dataReport['outputmode']='pdf';
        $dataReport['recnobup']=$recnobup;        
        $dataReport['linkoutput']=$this->linkOutput;        
        $this->Perizinan->setDataReport($dataReport);
        $this->Perizinan->printSIPI(); 
        $this->modalPrintOut->show();
    }
    
}
		