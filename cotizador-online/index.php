<?php

#-----Timezone
date_default_timezone_set('America/Bogota');

#-----Helpers
require_once('configs/configs.php');
require_once('configs/functions.php');

#-----Controladores
require_once('controllers/AdminC.php');
require_once('controllers/CotizadorC.php');
require_once('controllers/SugarC.php');
require_once('controllers/SincoC.php');
require_once('controllers/Error404C.php');
require_once('controllers/MailerC.php');
require_once('controllers/RouterC.php');
require_once('controllers/UploadsC.php');

#-----Modelos
require_once('models/ConexionM.php');
require_once('models/RegistrosBdM.php');
require_once('models/PreparedStatementsM.php');

#-----Router
RouterC::ControllerLoadC();

?>