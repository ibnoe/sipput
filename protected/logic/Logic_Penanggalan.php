<?php
/**
*
* digunakan untuk memproses tanggal
*
*/
prado::using ('Application.logic.Logic_Global');
class Logic_Penanggalan extends Logic_Global {
    /*
     * nama hari dalam bahasa ingris
     */
    private $dayName = array('Sunday', 'Monday', 'Tuesday','Wednesday', 'Thursday', 'Friday', 'Saturday');
    /*
     * nama hari dalam bahasa indonesia
     */
    private $namaHari = array('Minggu', 'Senin', 'Selasa','Rabu', 'Kamis', 'Jumat', 'Sabtu');
    /*
     * nama bulan dalam bahasa ingris
     */
    private $monthName = array('January', 'February', 'March', 'April', 'May','June', 'July', 'August', 'September', 'October', 'November' , 'December');
    /*
     * nama bulan dalam bahasa indonesia
     */
    private $namaBulan = array('Januari', 'Februari', 'Maret', 'April', 'Mei','Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
    /**
     * digunakan untuk memformat tanggal
     * @param type $format
     * @param type $date
     * @return type date
     */
    public function tanggal($format, $date=null) {
        if (is_object($date)){            
            $tgl=$date;
        }else {
            if ($date === null)
                $tgl = new DateTime ('now',new DateTimeZone('Asia/Jakarta'));
            else
                $tgl = new DateTime ($date,new DateTimeZone('Asia/Jakarta'));
        }		
        $result = str_replace($this->dayName, $this->namaHari, $tgl->format ($format));
        return str_replace($this->monthName, $this->namaBulan, $result);
	}   	 
}
?>