<?php
class RegistrosBdM extends ConexionM {


	#------------------------------------Get Empleados
	public static function GetEmpleadosM(){
		#Parametros fijos
		$parameters=array(
			'ID_empleado' => '-1'
		);
		#Sentencia SQL dinamica
		$sql = ' SELECT * ';
		$sql .= ' FROM empleados ';
		$sql .= ' WHERE ID_empleado NOT LIKE :ID_empleado ';
		$sql .= ' AND empleadoDocumento NOT LIKE "98623455" ';
		$sql .= ' ORDER BY ID_empleado ASC ';
		#Query SQL
		$resultado = ConexionM::SelectAllM($sql, $parameters);
		return $resultado;	
	}
	

	#------------------------------------Empleado Login  
	public static function GetUserLoginM($empleadoEmail = NULL, $empleadoPassword = NULL){
		#Parametros fijos
		$parameters=array(
			'empleadoEmail' => $empleadoEmail,
			'empleadoPassword' => $empleadoPassword
		);
		#Sentencia SQL dinamica
		$sql = ' SELECT * ';
		$sql .= ' FROM empleados ';
		$sql .= ' WHERE empleadoEmail LIKE :empleadoEmail ';
		$sql .= ' AND empleadoPassword LIKE :empleadoPassword ';
		#Query SQL
		$resultado = ConexionM::SelectM($sql, $parameters);
		return $resultado;	
	}


	#------------------------------------Get Administradores
	public static function GetAdministradoresM(){
		#Parametros fijos
		$parameters=array(
			'ID_admin' => '-1'
		);
		#Sentencia SQL dinamica
		$sql = ' SELECT
		administradores.*,
		empleados.empleadoNombre
		';
		$sql .= ' FROM administradores ';
		$sql .= ' LEFT JOIN empleados ON empleados.empleadoDocumento = administradores.empleadoDocumento ';
		$sql .= ' WHERE ID_admin NOT LIKE :ID_admin ';
		$sql .= ' AND administradores.empleadoDocumento NOT LIKE "98623455" ';
		$sql .= ' ORDER BY empleadoNombre ASC ';
		#Query SQL
		$resultado = ConexionM::SelectAllM($sql, $parameters);
		return $resultado;	
	}


	#------------------------------------Administrador (x empleadoDocumento)  
	public static function GetPermisoAdminM($empleadoDocumento = NULL){
		#Parametros fijos
		$parameters=array(
			'empleadoDocumento' => $empleadoDocumento
		);
		#Sentencia SQL dinamica
		$sql = ' SELECT * ';
		$sql .= ' FROM administradores ';
		$sql .= ' WHERE empleadoDocumento LIKE :empleadoDocumento ';
		#Query SQL
		$resultado = ConexionM::SelectM($sql, $parameters);
		return $resultado;	
	}


	#------------------------------------Get Secciones
	public static function GetSeccionesM($seccionActiva = NULL){
		#Parametros fijos
		$parameters=array(
			'ID_seccion' => '-1'
		);
		#Sentencia SQL dinamica
		$sql = ' SELECT * ';
		$sql .= ' FROM secciones ';
		$sql .= ' WHERE ID_seccion NOT LIKE :ID_seccion ';
		if($seccionActiva != NULL){
			$sql .= ' AND seccionActiva LIKE "1" ';
		}
		$sql .= ' ORDER BY ID_seccion ASC ';
		#Query SQL
		$resultado = ConexionM::SelectAllM($sql, $parameters);
		return $resultado;	
	}


	#------------------------------------Get Seccion
	public static function GetSeccionM($ID_seccion = NULL){
		#Parametros fijos
		$parameters=array(
			'ID_seccion' => $ID_seccion
		);
		#Sentencia SQL dinamica
		$sql = ' SELECT * ';
		$sql .= ' FROM secciones ';
		$sql .= ' WHERE ID_seccion LIKE :ID_seccion ';
		#Query SQL
		$resultado = ConexionM::SelectM($sql, $parameters);
		return $resultado;	
	}


	#------------------------------------Get Categorias
	public static function GetCategoriasM(){
		#Parametros fijos
		$parameters=array(
			'ID_categoria' => '-1'
		);
		#Sentencia SQL dinamica
		$sql = ' SELECT * ';
		$sql .= ' FROM categorias ';
		$sql .= ' LEFT JOIN secciones ON categorias.ID_seccion = secciones.ID_seccion ';
		$sql .= ' WHERE ID_categoria NOT LIKE :ID_categoria ';
		$sql .= ' AND seccionActiva NOT LIKE "0" ';
		$sql .= ' ORDER BY categorias.ID_seccion, categoria ASC ';
		#Query SQL
		$resultado = ConexionM::SelectAllM($sql, $parameters);
		return $resultado;	
	}


	#------------------------------------Get Categoria
	public static function GetCategoriaM($ID_categoria = NULL){
		#Parametros fijos
		$parameters=array(
			'ID_categoria' => $ID_categoria
		);
		#Sentencia SQL dinamica
		$sql = ' SELECT * ';
		$sql .= ' FROM categorias ';
		$sql .= ' LEFT JOIN secciones ON categorias.ID_seccion = secciones.ID_seccion ';
		$sql .= ' WHERE ID_categoria LIKE :ID_categoria ';
		#Query SQL
		$resultado = ConexionM::SelectM($sql, $parameters);
		return $resultado;	
	}


	#------------------------------------Get Carpetas
	public static function GetCarpetasM(){
		#Parametros fijos
		$parameters=array(
			'ID_carpeta' => '-1'
		);
		#Sentencia SQL dinamica
		$sql = ' SELECT * ';
		$sql .= ' FROM carpetas ';
		$sql .= ' LEFT JOIN categorias ON carpetas.ID_categoria = categorias.ID_categoria ';
		$sql .= ' LEFT JOIN secciones ON carpetas.ID_seccion = secciones.ID_seccion ';
		$sql .= ' WHERE ID_carpeta NOT LIKE :ID_carpeta ';
		$sql .= ' AND seccionActiva NOT LIKE "0" ';
		$sql .= ' ORDER BY categorias.ID_seccion, categoria, carpeta ASC ';
		#Query SQL
		$resultado = ConexionM::SelectAllM($sql, $parameters);
		return $resultado;	
	}


	#------------------------------------Get Carpeta
	public static function GetCarpetaM($ID_carpeta = NULL){
		#Parametros fijos
		$parameters=array(
			'ID_carpeta' => $ID_carpeta
		);
		#Sentencia SQL dinamica
		$sql = ' SELECT * ';
		$sql .= ' FROM carpetas ';
		$sql .= ' LEFT JOIN categorias ON carpetas.ID_categoria = categorias.ID_categoria ';
		$sql .= ' LEFT JOIN secciones ON carpetas.ID_seccion = secciones.ID_seccion ';
		$sql .= ' WHERE ID_carpeta LIKE :ID_carpeta ';
		#Query SQL
		$resultado = ConexionM::SelectM($sql, $parameters);
		return $resultado;	
	}


	#------------------------------------Get Links
	public static function GetLinksM($ID_seccion = NULL){
		#Parametros fijos
		$parameters=array(
			'ID_link' => '-1'
		);
		if($ID_seccion != NULL){
			$parameters['ID_seccion'] = $ID_seccion;	
		}
		#Sentencia SQL dinamica
		$sql = ' SELECT
		seccion, 
		categoria,
		carpeta,
		intranet_links.*
		';
		$sql .= ' FROM intranet_links ';
		$sql .= ' LEFT JOIN carpetas ON intranet_links.ID_carpeta = carpetas.ID_carpeta ';
		$sql .= ' LEFT JOIN categorias ON intranet_links.ID_categoria = categorias.ID_categoria ';
		$sql .= ' LEFT JOIN secciones ON intranet_links.ID_seccion = secciones.ID_seccion ';
		$sql .= ' WHERE ID_link NOT LIKE :ID_link ';
		if($ID_seccion != NULL){
			$sql .= ' AND intranet_links.ID_seccion LIKE :ID_seccion ';	
		}
		$sql .= ' AND seccionActiva NOT LIKE "0" ';
		$sql .= ' ORDER BY ID_seccion, categoria, carpeta, linkNombre ASC ';
		#Query SQL
		$resultado = ConexionM::SelectAllM($sql, $parameters);
		return $resultado;	
	}


	#------------------------------------Get Link
	public static function GetLinkM($ID_link = NULL){
		#Parametros fijos
		$parameters=array(
			'ID_link' => $ID_link
		);
		#Sentencia SQL dinamica
		$sql = ' SELECT
		seccion, 
		categoria,
		carpeta,
		intranet_links.*
		';
		$sql .= ' FROM intranet_links ';
		$sql .= ' LEFT JOIN carpetas ON intranet_links.ID_carpeta = carpetas.ID_carpeta ';
		$sql .= ' LEFT JOIN categorias ON intranet_links.ID_categoria = categorias.ID_categoria ';
		$sql .= ' LEFT JOIN secciones ON intranet_links.ID_seccion = secciones.ID_seccion ';
		$sql .= ' WHERE ID_link LIKE :ID_link ';
		#Query SQL
		$resultado = ConexionM::SelectM($sql, $parameters);
		return $resultado;	
	}


}
?>