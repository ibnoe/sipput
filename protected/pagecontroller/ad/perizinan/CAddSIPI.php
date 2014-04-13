<?php
prado::using ('Application.MainPageSA');
class CAddSIPI extends MainPageSA {    
	public function onLoad($param) {		
		parent::onLoad($param);		
        $this->showPerizinan=true;
        $this->showPermohonanBaru=true; 
        $this->createObj('DMaster');
		if (!$this->IsPostBack&&!$this->IsCallBack) {
            if (!isset($_SESSION['currentPageAddSIPI'])||$_SESSION['currentPageAddSIPI']['page_name']!='ad.dmaster.Pemohon') {
                $_SESSION['currentPageAddSIPI']=array('page_name'=>'ad.dmaster.Pemohon','page_num'=>0,'search'=>false,'iduptd'=>$this->Pengguna->getDataUser('iduptd'));	                
            } 
            $listpemohon=$this->DMaster->getListPemohon($_SESSION['currentPageAddSIPI']['iduptd']);
            $this->cmbAddPemohon->DataSource=$listpemohon;            
            $this->cmbAddPemohon->DataBind();
		}
	}
    
    public function processNextButton($sender,$param) {
        $RecNoPem=$this->cmbAddPemohon->Text;
        $this->DMaster->setRecNoPem($RecNoPem,true);  
		if ($param->CurrentStepIndex ==1) {            
            if ($this->DMaster->DataPemohon['Status']=='perorangan') {                
                $this->newpemohonwizard->ActiveStepIndex=3; 
            }else{
                $this->DMaster->setRecNoPem($RecNoPem);
                $this->cmbAddDaftarPerusahaan->DataSource=$this->DMaster->getDataPerusahaan (0);
                $this->cmbAddDaftarPerusahaan->DataBind();
            }
        }
	}
    public function changePerusahaanPemohon ($sender,$param) {
        $id=$this->cmbAddDaftarPerusahaan->Text;
        $str = "SELECT RecStsCom, NmCom, NoAkte, TglAkte, NPWPCom, AlmtCom, TelCom, FaxComp, AlmtComCab FROM perusahaan WHERE IdCom='$id'";
        $this->DB->setFieldTable(array('RecStsCom','NmCom','NoAkte','TglAkte','NPWPCom','AlmtCom','TelCom','FaxComp','AlmtComCab'));
        $r=$this->DB->getRecord($str);     
        if (isset($r[1])) {
            $this->hiddenidperusahaan->Value=$id;
            $this->lblStatusPerusahaan->Text=$r[1]['RecStsCom'];                            
            $this->lblNamaPerusahaan->Text=$r[1]['NmCom'];
            $this->lblNoAktePerusahaan->Text=$r[1]['NoAkte'];
            $this->lblTglAktePendirianPerusahaan->Text=$this->TGL->tanggal('d F Y',$r[1]['TglAkte']);
            $this->lblNoNPWPPerusahaan->Text=$r[1]['NPWPCom'];
            $this->lblAlamatPerusahaan->Text=$r[1]['AlmtCom'];
            $this->lblNoTelpPerusahaan->Text=$r[1]['TelCom'];
            $this->lblNoFaxPerusahaan->Text=$r[1]['FaxComp'];
            $this->lblAlamatKantorCabangPerusahaan->Text=$r[1]['AlmtComCab'];              
        }else {
            $this->hiddenidperusahaan->Value='';
            $this->lblStatusPerusahaan->Text='-';
            $this->lblNamaPerusahaan->Text='-';
            $this->lblNoAktePerusahaan->Text='-';
            $this->lblTglAktePendirianPerusahaan->Text='-';
            $this->lblNoNPWPPerusahaan->Text='-';
            $this->lblAlamatPerusahaan->Text='-';
            $this->lblNoTelpPerusahaan->Text='-';
            $this->lblNoFaxPerusahaan->Text='-';
            $this->lblAlamatKantorCabangPerusahaan->Text='-';
        }
    }
}
		