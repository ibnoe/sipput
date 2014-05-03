<?php
prado::using ('Application.MainPageSA');
class CPengajuanSIPI extends MainPageSA {    
	public function onLoad($param) {		
		parent::onLoad($param);		        
        $this->showDaftarPengajuan=true; 
        $this->showPengajuanSIPI=true;                 
        $this->createObj('Pemohon');
		if (!$this->IsPostBack&&!$this->IsCallBack) {
            if (!isset($_SESSION['currentPagePengajuanSIPI'])||$_SESSION['currentPagePengajuanSIPI']['page_name']!='ad.perizinan.PengajuanSIPI') {
                $_SESSION['currentPagePengajuanSIPI']=array('page_name'=>'ad.perizinan.PengajuanSIPI','page_num'=>0,'search'=>false,'iduptd'=>$this->Pengguna->getDataUser('iduptd'),'jenispengajuan'=>'none');	                
            }       
            $this->cmbJenisPengajuan->Text=$_SESSION['currentPagePengajuanSIPI']['jenispengajuan'];
            $this->labelDaftarPengajuan->Text=$_SESSION['currentPagePengajuanSIPI']['jenispengajuan']=='none'?'':strtoupper($_SESSION['currentPagePengajuanSIPI']['jenispengajuan']);
            $this->populateData();
		}
	}
    public function renderCallback ($sender,$param) {
		$this->RepeaterS->render($param->NewWriter);	
	}	
	public function Page_Changed ($sender,$param) {
		$_SESSION['currentPagePengajuanSIPI']['page_num']=$param->NewPageIndex;
		$this->populateData($_SESSION['currentPagePengajuanSIPI']['search']);
	}
    public function changeStatusPengajuan($sender,$param) {
        $_SESSION['currentPagePengajuanSIPI']['jenispengajuan']=$this->cmbJenisPengajuan->Text;    
        $this->labelDaftarPengajuan->Text=$_SESSION['currentPagePengajuanSIPI']['jenispengajuan']=='none'?'':strtoupper($_SESSION['currentPagePengajuanSIPI']['jenispengajuan']);
        $this->populateData();
    }
    protected function populateData ($search=false) {
        $iduptd=$_SESSION['currentPagePengajuanSIPI']['iduptd'];
        $str_stastuspermohonan=$_SESSION['currentPagePengajuanSIPI']['jenispengajuan']=='none'?'':"AND JnsDtSIUP='{$_SESSION['currentPagePengajuanSIPI']['jenispengajuan']}'";
        $str = "SELECT s.RecNoSiup,JnsDtSIUP,NoRegSiup,NmPem,NoSrtSiup,TglSrtSiup,date_added FROM siup s LEFT JOIN siup_data_pemohon sdp ON (sdp.RecNoSiup=s.RecNoSiup) WHERE RecNoIzin=1 AND iduptd=$iduptd $str_stastuspermohonan";
        if ($search) {
            
        }else{
            $jumlah_baris=$this->DB->getCountRowsOfTable ("siup WHERE RecNoIzin=1 AND iduptd=$iduptd $str_stastuspermohonan",'RecNoPem');			
        }
        $this->RepeaterS->CurrentPageIndex=$_SESSION['currentPagePengajuanSIPI']['page_num'];		
		$this->RepeaterS->VirtualItemCount=$jumlah_baris;
		$currentPage=$this->RepeaterS->CurrentPageIndex;
		$offset=$currentPage*$this->RepeaterS->PageSize;		
		$itemcount=$this->RepeaterS->VirtualItemCount;
		$limit=$this->RepeaterS->PageSize;
		if (($offset+$limit)>$itemcount) {
			$limit=$itemcount-$offset;
		}
		if ($limit <= 0) {$offset=0;$limit=10;$_SESSION['currentPagePengajuanSIPI']['page_num']=0;}
        $str = "$str ORDER BY date_added DESC LIMIT $offset,$limit";        
        $this->DB->setFieldTable(array('RecNoSiup','NmPem','JnsDtSIUP','NoRegSiup','NoSrtSiup','TglSrtSiup','date_added'));
		$r=$this->DB->getRecord($str,$offset+1);        
		$this->RepeaterS->DataSource=$r;
		$this->RepeaterS->dataBind();      		
    }
    public function deleteRecord ($sender,$param) {
        $id=$this->getDataKeyField($sender,$this->RepeaterS);
        $this->DB->deleteRecord("siup WHERE RecNoSiup='$id'");        
        $this->redirect('perizinan.PengajuanSIPI',true);					        
    }
    public function printOut ($sender,$param) {        
        $recnosiup=$this->getDataKeyField($sender,$this->RepeaterS);        
        $dataReport['outputmode']='pdf';
        $dataReport['recnosiup']=$recnosiup;
        $dataReport['linkoutput']=$this->linkOutput;        
        $this->Pemohon->setDataReport($dataReport);
        $this->Pemohon->printFormPemeriksaanFisikKapal($recnosiup,'pdf');
        $this->modalPrintOut->show();
    }
}
		