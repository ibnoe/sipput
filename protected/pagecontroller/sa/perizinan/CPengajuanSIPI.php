<?php
prado::using ('Application.MainPageSA');
class CPengajuanSIPI extends MainPageSA {          
    public function onLoad($param) {		
		parent::onLoad($param);		        
        $this->showDaftarPengajuan=true; 
        $this->showPengajuanSIPI=true;                 
        $this->createObj('Pemohon');        
		if (!$this->IsPostBack&&!$this->IsCallBack) {
            if (isset($_SESSION['currentPagePengajuanSIPI']['datapengajuan']['recnobup'])) {
                $this->dataPengajuan=$_SESSION['currentPagePengajuanSIPI']['datapengajuan'];                
                $this->idProcess='view';
                $this->MultiView->ActiveViewIndex=$this->dataPengajuan['currentview'];
            }else {                
                if (!isset($_SESSION['currentPagePengajuanSIPI'])||$_SESSION['currentPagePengajuanSIPI']['page_name']!='sa.perizinan.PengajuanSIPI') {
                    $_SESSION['currentPagePengajuanSIPI']=array('page_name'=>'sa.perizinan.PengajuanSIPI','page_num'=>0,'search'=>false,'iduptd'=>'none','jenispengajuan'=>'none','datapengajuan'=>array());	                
                }       
                $this->cmbJenisPengajuan->Text=$_SESSION['currentPagePengajuanSIPI']['jenispengajuan'];
                $this->labelDaftarPengajuan->Text=$_SESSION['currentPagePengajuanSIPI']['jenispengajuan']=='none'?'':strtoupper($_SESSION['currentPagePengajuanSIPI']['jenispengajuan']);
                $this->populateData();
            }
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
        $str_stastuspermohonan=$_SESSION['currentPagePengajuanSIPI']['jenispengajuan']=='none'?'':"AND JnsDtSIUP='{$_SESSION['currentPagePengajuanSIPI']['jenispengajuan']}'";
        $str = "SELECT s.RecNoSiup,bup.RecNoBup,JnsDtSIUP,NoRegSiup,NmPem,NoSrtSiup,TglSrtSiup,StatusBup,bup.date_added FROM siup s JOIN bup ON (bup.RecNoSiup=s.RecNoSiup) LEFT JOIN siup_data_pemohon sdp ON (sdp.RecNoSiup=s.RecNoSiup) WHERE s.RecNoIzin=1 AND StatusBup='approved' $str_stastuspermohonan";
        if ($search) {
            
        }else{
            $jumlah_baris=$this->DB->getCountRowsOfTable ("siup WHERE RecNoIzin=1 $str_stastuspermohonan",'RecNoPem');			
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
        $this->DB->setFieldTable(array('RecNoSiup','RecNoBup','NmPem','JnsDtSIUP','NoRegSiup','NoSrtSiup','TglSrtSiup','StatusBup','date_added'));
		$r=$this->DB->getRecord($str,$offset+1);             
		$this->RepeaterS->DataSource=$r;
		$this->RepeaterS->dataBind();      		
    }
    public function deleteRecord ($sender,$param) {
        $recnosiup=$sender->CommandParameter;
        $this->DB->deleteRecord("siup WHERE RecNoSiup='$recnosiup'");        
        $this->redirect('perizinan.PengajuanSIPI',true);					        
    }
    public function viewDetailPengajuan ($sender,$param) {
        $recnobup=$this->getDataKeyField($sender,$this->RepeaterS);
        $recnosiup=$sender->CommandParameter;        
        $this->Pemohon->setRecNoPem($recnosiup,true,1);        
        $data=$this->Pemohon->DataPemohon;        
        $data['recnobup']=$recnobup;
        $data['recnosiup']=$recnosiup;
        $data['currentview']=0; 
        $_SESSION['currentPagePengajuanSIPI']['datapengajuan']=$data;
        $this->redirect('perizinan.PengajuanSIPI',true);
    }    
    public function viewChanged($sender,$param) {
        $this->idProcess='view'; 
        $activeviewindex=$this->MultiView->ActiveViewIndex;
        $dataPengajuan=$_SESSION['currentPagePengajuanSIPI']['datapengajuan'];
        $recnosiup=$dataPengajuan['recnosiup'];
        $recnobup=$dataPengajuan['recnobup'];
        $_SESSION['currentPagePengajuanSIPI']['datapengajuan']['currentview']=$activeviewindex;
        switch ($activeviewindex) {
            case 0 : //view pengajuan SIUP
                $masaberlakusiup=$this->setup->getSettingValue('masa_berlaku_siup');
                $str = "SELECT NoSiup,TglStartSiup,TglLastSiup FROM siup WHERE RecNoSiup=$recnosiup";
                $this->DB->setFieldTable(array('NoSiup','TglStartSiup','TglLastSiup'));
                $r=$this->DB->getRecord($str);
                $this->txtNoSIUP->Text=$r[1]['NoSiup'];
                if ($r[1]['TglStartSiup']=='0000-00-00') {                    
                    $this->cmbSelesaiBerlakuSIUP->Text=$this->TGL->getDateNextYear(date('Y-m-d'),$masaberlakusiup,'d-m-Y');
                }else {
                    $this->cmbMulaiBerlakuSIUP->Text=$this->TGL->tanggal('d-m-Y',$r[1]['TglStartSiup']);
                    $this->cmbSelesaiBerlakuSIUP->Text=$this->TGL->tanggal('d-m-Y',$r[1]['TglLastSiup']);
                }
            break;
            case 1 : //view pengajuan SIPI
                $masaberlakusipi=$this->setup->getSettingValue('masa_berlaku_sipi');
                $str = "SELECT NoSiup,NoBUP,TglStartBUP,TglLastBUP FROM bup,siup s WHERE bup.RecNoSiup=s.RecNoSiup AND RecNoBup=$recnobup";
                $this->DB->setFieldTable(array('NoSiup','NoBUP','TglStartBUP','TglLastBUP'));
                $r=$this->DB->getRecord($str);                
                $this->hiddennosiup->Value=$r[1]['NoSiup'];
                $this->literalNoSiup->Text=$r[1]['NoSiup']==''?'N.A':$r[1]['NoSiup'];
                $this->txtNoSIPI->Text=$r[1]['NoBUP'];
                if ($r[1]['TglStartBUP']=='0000-00-00') {                    
                    $this->cmbSelesaiBerlakuSIPI->Text=$this->TGL->getDateNextYear(date('Y-m-d'),$masaberlakusipi,'d-m-Y');
                }else {
                    $this->cmbMulaiBerlakuSIPI->Text=$this->TGL->tanggal('d-m-Y',$r[1]['TglStartBUP']);
                    $this->cmbSelesaiBerlakuSIPI->Text=$this->TGL->tanggal('d-m-Y',$r[1]['TglLastBUP']);
                }
            break;
        }
    }
    public function changeMasaBerlakuSIUP ($sender,$param) {
        $this->idProcess='view';
        $masaberlakusiup=$this->setup->getSettingValue('masa_berlaku_siup');        
        $this->cmbSelesaiBerlakuSIUP->Text=$this->TGL->getDateNextYear(date('Y-m-d',$this->cmbMulaiBerlakuSIUP->TimeStamp),$masaberlakusiup,'d-m-Y');
    }
    public function saveDataSIUP ($sender,$param) {
        $this->idProcess='view';
        if ($this->IsValid) {
            $dataPengajuan=$_SESSION['currentPagePengajuanSIPI']['datapengajuan'];
            $recnosiup=$dataPengajuan['recnosiup'];
            $nosiup=addslashes($this->txtNoSIUP->Text);
            $mulaiberlaku=date('Y-m-d',$this->cmbMulaiBerlakuSIUP->TimeStamp);
            $selesaiberlaku=date('Y-m-d',$this->cmbSelesaiBerlakuSIUP->TimeStamp);
            $str = "UPDATE siup SET NoSiup='$nosiup',TglStartSiup='$mulaiberlaku',TglLastSiup='$selesaiberlaku' WHERE RecNoSiup=$recnosiup";
            $this->DB->updateRecord($str);
            $this->redirect('perizinan.PengajuanSIPI', true);
        }
    }
    public function saveDataSIPI ($sender,$param) {
        $this->idProcess='view';
        if ($this->IsValid) {
            $dataPengajuan=$_SESSION['currentPagePengajuanSIPI']['datapengajuan'];
            $recnobup=$dataPengajuan['recnobup'];
            $nosipi=addslashes($this->txtNoSIPI->Text);
            $mulaiberlaku=date('Y-m-d',$this->cmbMulaiBerlakuSIPI->TimeStamp);
            $selesaiberlaku=date('Y-m-d',$this->cmbSelesaiBerlakuSIPI->TimeStamp);
            $str = "UPDATE bup SET NoBUP='$nosipi',TglStartBUP='$mulaiberlaku',TglLastBUP='$selesaiberlaku' WHERE RecNoBup=$recnobup";
            $this->DB->updateRecord($str);
            $this->redirect('perizinan.PengajuanSIPI', true);
        }
    }
    public function setujuiPengajuanSIUP ($sender,$param) {
        $this->idProcess='view';
        if ($this->IsValid) {
            $this->dataPengajuan=$_SESSION['currentPagePengajuanSIPI']['datapengajuan'];            
            $recnosiup=$this->dataPengajuan['recnosiup'];            
            $tglsahsiup=date('Y-m-d',$this->cmbMulaiBerlakuSIUP->TimeStamp);
            $tahunsahsiup=date('Y',$this->cmbMulaiBerlakuSIUP->TimeStamp);                
            $str = "UPDATE siup SET StatusSiup='granted',TglSahSiup='$tglsahsiup',ThnSahSiup='$tahunsahsiup',ActiveSiup=1,date_modified=NOW() WHERE RecNoSiup=$recnosiup";
            $this->DB->updateRecord($str);            
            $this->redirect('perizinan.PengajuanSIPI', true);
        }
    }
    public function setujuiPengajuanSIPI ($sender,$param) {
        $this->idProcess='view';
        if ($this->IsValid) {
            $this->dataPengajuan=$_SESSION['currentPagePengajuanSIPI']['datapengajuan'];
            $recnobup=$this->dataPengajuan['recnobup'];                              
            $tglsahsipi=date('Y-m-d',$this->cmbMulaiBerlakuSIPI->TimeStamp);
            $tahunsahsipi=date('Y',$this->cmbMulaiBerlakuSIPI->TimeStamp);                
            $str = "UPDATE bup SET TglSahBUP='$tglsahsipi',StatusBup='granted',ActiveBup=1,ThnDtBup='$tahunsahsipi',date_modified=NOW() WHERE RecNoBup=$recnobup";
            $this->DB->updateRecord($str);            
            $this->redirect('perizinan.PengajuanSIPI', true);
        }
    }
    public function closeDetailPengajuan ($sender,$param) {
        $_SESSION['currentPagePengajuanSIPI']['datapengajuan']=array();
        $this->redirect('perizinan.PengajuanSIPI',true);
    }
}
		