<?php
prado::using ('Application.logic.Logic_Global');
class Logic_Report extends Logic_Global {	
    /**
	* mode dari driver
	*
	*/
	private $driver;
	/**
	* object dari driver2 report misalnya PHPExcel, TCPDF, dll.
	*
	*/
	public $rpt;	
    /**
	* object setup;	
	*/
	public $setup;	
    /**
	* object tanggal;	
	*/
    public $tgl;    	
	/**
	* Exported Dir
	*
	*/
	private $exportedDir;	
	/**
	* posisi row sekarang
	*
	*/
	public $currentRow=1;		
    /**
     * 
     * data report	
	*/
	public $dataReport;	
	public function __construct ($db) {
		parent::__construct ($db);	
        $this->setup = $this->getLogic ('Setup');
		$this->tgl = $this->getLogic ('Penanggalan');
	}		
    /**
     * digunakan untuk mengeset data report
     * @param type $dataReport
     */
    public function setDataReport ($dataReport) {
        $this->dataReport=$dataReport;
    }
    /**
	*
	* set mode driver
	*/
	public function setMode ($driver) {
		$this->driver = $driver;
		$path = dirname($this->getPath()).'/';								
		$host=$this->setup->getAddress().'/';				
		switch ($driver) {
            case 'excel2003' :								
                $phpexcel=BASEPATH.'protected/lib/excel/';
                define ('PHPEXCEL_ROOT',$phpexcel);
                set_include_path(get_include_path() . PATH_SEPARATOR . $phpexcel);
                
                require_once ('PHPExcel.php');                
				$this->rpt=new PHPExcel();
                $this->exportedDir['excel_path']=$host.'exported/excel/';
				$this->exportedDir['full_path']=$path.'exported/excel/';
			break;
			case 'excel2007' :							
                //phpexcel
                $phpexcel=BASEPATH.'protected/lib/excel/';
                define ('PHPEXCEL_ROOT',$phpexcel);
                set_include_path(get_include_path() . PATH_SEPARATOR . $phpexcel);
                
                require_once ('PHPExcel.php');
				$this->rpt=new PHPExcel();
				$this->exportedDir['excel_path']=$host.'exported/excel/';
				$this->exportedDir['full_path']=$path.'exported/excel/';
			break;					
            case 'pdf' :				
                require_once (BASEPATH.'protected/lib/tcpdf/tcpdf.php');
				$this->rpt=new TCPDF();			
				$this->rpt->setCreator ($this->Application->getID());
				$this->rpt->setAuthor ($this->setup->getSettingValue('config_name'));
				$this->rpt->setPrintHeader(false);
				$this->rpt->setPrintFooter(false);				
				$this->exportedDir['pdf_path']=$host.'exported/pdf/';	
				$this->exportedDir['full_path']=$path.'exported/pdf/';
			break;	
		}
	}
    /**
     * digunakan untuk mendapatkan driver saat ini
     */
	public function getDriver () {
        return $this->driver;
    }
    /**
	* set header logo;
	*
	*/
	public function setHeaderLogo () {
		$headerLogo=BASEPATH.$this->setup->getSettingValue('config_logo');       
		switch ($this->driver) {
            case 'excel2003' :
                //drawing
				$drawing = new PHPExcel_Worksheet_Drawing();		
				$drawing->setName('Logo');
				$drawing->setDescription('Logo');			
				
				$drawing->setPath($headerLogo);
				$drawing->setHeight(90);
				$drawing->setCoordinates('A'.$this->currentRow);
				$drawing->setOffsetX(90);
				$drawing->setRotation(25);
				$drawing->getShadow()->setVisible(true);
				$drawing->getShadow()->setDirection(45);
				$drawing->setWorksheet($this->rpt->getActiveSheet());
            break;
			case 'excel2007' :
				//drawing
				$drawing = new PHPExcel_Worksheet_Drawing();		
				$drawing->setName('Logo');
				$drawing->setDescription('Logo');			
				
				$drawing->setPath($headerLogo);
				$drawing->setHeight(90);
				$drawing->setCoordinates('A'.$this->currentRow);
				$drawing->setOffsetX(10);
				$drawing->setRotation(0);
				$drawing->getShadow()->setVisible(true);
				$drawing->getShadow()->setDirection(45);
				$drawing->setWorksheet($this->rpt->getActiveSheet());
			break;			            
		}		
	}
    /**
	* digunakan untuk mencetak header 
	*
	*/
	public function setHeaderSertifikat ($nama_sertifikat,$endColumn=null,$alignment=null,$columnHeader='C') {			
        $headerLogo=BASEPATH.$this->setup->getSettingValue('config_logo');
        $nama_dinas=strtoupper($this->setup->getSettingValue('config_nama_dinas'));
        $nama_kabupaten=  strtoupper($this->setup->getSettingValue('config_nama_kabupaten'));
		switch ($this->driver) {
			case 'excel2003' :
			case 'excel2007' :	
                //cetak logo
                $this->setHeaderLogo();				
				$row=1;
				$this->rpt->getActiveSheet()->getRowDimension($row)->setRowHeight(18);
				$this->rpt->getActiveSheet()->mergeCells ($columnHeader.$row.':'.$endColumn.$row);
				$this->rpt->getActiveSheet()->setCellValue($columnHeader.$row,'PEMERINTAH KABUPATEN BINTAN');
				
				$row+=1;
				$this->rpt->getActiveSheet()->getRowDimension($row)->setRowHeight(18);
				$this->rpt->getActiveSheet()->mergeCells ($columnHeader.$row.':'.$endColumn.$row);
				$this->rpt->getActiveSheet()->setCellValue($columnHeader.$row,'DINAS PERIKANAN');
				
				$row+=1;
				$this->rpt->getActiveSheet()->getRowDimension($row)->setRowHeight(18);
				$this->rpt->getActiveSheet()->mergeCells ($columnHeader.$row.':'.$endColumn.$row);
				$this->rpt->getActiveSheet()->setCellValue($columnHeader.$row,'Jl. ABC SRI BINTAN BUYU');
				
				$row+=1;
				$this->rpt->getActiveSheet()->getRowDimension($row)->setRowHeight(18);
				$this->rpt->getActiveSheet()->mergeCells ($columnHeader.$row.':'.$endColumn.$row);
				$this->rpt->getActiveSheet()->setCellValue($columnHeader.$row,'');
								
				$this->rpt->getActiveSheet()->getStyle($columnHeader.($row-3))->getFont()->setSize('10');
				$this->rpt->getActiveSheet()->getStyle($columnHeader.($row-2))->getFont()->setSize('12');	
				$this->rpt->getActiveSheet()->getStyle($columnHeader.($row-1))->getFont()->setSize('10');				
				$this->rpt->getActiveSheet()->getStyle($columnHeader.$row)->getFont()->setSize('10');
				
				
				$this->rpt->getActiveSheet()->duplicateStyleArray(array(
												'font' => array('bold' => true,
                                                                'size' =>14),
												'alignment' => array('horizontal'=>$alignment,
														'vertical'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER)					   	
												),
												$columnHeader.$this->currentRow.':'.$columnHeader.$row
											);				
				$this->currentRow=$row;
			break;	
            case 'pdf' :                              
                $this->rpt->AddPage();
                
                $row=6;
                $this->rpt->SetFont ('helvetica','B',10);
                $this->rpt->setXY(5,$row);
                $this->rpt->Cell (20,5,$nama_sertifikat,0,0,'L');
                $this->rpt->Cell (180,5,$nama_sertifikat,0,0,'R');
                
                $row+=15;
                $this->rpt->Image($headerLogo,90,$row,34,27); 
                
                $row+=28;
                $this->rpt->SetFont ('helvetica','B',12);
                $this->rpt->setXY(3,$row);
                $this->rpt->Cell (203,5,$nama_dinas,0,0,'C');
                $row+=5;
                $this->rpt->setXY(3,$row);
                $this->rpt->Cell (203,5,$nama_kabupaten,0,0,'C');
                
                $this->currentRow=64;
				
			break;
		}		
	}	
    /**
	* digunakan untuk mencetak laporan
	*
	*/
	public function printOut ($filename) {	
		$filename_to_write =$filename.'_'.date('Y_m_d_H_m_s');	
// 		$filename_to_write =$filename.'_';		//uncoment this line, if you in debug process        
		switch ($this->driver) {
			case 'excel2003' :
                //$writer=new PHPExcel_Writer_Excel5($this->rpt);								
                $writer=PHPExcel_IOFactory::createWriter($this->rpt, 'Excel5');
				$filename_to_write = $filename_to_write . '.xls';
				$writer->save ($this->exportedDir['full_path'].$filename_to_write);		
				$this->exportedDir['filename']=$filename;
				$this->exportedDir['excel_path'].=$filename_to_write;		
            break;
			case 'excel2007' :
				$writer=PHPExcel_IOFactory::createWriter($this->rpt, 'Excel2007');
				$filename_to_write = $filename_to_write . '.xlsx';
				$writer->save ($this->exportedDir['full_path'].$filename_to_write);		
				$this->exportedDir['filename']=$filename;
				$this->exportedDir['excel_path'].=$filename_to_write;		
			break;	
            case 'pdf' :
				$filename_to_write=$filename_to_write.'.pdf';
				$this->rpt->output ($this->exportedDir['full_path'].$filename_to_write,'F');
				$this->exportedDir['filename']=$filename;
				$this->exportedDir['pdf_path'].=$filename_to_write;		
			break;
		}
	}    
    /**
	* digunakan untuk mendapatkan link ke sebuah file hasil dari export	
	* @param obj_out object 
	* @param text in override text result
	*/
	public function setLink ($obj_out,$text='') {
		$filename=$text==''?$this->exportedDir['filename']:$text;		        
		switch ($this->driver) {
			case 'excel2003' :
                $obj_out->Text = "$filename.xls";
				$obj_out->NavigateUrl=$this->exportedDir['excel_path'];				
            break;
			case 'excel2007' :                
				$obj_out->Text = "$filename.xlsx";
				$obj_out->NavigateUrl=$this->exportedDir['excel_path'];				
			break;	
            case 'pdf' :
				$obj_out->Text = "$filename.pdf";
				$obj_out->NavigateUrl=$this->exportedDir['pdf_path'];	
			break;
		}
	}   
}
?>