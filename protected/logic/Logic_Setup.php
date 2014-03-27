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
     * mengembalikan nilai durasi output cache
     */
    public function getDurationOutputCache () {
        return 86400;
    }        
}
?>