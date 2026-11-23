<?php
class RouterC{
	
	#------------Carga Controlador según URL
	public static function ControllerLoadC(){
		#-----------Defino variable Controlador
		$controller = 'CotizadorC';
		if(isset($_GET['controller']) && $_GET['controller'] !=''){
			$controller = $_GET['controller'];	
		}
		#-----------Defino variable Método
		$method = 'LoadC';
		if(isset($_GET['method']) && $_GET['method'] !=''){
			$method = $_GET['method'];
		}
		#-----------Instancia
		$ControllerIncludeC = new $controller;
		$ControllerIncludeC->$method();
	}


}
?>