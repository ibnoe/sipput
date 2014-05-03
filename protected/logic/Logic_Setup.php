<?php
/**
*
* digunakan untuk memproses setup aplikasi
*
*/
prado::using ('Application.logic.Logic_Global');
class Logic_Setup extends Logic_Global {   
    /**
     *
     * setting application
     */
    private $settings;
    /**
     *
     * file parameters xpath
     */
    private $parameters;
	public function __construct ($db) {
		parent::__construct ($db);	        
        $this->loadSetting();        
        $this->parameters=$this->Application->getParameters ();	
	}	
    /**
     * digunakan untuk meload setting
     */
    public function loadSetting ($flush=false) {     
        if ($flush) {
            $this->settings=$this->populateSetting ();
            $this->settings['loaded']=true;
            if ($this->Application->Cache) {                
                $this->Application->Cache->set('settings',$this->settings);
            }else {
                $_SESSION['settings']=$this->settings;                
            }
        }elseif ($this->Application->Cache) {
            $this->settings=$this->Application->Cache->get('settings');
            if (!$this->settings['loaded']) $this->loadSetting (true);
        }else {
            $this->settings=$_SESSION['settings'];
            if (!$this->settings['loaded']) $this->loadSetting (true);
        }        
    }
    /**
     * digunakan untuk populate setting
     */
    private function populateSetting () {
        $str = 'SELECT setting_id,`key`,`value` FROM setting';
        $this->db->setFieldTable(array('setting_id','key','value'));
        $r=$this->db->getRecord($str);
        $result=array();
        while (list($k,$v)=each($r)) {
            $result[$v['key']]=array('setting_id'=>$v['setting_id'],'key'=>$v['key'],'value'=>$v['value']);
        }
        return $result;
    }
    /**
     * digunakan untuk mendapat nilai setting
     * @param type $mode
     * @return type
     */
    public function getSettingValue($keys,$mode='value') {  
        $value=$this->settings[$keys][$mode];
        if ($value=='') {            
            $this->loadSetting (true);
            $value=$this->settings[$keys][$mode];
        }        
        return $value; 
    }    
    /**
     * digunakan untuk mendapatkan alamat aplikasi
     * 
     */
    public function getAddress () {       
		$ip=explode('.',$_SERVER['REMOTE_ADDR']);		
		$ipaddress=$ip[0];	       	
		if ($ipaddress == '127' || $ipaddress == '::1') {
			$url=$this->parameters['address_lokal'];
		}elseif ($ipaddress == '192' || $ip=='10'||$ip=='172'){
			$url=$this->parameters['address_lan'];
		}else {
			$url=$this->parameters['address_internet'];
		}				
		return $url;
    }
    /**
     * digunakan untuk memperoleh ukuran maksimal file
     */
    public function getMaxFileSize () {
        $size=(int)ini_get('upload_max_filesize');        
        $filesize=$size*1048576;
        return $filesize;
    }
    /**
     * digunakan untuk mengatur format ukuran file
     * @param type $bytes
     * @return string
     */
    function formatSizeUnits($bytes)    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
    /**
     * digunakan untuk membersihkan nama file dari string non alphanumerik
     * @param type $string
     */
    public function cleanFileNameString ($filename) {
        $data=explode ('.',$filename);           
        $extensi=$data[count($data)-1];        
        $replacefile=preg_replace('/\W+/', '_', $filename);
        $files= str_replace($extensi, '', $replacefile);
        return "$files.$extensi";
        
    }
    /**
     * digunakan untuk menghapus file atau direktori
     * @param type $arg
     */
    public function totalDelete($arg) {
        if (file_exists($arg)) {
            @chmod($arg,0755);
            if (is_dir($arg)) {
                $handle = opendir($arg);
                while($aux = readdir($handle)) {
                    if ($aux != "." && $aux != "..") $this->totalDelete($arg."/".$aux);
                }
                @closedir($handle);
                rmdir($arg);
            } else unlink($arg);
        }
    }
    /**
     * digunakan untuk membuat direktori baru
     * @param type $dirname
     */
    public function createNewDir ($dirname) {
        $dirname = BASEPATH . $this->baseFolder . $dirname;
        if (!is_dir($dirname)) {           
            mkdir($dirname,0755);
            chmod($dirname,0755);
        }
    }
    /**
     * mengembalikan nilai durasi output cache
     */
    public function getDurationOutputCache () {
        return 86400;
    }        
    /**
	* casting ke integer	
	*/
	public function toInteger ($stringNumeric) {
		return str_replace('.','',$stringNumeric);
	}
	/**
	* Untuk mendapatkan uang dalam format rupiah
	* @param angka	
	* @return string dalam rupiah
	*/
	public function toRupiah($angka,$tanpa_rp=true)  {
		if ($angka == '') {
			$angka=0;
		}
		$rupiah='';
        $angka=explode('.',$angka);
        $bilangan=$angka[0];     
        $pecahan=($angka[1]==''||$angka[1]=='00')?'':','.$angka[1];
		$rp=strlen($bilangan);
		while ($rp>3){
			$rupiah = ".". substr($bilangan,-3). $rupiah;
			$s=strlen($bilangan) - 3;
			$bilangan=substr($bilangan,0,$s);
			$rp=strlen($bilangan);
		}
		if ($tanpa_rp) {
			$rupiah = $bilangan . $rupiah.$pecahan;
		}else {
			$rupiah = "Rp. " . $bilangan . $rupiah.$pecahan;
		}
		return $rupiah;
	}
}
?>