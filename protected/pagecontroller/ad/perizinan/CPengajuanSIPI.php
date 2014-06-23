<?php
prado::using ('Application.MainPageAD');
class CPengajuanSIPI extends MainPageAD {          
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
                if (!isset($_SESSION['currentPagePengajuanSIPI'])||$_SESSION['currentPagePengajuanSIPI']['page_name']!='ad.perizinan.PengajuanSIPI') {
                    $_SESSION['currentPagePengajuanSIPI']=array('page_name'=>'ad.perizinan.PengajuanSIPI','page_num'=>0,'search'=>false,'iduptd'=>$this->Pengguna->getDataUser('iduptd'),'jenispengajuan'=>'none','datapengajuan'=>array());	                
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
        $iduptd=$_SESSION['currentPagePengajuanSIPI']['iduptd'];
        $str_stastuspermohonan=$_SESSION['currentPagePengajuanSIPI']['jenispengajuan']=='none'?'':"AND JnsDtSIUP='{$_SESSION['currentPagePengajuanSIPI']['jenispengajuan']}'";
        $str = "SELECT s.RecNoSiup,bup.RecNoBup,s.JnsDtSIUP,s.NoRegSiup,sdp.NmPem,sdpe.idsiup_data_perusahaan,sdpe.RecStsCom,sdpe.NmCom,s.NoSrtSiup,s.TglSrtSiup,bup.StatusBup,s.JnsDtPemSIUP,bup.date_added FROM siup s JOIN bup ON (bup.RecNoSiup=s.RecNoSiup) LEFT JOIN siup_data_pemohon sdp ON (sdp.RecNoSiup=s.RecNoSiup) LEFT JOIN siup_data_perusahaan sdpe ON (sdpe.RecNoSiup=s.RecNoSiup) WHERE s.RecNoIzin=1 AND s.iduptd=$iduptd $str_stastuspermohonan";
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
        $this->DB->setFieldTable(array('RecNoSiup','RecNoBup','NmPem','idsiup_data_perusahaan','RecStsCom','NmCom','JnsDtSIUP','NoRegSiup','NoSrtSiup','TglSrtSiup','JnsDtPemSIUP','StatusBup','date_added'));
		$r=$this->DB->getRecord($str,$offset+1);   
        $result=array();
        while (list($k,$v)=each($r)) {
            if ($v['JnsDtPemSIUP'] == 'perusahaan')  {
                $v['RecNoSiup']=$v['RecNoSiup'] . '/'.$v['idsiup_data_perusahaan'];
                $v['NmPem']=$v['NmCom'];
            }else {
                $v['RecNoSiup']=$v['RecNoSiup'] . '/0';
            }
            $result[$k]=$v;
        }
		$this->RepeaterS->DataSource=$result;
		$this->RepeaterS->dataBind();      		
    }
    public function deleteRecord ($sender,$param) {
        $recnosiup=$sender->CommandParameter;
        $this->DB->deleteRecord("siup WHERE RecNoSiup='$recnosiup'");        
        $this->redirect('perizinan.PengajuanSIPI',true);					        
    }
    public function viewDetailPengajuan ($sender,$param) {
        $recnobup=$this->getDataKeyField($sender,$this->RepeaterS);
        $recnosiup=explode('/',$sender->CommandParameter);  
        
        $this->Pemohon->setRecNoSIUP($sender->CommandParameter,true);        
        $data=$this->Pemohon->DataPemohon;                        
        $data['recnobup']=$recnobup;
        $data['recnosiup']=$recnosiup[0];
        $data['recsiupdataperusahaan']=$recnosiup[1];
        
        $data['currentview']=0;                       
        $_SESSION['currentPagePengajuanSIPI']['datapengajuan']=$data;
        $this->redirect('perizinan.PengajuanSIPI',true);
    }    
    public function viewChanged($sender,$param) {
        $this->idProcess='view'; 
        $recnosiup=$_SESSION['currentPagePengajuanSIPI']['datapengajuan']['recnosiup'];
        $activeviewindex=$this->MultiView->ActiveViewIndex;
        $_SESSION['currentPagePengajuanSIPI']['datapengajuan']['currentview']=$activeviewindex;
        switch ($activeviewindex) {
            case 0 :
                $bool_bukukapal=true;
                $bool_siup=true;
                $bool_sipi=true;
                $this->createObj('Perizinan');                      
                $persyaratan_bukukapal_pemohon=$this->Perizinan->getPersyaratanPengajuanMilikPemohon('bukukapal',$recnosiup,0);
                if (count($persyaratan_bukukapal_pemohon) > 1 ) {    
                    $bool_bukukapal=false;
                    $this->RepeaterPermohonanBUKUKAPAL->DataSource=$persyaratan_bukukapal_pemohon;                     
                    $this->RepeaterPermohonanBUKUKAPAL->DataBind();                                
                }else {
                    $persyaratan_bukukapal=$this->Perizinan->getPersyaratanPengajuan('bukukapal');                
                    $this->listSyaratPermohonanBUKUKAPAL->DataSource=$persyaratan_bukukapal; 
                    $this->listSyaratPermohonanBUKUKAPAL->DataBind();                               
                }                
                $persyaratan_bukusiup_pemohon=$this->Perizinan->getPersyaratanPengajuanMilikPemohon('siup',$recnosiup,0);
                if (count($persyaratan_bukusiup_pemohon) > 1 ) {                    
                    $bool_siup=false;
                    $this->RepeaterPermohonanSIUP->DataSource=$persyaratan_bukusiup_pemohon;                     
                    $this->RepeaterPermohonanSIUP->DataBind(); 
                }else{
                    $this->listSyaratPermohonanSIUP->DataSource=$this->Perizinan->getPersyaratanPengajuan('siup');
                    $this->listSyaratPermohonanSIUP->DataBind();                                                            
                }
                $persyaratan_bukusipi_pemohon=$this->Perizinan->getPersyaratanPengajuanMilikPemohon('sipi',$recnosiup,0);
                if (count($persyaratan_bukusipi_pemohon) > 1 ) {                    
                    $bool_sipi=false;
                    $this->RepeaterPermohonanSIPI->DataSource=$persyaratan_bukusipi_pemohon;                     
                    $this->RepeaterPermohonanSIPI->DataBind(); 
                }else{
                    $this->listSyaratPermohonanSIPI->DataSource=$this->Perizinan->getPersyaratanPengajuan('sipi');
                    $this->listSyaratPermohonanSIPI->DataBind();                                                            
                }
                $this->btnVerifikasi->Enabled=($bool_bukukapal&&$bool_siup&&$bool_sipi) ? true:false;
            break;
        }
    }
    public function closeDetailPengajuan ($sender,$param) {
        $_SESSION['currentPagePengajuanSIPI']['datapengajuan']=array();
        $this->redirect('perizinan.PengajuanSIPI',true);
    }    
    public function verifikasiData ($sender,$param) {
        $this->idProcess='view';
        if ($this->IsValid) {
            $this->dataPengajuan=$_SESSION['currentPagePengajuanSIPI']['datapengajuan'];
            $recnobup=$this->dataPengajuan['recnobup'];
            $recnosiup=$this->dataPengajuan['recnosiup'];
            $indices_bukukapal=$this->listSyaratPermohonanBUKUKAPAL->SelectedIndices;   
            $totalItemBUKUKAPAL=$this->listSyaratPermohonanBUKUKAPAL->getItemCount(); 
            $totalBukuSelected=count($indices_bukukapal);
            $bool_buku_kapal=false;            
            if ($totalBukuSelected == $totalItemBUKUKAPAL) {
                $bool_buku_kapal=true;
                for ($i=0; $i<$totalBukuSelected;$i++) {                                 
                    $item=$this->listSyaratPermohonanBUKUKAPAL->Items[$i];
                    $idpersyaratan=$item->Value;
                    $nama_persyaratan=preg_replace("/&nbsp;/",'',addslashes($item->Text));
                    if ($totalBukuSelected > $i+1) {
                        $value_buku_kapal = "$value_buku_kapal (NULL,$recnosiup,$idpersyaratan,'bukukapal','$nama_persyaratan',1),";                                
                    }else{
                        $value_buku_kapal = "$value_buku_kapal (NULL,$recnosiup,$idpersyaratan,'bukukapal','$nama_persyaratan',1)";                                
                    }
                }                                 
            }   
            $indices_siup=$this->listSyaratPermohonanSIUP->SelectedIndices;   
            $totalItemSIUP=$this->listSyaratPermohonanSIUP->getItemCount(); 
            $totalSIUPSelected=count($indices_siup);
            $bool_siup_kapal=false;
            if ($totalSIUPSelected == $totalItemSIUP) {
                $bool_siup_kapal=true;
                $value='';
                for ($i=0; $i<$totalSIUPSelected;$i++) {
                    $item=$this->listSyaratPermohonanSIUP->Items[$i];
                    $idpersyaratan=$item->Value;
                    $nama_persyaratan=preg_replace("/&nbsp;/",'',addslashes($item->Text));
                    if ($totalSIUPSelected > $i+1) {
                        $value_siup = "$value_siup (NULL,$recnosiup,$idpersyaratan,'siup','$nama_persyaratan',1),";                                
                    }else{
                        $value_siup = "$value_siup (NULL,$recnosiup,$idpersyaratan,'siup','$nama_persyaratan',1)";                                
                    }                                        
                }                
            }
            $indices_sipi=$this->listSyaratPermohonanSIPI->SelectedIndices;   
            $totalItemSIPI=$this->listSyaratPermohonanSIPI->getItemCount(); 
            $totalSIPISelected=count($indices_sipi);
            $bool_sipi_kapal=false;
            if ($totalSIPISelected == $totalItemSIPI) {
                $bool_sipi_kapal=true;                
                for ($i=0; $i<$totalSIPISelected;$i++) {
                    $item=$this->listSyaratPermohonanSIPI->Items[$i];
                    $idpersyaratan=$item->Value;
                    $nama_persyaratan=preg_replace("/&nbsp;/",'',addslashes($item->Text));
                    if ($totalSIPISelected > $i+1) {
                        $value_sipi = "$value_sipi (NULL,$recnosiup,$idpersyaratan,'sipi','$nama_persyaratan',1),";                                
                    }else{
                        $value_sipi = "$value_sipi (NULL,$recnosiup,$idpersyaratan,'sipi','$nama_persyaratan',1)";                                
                    }                                        
                }                
            }
            if ($bool_buku_kapal&&$bool_siup_kapal&&$bool_sipi_kapal) {
                $this->DB->query('BEGIN');        
                $str_persyaratan_siup = "INSERT INTO persyaratan_siup (idpersyaratan_siup,RecNoSiup,idpersyaratan,`group`,nama_persyaratan,status) VALUES $value_buku_kapal,$value_siup,$value_sipi";                 
                $this->DB->insertRecord($str_persyaratan_siup);         
                $str = "UPDATE bup SET StatusBup='verified',date_modified=NOW() WHERE RecNoBup=$recnobup";
                if ($this->DB->updateRecord($str)) {
                    $str = "UPDATE siup SET StatusSiup='verified',date_modified=NOW() WHERE RecNoSiup=$recnosiup";
                    $this->DB->updateRecord($str);
                    $this->DB->query('COMMIT');
                }else {
                    $this->DB->query('ROLLBACK');
                }
                $this->redirect('perizinan.PengajuanSIPI', true);
            }else {
                $this->errormessage->Text="Mohon diperiksa kembali persyaratan, apakah sudah di centang semuanya ?";
            }
        }
    }
    public function approvalData ($sender,$param) {
        $this->idProcess='view';
        $this->IsValid=false;
        if ($this->IsValid) {
            $this->dataPengajuan=$_SESSION['currentPagePengajuanSIPI']['datapengajuan'];
            $recnobup=$this->dataPengajuan['recnobup'];
            $recnosiup=$this->dataPengajuan['recnosiup'];
            $this->DB->query('BEGIN');
            $str = "UPDATE bup SET StatusBup='approved',date_modified=NOW() WHERE RecNoBup=$recnobup";
            if ($this->DB->updateRecord($str)) {
                $str = "UPDATE siup SET StatusSiup='approved',date_modified=NOW() WHERE RecNoSiup=$recnosiup";
                $this->DB->updateRecord($str);
                $this->DB->query('COMMIT');
            }else {
                $this->DB->query('ROLLBACK');
            }
            $this->redirect('perizinan.PengajuanSIPI', true);
        }
    }
    public function printOut ($sender,$param) {  
        $this->idProcess='view';
        $processprintout=$sender->CommandParameter;
        $dataReport=$_SESSION['currentPagePengajuanSIPI']['datapengajuan'];                
        $dataReport['outputmode']='pdf';                
        $dataReport['linkoutput']=$this->linkOutput;                
        switch ($processprintout) {
            case 'pemeriksaankapal':  
                $label='Form Pemeriksaan Fisik Kapal';
                $this->Pemohon->setDataReport($dataReport);
                $this->Pemohon->printFormPemeriksaanFisikKapal();
            break;
            case 'suratpengantar':            
                if ($this->DB->checkRecordIsExist('StatusBup','bup',$dataReport['StatusBup']," AND RecNoBup={$dataReport['recnobup']}")) {
                    $label='Surat Pengantar dari KA. UPT';
                    $this->Pemohon->setDataReport($dataReport);
                    $this->Pemohon->printSuratPengantarUPT();
                }else {
                    $label='Surat Pengantar Tidak bisa';
                    $this->labelBoxBody->Text='Tidak bisa print Surat Pengantar dari KA. UPT karena belum di Approve.';
                }                
            break;
        }        
        $this->lblPrintout->Text = $label;
        $this->modalPrintOut->show();
    }
    
}
		