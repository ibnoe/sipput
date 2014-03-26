<?php
define ('BASEPATH',dirname(__FILE__).DIRECTORY_SEPARATOR);
$framework='framework_323/pradolite.php';
require_once ($framework);
require_once(BASEPATH."protected/lib/dompdf/dompdf_config.inc.php");

spl_autoload_unregister(array('Prado','autoload'));
spl_autoload_register('DOMPDF_autoload');
spl_autoload_register(array('Prado','autoload'));

$application = new TApplication;
$application->run();
?>
