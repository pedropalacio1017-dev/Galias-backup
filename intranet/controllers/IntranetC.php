<?php
class IntranetC{

	
	#------------Carga Home
	public static function LoadC(){

		#---Seguridad: ¿Está logueado?
		$IsNotLoguedC = AccesoC::IsNotLoguedC();

		#---Secciones Activas
		$seccionesMenu = RegistrosBdM::GetSeccionesM('1');

		#---Carga vista
		include('views/home.php');
		die();

	}


	#------------Carga Seccion
	public static function SeccionC(){

		#---Seguridad: ¿Está logueado?
		$IsNotLoguedC = AccesoC::IsNotLoguedC();

		#---Secciones Activas
		$seccionesMenu = RegistrosBdM::GetSeccionesM('1');

		#---ID_seccion
		$ID_seccion = sanitize_xss($_GET['ID_seccion']);

		#---Seccion
		$seccion = RegistrosBdM::GetSeccionM($ID_seccion);

		#---Consulto Links Seccion
		$links = RegistrosBdM::GetLinksM($ID_seccion);
		//echo json_encode($links, JSON_UNESCAPED_UNICODE);die;

		#---Array Arbol
		$arbol = array();

		#---Loop para llenar Arrays de control
		foreach($links as $link){
			$categoria = $link['categoria'];
			$carpeta = $link['carpeta'];
			$arbol[$categoria][$carpeta][] = array(
				'ID_link' => $link['ID_link'],
				'linkNombre' => $link['linkNombre'],
				'linkEnlace' => $link['linkEnlace']
			);
		}
		//echo json_encode($arbol);die;

		#---Carga vista
		include('views/seccion.php');
		die();

	}


}
?>