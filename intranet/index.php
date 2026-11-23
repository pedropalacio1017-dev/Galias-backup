<?php date_default_timezone_set('America/Bogota'); ?>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<?php
#-----Helpers
require_once('configs/configs.php');
require_once('configs/google.php');
require_once('configs/functions.php');
#-----Controladores
require_once('controllers/RouterC.php');
require_once('controllers/AccesoC.php');
require_once('controllers/GoogleC.php');
require_once('controllers/IntranetC.php');


require_once('controllers/AdminC.php');
require_once('controllers/Error404C.php');
require_once('controllers/MailerC.php');
require_once('controllers/UploadsC.php');
#-----Modelos
require_once('models/ConexionM.php');
require_once('models/RegistrosBdM.php');
require_once('models/PreparedStatementsM.php');
?>
<?php
RouterC::ControllerLoadC();
?>
