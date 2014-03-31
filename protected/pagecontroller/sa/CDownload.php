<?php
prado::using ('Application.MainPageSA');
class CDownload extends MainPageSA {
	public function onLoad($param) {		
		parent::onLoad($param);		            
		if (!$this->IsPostBack&&!$this->IsCallBack) {              
            if (!isset($_SESSION['currentPageDownload'])||$_SESSION['currentPageDownload']['page_name']!='sa.Download') {                                
                $_SESSION['currentPageDownload']=array('page_name'=>'sa.Download','page_num'=>0,'search'=>false);	                                
            }
            $this->populateData();
		}        
	}
    public function renderCallback ($sender,$param) {
		$this->RepeaterS->render($param->NewWriter);	
	}	
	public function Page_Changed ($sender,$param) {
		$_SESSION['currentPageDownload']['page_num']=$param->NewPageIndex;
		$this->populateData($_SESSION['currentPageDownload']['search']);
	}
    public function populateData () {
        $str = "SELECT idfiles,name,name_alias,path,size,note FROM files";
        $jumlah_baris=$this->DB->getCountRowsOfTable ('files','idfiles');			
        $this->RepeaterS->CurrentPageIndex=$_SESSION['currentPageDownload']['page_num'];		
		$this->RepeaterS->VirtualItemCount=$jumlah_baris;
		$currentPage=$this->RepeaterS->CurrentPageIndex;
		$offset=$currentPage*$this->RepeaterS->PageSize;		
		$itemcount=$this->RepeaterS->VirtualItemCount;
		$limit=$this->RepeaterS->PageSize;
		if (($offset+$limit)>$itemcount) {
			$limit=$itemcount-$offset;
		}
		if ($limit <= 0) {$offset=0;$limit=10;$_SESSION['currentPageUPDT']['page_num']=0;}
        $str = "$str LIMIT $offset,$limit";        
		$this->DB->setFieldTable(array('idfiles','name','name_alias','path','size','note'));
		$r=$this->DB->getRecord($str,$offset+1);        
		$this->RepeaterS->DataSource=$r;
		$this->RepeaterS->dataBind();      		
    }
    public function saveData($sender,$param) {
        if ($this->IsValid) {           
            if ($this->File1->HasFile) {      
                $name_alias=  addslashes($this->txtAddKeterangan->Text);
                $note= strip_tags(addslashes($this->txtAddKeterangan->Text));
                $name=$this->File1->FileName;
                $part=$this->setup->cleanFileNameString($name);
                $size=$this->File1->FileSize;
                $format=$this->File1->FileType;
                $path=$this->setup->getSettingValue('dir_files')."/$part";
                $this->File1->saveAs("./$path");                
                $str = "INSERT INTO files (idfiles,name,name_alias,part,path,format,size,note) VALUES (NULL,'$name','$name_alias','$part','$path','$format','$size','$note')";
                $this->DB->insertRecord($str);                     
                $this->redirect('Download',true);
            }
        }
    }
    public function editRecord ($sender,$param) {		
		$this->idProcess='edit';
		$idfiles=$this->getDataKeyField($sender,$this->RepeaterS);
		$this->hiddenidfiles->Value=$idfiles;
        $str = "SELECT name_alias,note FROM files WHERE idfiles='$idfiles'";
        $this->DB->setFieldTable(array('name_alias','note'));
        $r=$this->DB->getRecord($str);    
		$result = $r[1];        				
		$this->txtEditNamaFile->Text=$result['name_alias'];		
		$this->txtEditKeterangan->Text=$result['note'];		        		
    }
    public function updateData($sender,$param) {
        if ($this->IsValid) {                       
            $idfiles=$this->hiddenidfiles->Value;
            $name_alias=  addslashes($this->txtEditNamaFile->Text);
            $note= strip_tags(addslashes($this->txtEditKeterangan->Text));            
            $str = "UPDATE files SET name_alias='$name_alias',note='$note' WHERE idfiles=$idfiles";
            $this->DB->updateRecord($str);                     
            $this->redirect('Download',true);           
        }
    }
    public function deleteRecord ($sender,$param) {
        $idfiles=$this->getDataKeyField($sender,$this->RepeaterS);
        $nama_files=$sender->CommandParameter;        
        if ($this->DB->deleteRecord("files WHERE idfiles='$idfiles'")) {                                                            
            $this->setup->totalDelete($nama_files);
            $this->redirect('Download',true);					
        }
    }
}
?>		