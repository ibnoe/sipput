<?php
prado::using ('Application.MainPageSA');
class CPemohon extends MainPageSA {
	public function onLoad($param) {		
		parent::onLoad($param);		        
        $this->showPemohon=true;        
        $this->createObj('DMaster');
		if (!$this->IsPostBack&&!$this->IsCallBack) {
            if (!isset($_SESSION['currentPagePemohon'])||$_SESSION['currentPagePemohon']['page_name']!='sa.Pemohon') {
                $_SESSION['currentPagePemohon']=array('page_name'=>'sa.Pemohon','page_num'=>0,'search'=>false,'iduptd'=>'none');	                
            }        
            $_SESSION['currentPagePemohon']['search']=false;            
            $listuptd=$this->DMaster->getListUPTD();
            $this->cmbUPTD->DataSource=$listuptd;
            $this->cmbUPTD->Text=$_SESSION['currentPagePemohon']['iduptd'];
            $this->cmbUPTD->DataBind(); 
            
            $this->cmbAddUPTD->DataSource=$listuptd;
            $this->cmbAddUPTD->Text=$_SESSION['currentPagePemohon']['iduptd'];
            $this->cmbAddUPTD->DataBind(); 
            
            $this->populateData();
		}
	}    
    public function renderCallback ($sender,$param) {
		$this->RepeaterS->render($param->NewWriter);	
	}	
	public function Page_Changed ($sender,$param) {
		$_SESSION['currentPagePemohon']['page_num']=$param->NewPageIndex;
		$this->populateData($_SESSION['currentPagePemohon']['search']);
	} 
    public function filterRecord ($sender,$param) {
		$_SESSION['currentPagePemohon']['search']=true;
        $this->populateData($_SESSION['currentPagePemohon']['search']);
	}
    public function filterUPTD ($sender,$param) {		
        if ($this->IsValid) {
            $_SESSION['currentPagePemohon']['iduptd']=$this->cmbUPTD->Text;
            $this->populateData($_SESSION['currentPagePemohon']['search']);
        }
	}
    private function populateData ($search=false) {    
        $iduptd=$_SESSION['currentPagePemohon']['iduptd'];
        if ($search) {
            $str_iduptd=$iduptd=='none'?'':" AND p.iduptd=$iduptd";
            $str = "SELECT pe.RecNoPem,idCom,NmPem,KtpPem,TelpPem,Foto,pe.nama_uptd,DateAdded FROM pemohon pe LEFT JOIN perusahaan p ON (p.RecNoPem=pe.RecNoPem)";
            $txtsearch=$this->txtKriteria->Text;
            switch ($this->cmbKriteria->Text) {
                case 'kode' :
                    $cluasa="WHERE RecNoPem='$txtsearch' $str_iduptd";
                    $jumlah_baris=$this->DB->getCountRowsOfTable ("pemohon $cluasa $str_iduptd",'RecNoPem');
                    $str = "$str $cluasa";
                break;
                case 'nama_pemohon' :
                    $cluasa="WHERE NmPem  LIKE '%$txtsearch%' $str_iduptd";
                    $jumlah_baris=$this->DB->getCountRowsOfTable ("pemohon $cluasa $str_iduptd",'RecNoPem');
                    $str = "$str $cluasa";
                break;
            }
        }else {
            $str_iduptd=$iduptd=='none'?'':" WHERE iduptd=$iduptd";
            $str = "SELECT pe.RecNoPem,IdCom,NmPem,KtpPem,TelpPem,Foto,pe.nama_uptd,DateAdded FROM pemohon pe LEFT JOIN perusahaan p ON (p.RecNoPem=pe.RecNoPem)$str_iduptd";
            $jumlah_baris=$this->DB->getCountRowsOfTable ("pemohon$str_iduptd",'RecNoPem');			
        }
        $this->RepeaterS->CurrentPageIndex=$_SESSION['currentPagePemohon']['page_num'];		
		$this->RepeaterS->VirtualItemCount=$jumlah_baris;
		$currentPage=$this->RepeaterS->CurrentPageIndex;
		$offset=$currentPage*$this->RepeaterS->PageSize;		
		$itemcount=$this->RepeaterS->VirtualItemCount;
		$limit=$this->RepeaterS->PageSize;
		if (($offset+$limit)>$itemcount) {
			$limit=$itemcount-$offset;
		}
		if ($limit <= 0) {$offset=0;$limit=10;$_SESSION['currentPagePemohon']['page_num']=0;}
        $str = "$str ORDER BY DateAdded DESC LIMIT $offset,$limit";        
		$this->DB->setFieldTable(array('RecNoPem','IdCom','NmPem','KtpPem','TelpPem','Foto','nama_uptd','DateAdded'));
		$r=$this->DB->getRecord($str,$offset+1);                
		$this->RepeaterS->DataSource=$r;
		$this->RepeaterS->dataBind();      		
    }
    public function addProcess($sender,$param) {
        $this->idProcess='add';
        $this->createObj('Pemohon');
        if (!($recnopem=$this->Pemohon->getAutoRecNoPem() )) {
            $recnopem = $this->setup->getSettingValue('mulai_kode_pemohon');
        }        
        $this->txtAddKodePemohon->Text=$recnopem;
    }
    public function checkId ($sender,$param) {
		$this->idProcess=$sender->getId()=='addKodeUPDT'?'add':'edit';
        $idpemohon=$param->Value;
        if ($idpemohon != '') {
            try {
                if ($this->hiddenidpemohon->Value!=$idpemohon) {
                    if ($this->DB->checkRecordIsExist('RecNoPem','pemohon',$idpemohon)) {
                        throw new Exception ("ID Pemohon ($idpemohon) sudah tidak tersedia silahkan ganti dengan yang lain.");
                    }
                }
            }catch (Exception $e) {
                $param->IsValid=false;
                $sender->ErrorMessage=$e->getMessage();
            }
        }
    }
    public function checkTypeFile ($sender,$param) {
        $this->idProcess=$sender->getId()=='addFileFoto'?'add':'edit';
        $control = $this->idProcess == 'add' ?$this->FileFoto:$this->editFileFoto;        
        if ($control->HasFile) {            
            if ($control->FileType!='image/png' && $this->FileFoto->FileType!='image/jpeg')
                $param->IsValid=false;
        }
    } 
    public function processNextButton($sender,$param) {
        $this->idProcess='add';
		if ($param->CurrentStepIndex ==0) {
            if ($this->rdAddStatusPemohonPerorangan->Checked) {
                $this->newpemohonwizard->ActiveStepIndex=2;
            }
		}
	}
    public function addNewPemohonCompleted ($sender,$param) {
        $this->idProcess='add';
        if ($this->FileFoto->HasFile) {
            $filename=substr(hash('sha512',rand()),0,8);
            $name=$this->FileFoto->FileName;
            $part=$this->setup->cleanFileNameString($name);
            $path=$this->setup->getSettingValue('dir_userimages')."/$filename-$part";
            $this->path_userimages->Value=$path;
            $this->FileFoto->saveAs("./$path");
        }else{
            $path=$this->setup->getSettingValue('dir_userimages')."/empty_applicant.png";                
            $this->path_userimages->Value=$path;
        }
    }    
    public function saveData($sender,$param) {		
        if ($this->Page->IsValid) {		                                                                
            $status_pemohon=$this->rdAddStatusPemohonPerorangan->Checked==true?'perorangan':'perusahaan';
            $kode_pemohon=$this->txtAddKodePemohon->Text;
            $nama_pemohon=$this->txtAddNamaPemohon->Text;
            $no_ktp_pemohon=$this->txtAddNoKTP->Text;
            $alamat_pemohon=$this->txtAddAlamatPemohon->Text;
            $npwp_pemohon=$this->txtAddNoNPWPPemohon->Text;
            $notelp_pemohon=$this->txtAddNoTelp->Text;     
            $iduptd=$this->cmbAddUPTD->Text;
            $this->createObj('DMaster');
            $nama_uptd=$this->DMaster->getUPTName($iduptd);
            $path_userimages=$this->path_userimages->Value;
            
            $str = "INSERT INTO pemohon (RecNoPem,NmPem,KtpPem,AlmtPem,TelpPem,NpwpPem,Foto,Status,iduptd,nama_uptd,DateAdded,DateModified) VALUES ('$kode_pemohon','$nama_pemohon','$no_ktp_pemohon','$alamat_pemohon','$notelp_pemohon','$npwp_pemohon','$path_userimages','$status_pemohon','$iduptd','$nama_uptd',NOW(),NOW())";
            $this->DB->query('BEGIN');
            if ($this->DB->insertRecord($str)) {
                if ($status_pemohon=='perusahaan') {
                    $status_perusahaan=$this->rdAddStatusPerusahaanPusat->Checked==true?'pusat':'cabang';
                    $nama_perusahaan=$this->txtAddNamaPerusahaan->Text;
                    $no_akte=$this->txtAddNoAktePerusahaan->Text;
                    $tgl_akte=date('Y-m-d',$this->cmbAddTGLAktePendirian->TimeStamp);
                    $npwp=$this->txtAddNoNPWP->Text;
                    $alamat=$this->txtAddAlamatPerusahaan->Text;
                    $notelepon=$this->txtAddNoTelpPerusahaan->Text;
                    $nofax=$this->txtAddNoFaxPerusahaan->Text;
                    $alamatcabang=$this->txtAddAlamatCabang->Text;
                    $str = "INSERT INTO perusahaan (RecNoPem,RecStsCom,NmCom,NoAkte,TglAkte,NPWPCom,AlmtCom,TelCom,FaxCom,AlmtComCab,iduptd,nama_uptd) VALUES ($kode_pemohon,'$status_perusahaan','$nama_perusahaan','$no_akte','$tgl_akte','$npwp','$alamat','$notelepon','$nofax','$alamatcabang',$iduptd,'$nama_uptd')";
                    $this->DB->insertRecord($str);
                }
                $this->DB->query('COMMIT');            
                $this->redirect('Pemohon',true);
            }else {
                $this->DB->query('ROLLBACK');
            }          
        }
	}
    public function editRecord ($sender,$param) {		
		$this->idProcess='edit';
		$recnopem=$this->getDataKeyField($sender, $this->RepeaterS);
        $idcom=$sender->CommandParameter;
        
		$this->hiddenidpemohon->Value=$recnopem;
        $this->hiddenidcom->Value=$idcom;
        $str = "SELECT NmPem,KtpPem,AlmtPem,TelpPem,NpwpPem,Foto,Status,iduptd FROM pemohon WHERE RecNoPem=$recnopem";
        $this->DB->setFieldTable(array('NmPem','KtpPem','AlmtPem','TelpPem','NpwpPem','Foto','Status','iduptd'));
        $r=$this->DB->getRecord($str);
        
        $this->dataPengajuan=$r[1];
        if ($this->dataPengajuan['Status']=='perorangan')$this->rdEditStatusPemohonPerorangan->Checked=true; else $this->rdEditStatusPemohonPerusahaan->Checked=true;
        $this->txtEditKodePemohon->Text=$recnopem;
        $this->txtEditNamaPemohon->Text=$this->dataPengajuan['NmPem'];
        $this->txtEditNoKTP->Text=$this->dataPengajuan['KtpPem'];
        $this->txtEditAlamatPemohon->Text=$this->dataPengajuan['AlmtPem'];
        $this->txtEditNoTelp->Text=$this->dataPengajuan['TelpPem'];
        $this->txtEditNoNPWPPemohon->Text=$this->dataPengajuan['NpwpPem'];
        $this->editPath_userimages->Value=$this->dataPengajuan['Foto'];
        $listuptd=$this->DMaster->removeIdFromArray($this->DMaster->getListUPTD(),'none');        
        $this->cmbEditUPTD->DataSource=$listuptd;
        $this->cmbEditUPTD->Text=$this->dataPengajuan['iduptd'];
        $this->cmbEditUPTD->DataBind(); 
	}
    public function processEditNextButton($sender,$param) {
        $this->idProcess='edit';          
		if ($param->CurrentStepIndex ==0) {
            if ($this->rdEditStatusPemohonPerorangan->Checked) {
                $this->editpemohonwizard->ActiveStepIndex=2;                
            }else {
                $idcom=$this->hiddenidcom->Value;
                if ($idcom != '') {
                    $str = "SELECT idCom,RecStsCom,NmCom,NoAkte,TglAkte,NPWPCom,AlmtCom,TelCom,FaxCom,AlmtComCab FROM perusahaan WHERE IdCom=$idcom";
                    $this->DB->setFieldTable(array('idCom','RecStsCom','NmCom','NoAkte','TglAkte','NPWPCom','AlmtCom','TelCom','FaxCom','AlmtComCab'));
                    $r=$this->DB->getRecord($str);                
                    $data=$r[1];
                    if($data['RecStsCom']=='pusat') {
                        $this->rdEditStatusPerusahaanPusat->Checked=true;
                    }else {
                        $this->rdEditStatusPerusahaanCabang->Checked=true;
                    }
                    $this->txtEditNamaPerusahaan->Text=$data['NmCom'];
                    $this->txtEditNoAktePerusahaan->Text=$data['NoAkte'];                
                    $this->cmbEditTGLAktePendirian->Text=$this->TGL->tanggal ('d-m-Y',$data['TglAkte']);
                    $this->txtEditNoNPWP->Text=$data['NPWPCom'];
                    $this->txtEditAlamatPerusahaan->Text=$data['AlmtCom'];
                    $this->txtEditNoTelpPerusahaan->Text=$data['TelCom'];
                    $this->txtEditNoFaxPerusahaan->Text=$data['FaxCom'];
                    $this->txtEditAlamatCabang->Text=$data['AlmtComCab'];
                }
            }
		}
	}
    public function editPemohonCompleted ($sender,$param) {
        $this->idProcess='edit';         
        if ($this->editFileFoto->HasFile) {
            $filename=substr(hash('sha512',rand()),0,8);
            $name=$this->editFileFoto->FileName;
            $part=$this->setup->cleanFileNameString($name);
            $path=$this->setup->getSettingValue('dir_userimages')."/$filename-$part";
            $this->editPath_userimages->Value=$path;
            $this->editFileFoto->saveAs("./$path");
        }
    }
    public function updateData($sender,$param) {
        if ($this->Page->IsValid) {
            $recnopem=$this->hiddenidpemohon->Value;
            $status_pemohon=$this->rdEditStatusPemohonPerorangan->Checked==true?'perorangan':'perusahaan';
            $kode_pemohon=$this->txtEditKodePemohon->Text;
            $nama_pemohon=$this->txtEditNamaPemohon->Text;
            $no_ktp_pemohon=$this->txtEditNoKTP->Text;
            $alamat_pemohon=$this->txtEditAlamatPemohon->Text;
            $npwp_pemohon=$this->txtEditNoNPWPPemohon->Text;
            $notelp_pemohon=$this->txtEditNoTelp->Text;            
            $path_userimages=$this->editPath_userimages->Value;

            $str = "UPDATE pemohon SET RecNoPem='$kode_pemohon',NmPem='$nama_pemohon',KtpPem='$no_ktp_pemohon',AlmtPem='$alamat_pemohon',TelpPem='$notelp_pemohon',NpwpPem='$npwp_pemohon',Foto='$path_userimages',Status='$status_pemohon',DateModified=NOW() WHERE RecNoPem='$recnopem'";                    
            $this->DB->query('BEGIN');
            if ($this->DB->updateRecord($str)) {
                if ($status_pemohon=='perusahaan') {
                    $idcom=$this->hiddenidcom->Value;
                    $status_perusahaan=$this->rdEditStatusPerusahaanPusat->Checked==true?'pusat':'cabang';
                    $nama_perusahaan=$this->txtEditNamaPerusahaan->Text;
                    $no_akte=$this->txtEditNoAktePerusahaan->Text;
                    $tgl_akte=date('Y-m-d',$this->cmbEditTGLAktePendirian->TimeStamp);
                    $npwp=$this->txtEditNoNPWP->Text;
                    $alamat=$this->txtEditAlamatPerusahaan->Text;
                    $notelepon=$this->txtEditNoTelpPerusahaan->Text;
                    $nofax=$this->txtEditNoFaxPerusahaan->Text;
                    $alamatcabang=$this->txtEditAlamatCabang->Text;
                    $str = "UPDATE perusahaan SET RecNoPem='$kode_pemohon',RecStsCom='$status_perusahaan',NmCom='$nama_perusahaan',NoAkte='$no_akte',TglAkte='$tgl_akte',NPWPCom='$npwp',AlmtCom='$alamat',TelCom='$notelepon',FaxCom='$nofax',AlmtComCab='$alamatcabang' WHERE idCom=$idcom";                            
                    $this->DB->updateRecord($str);
                }
                $this->DB->query('COMMIT');
                $this->redirect('Pemohon',true);
            }else {
                $this->DB->query('ROLLBACK');
            }
        }
	}
    public function deleteRecord ($sender,$param) {
        $id=$this->getDataKeyField($sender,$this->RepeaterS);
        $this->DB->query('BEGIN');
        if ($this->DB->deleteRecord("pemohon WHERE RecNoPem='$id'")) {                                    
            $this->DB->query('COMMIT');
            $this->redirect('Pemohon',true);					
        }else{
            $this->DB->query('ROLLBACK');
        }
    }
}
?>