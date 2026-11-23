<?php
class Error404C{
	
	#------------Carga Error
	public static function LoadC(){

		#---Seguridad: ¿Está logueado?
		$IsNotLoguedC = AccesoC::IsNotLoguedC();

		#---Secciones Activas
		$seccionesMenu = RegistrosBdM::GetSeccionesM('1');

		#---Carga vista
		include('views/error404.php');
		die();


	}

}
?>