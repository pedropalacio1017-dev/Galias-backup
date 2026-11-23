<?php
class RegistrosBdM extends ConexionM {


	#------------------------------------Get Proyectos (Admin)
	public static function GetProyectosM(){
		#Parametros fijos
		$parameters=array(
			'ID_proyecto' => '-1'
		);
		#Sentencia SQL dinamica
		$sql = ' SELECT * ';
		$sql .= ' FROM proyectos ';
		$sql .= ' LEFT JOIN regionales ON regionales.ID_regional = proyectos.ID_regional ';
		$sql .= ' WHERE ID_proyecto NOT LIKE :ID_proyecto ';
		$sql .= ' ORDER BY proyectoNombre ASC ';
		#Query SQL
		$resultado = ConexionM::SelectAllM($sql, $parameters);
		return $resultado;	
	}


	#------------------------------------Get Proyecto 
	public static function GetProyectoM($ID_proyecto = NULL){
		#Parametros fijos
		$parameters=array(
			'ID_proyecto' => $ID_proyecto
		);
		#Sentencia SQL dinamica
		$sql = ' SELECT * ';
		$sql .= ' FROM proyectos ';
		$sql .= ' LEFT JOIN regionales ON regionales.ID_regional = proyectos.ID_regional ';
		$sql .= ' LEFT JOIN nota_legal ON nota_legal.ID_notaLegal = proyectos.ID_notaLegal ';
		$sql .= ' WHERE ID_proyecto LIKE :ID_proyecto ';
		#Query SQL
		$resultado = ConexionM::SelectM($sql, $parameters);
		return $resultado;	
	}


	#------------------------------------Get Subproyectos 
	public static function GetSubproyectosM($ID_proyecto = NULL, $proyectoActivo = NULL, $subproyectoActivo = NULL){
		#Parametros fijos
		$parameters=array(
			'ID_subproyecto' => '-1'
		);
		if($ID_proyecto != NULL){
			$parameters['ID_proyecto'] = $ID_proyecto;
		}
		if($proyectoActivo != NULL){
			$parameters['proyectoActivo'] = $proyectoActivo;
		}
		if($subproyectoActivo != NULL){
			$parameters['subproyectoActivo'] = $subproyectoActivo;
		}
		#Sentencia SQL dinamica
		$sql = ' SELECT * ';
		$sql .= ' FROM subproyectos ';
		$sql .= ' LEFT JOIN proyectos ON proyectos.ID_proyecto = subproyectos.ID_proyecto ';
		$sql .= ' LEFT JOIN regionales ON regionales.ID_regional = proyectos.ID_regional ';
		$sql .= ' WHERE ID_subproyecto NOT LIKE :ID_subproyecto ';
		if($ID_proyecto != NULL){
			$sql .= ' AND subproyectos.ID_proyecto LIKE :ID_proyecto ';
		}
		if($proyectoActivo != NULL){
			$sql .= ' AND proyectos.proyectoActivo LIKE :proyectoActivo ';
		}
		if($subproyectoActivo != NULL){
			$sql .= ' AND subproyectos.subproyectoActivo LIKE :subproyectoActivo ';
		}
		$sql .= ' ORDER BY regionales.ID_regional, proyectos.proyectoNombre, LENGTH(subproyectos.subproyectoNombre), subproyectos.subproyectoNombre ASC ';
		#Query SQL
		$resultado = ConexionM::SelectAllM($sql, $parameters);
		return $resultado;	
	}


	#------------------------------------Get Subproyecto
	public static function GetSubproyectoM($ID_subproyecto = NULL){
		#Parametros fijos
		$parameters=array(
			'ID_subproyecto' => $ID_subproyecto
		);
		#Sentencia SQL dinamica
		$sql = ' SELECT * ';
		$sql .= ' FROM subproyectos ';
		$sql .= ' WHERE ID_subproyecto LIKE :ID_subproyecto ';
		#Query SQL
		$resultado = ConexionM::SelectM($sql, $parameters);
		return $resultado;	
	}


	#------------------------------------Get Conteo Cotizaciones (Admin)
	public static function GetConteoCotizacionesM($desde = NULL, $hasta = NULL){
		#Parametros fijos
		$parameters=array(
			'ID_cotizacion' => '-1',
			'jsonCotizacion' => ''
		);
		if($desde != ''){ $parameters['desde'] = $desde; }
		if($hasta != ''){ $parameters['hasta'] = $hasta; }
		#Sentencia SQL dinamica
		$sql = ' SELECT ID_cotizacion ';
		$sql .= ' FROM cotizaciones ';
		$sql .= ' WHERE ID_cotizacion NOT LIKE :ID_cotizacion ';
		$sql .= ' AND jsonCotizacion NOT LIKE :jsonCotizacion ';
		if($desde != ''){
			$sql .= ' AND fechaCotizacion >= :desde ';
		}
		if($hasta != ''){
			$sql .= ' AND fechaCotizacion <= :hasta ';
		}
		#Query SQL
		$resultado = ConexionM::SelectAllM($sql, $parameters);
		return $resultado;
	}


	#------------------------------------Get Cotizacion
	public static function GetCotizacionM($ID_cotizacion = NULL){
		#Parametros fijos
		$parameters=array(
			'ID_cotizacion' => $ID_cotizacion
		);
		#Sentencia SQL dinamica
		$sql = ' SELECT * ';
		$sql .= ' FROM cotizaciones ';
		$sql .= ' LEFT JOIN usuarios ON usuarios.ID_usuario = cotizaciones.ID_usuario ';
		$sql .= ' WHERE ID_cotizacion LIKE :ID_cotizacion ';
		#Query SQL
		$resultado = ConexionM::SelectM($sql, $parameters);
		return $resultado;	
	}


	#------------------------------------Get Variables Cotizador 
	public static function GetRegionalesM(){
		#Parametros fijos
		$parameters=array(
			'ID_regional' => '-1'
		);
		#Sentencia SQL dinamica
		$sql = ' SELECT * ';
		$sql .= ' FROM regionales ';
		$sql .= ' WHERE ID_regional NOT LIKE :ID_regional ';
		#Query SQL
		$resultado = ConexionM::SelectAllM($sql, $parameters);
		return $resultado;	
	}	


	#------------------------------------Get Variables Cotizador 
	public static function GetVarsCotizadorM(){
		#Parametros fijos
		$parameters=array(
			'ID_variables' => '1'
		);
		#Sentencia SQL dinamica
		$sql = ' SELECT * ';
		$sql .= ' FROM variables_cotizador ';
		$sql .= ' WHERE ID_variables LIKE :ID_variables ';
		#Query SQL
		$resultado = ConexionM::SelectM($sql, $parameters);
		return $resultado;	
	}
	

	#------------------------------------Get Login
	public static function LoginM($usuarioEmail = NULL, $usuarioContrasena = NULL){
		#Parametros fijos
		$parameters=array(
			'usuarioEmail' => $usuarioEmail,
			'usuarioContrasena' => $usuarioContrasena
		);
		#Sentencia SQL dinamica
		$sql = ' SELECT * ';
		$sql .= ' FROM usuarios ';
		$sql .= ' WHERE usuarioEmail LIKE :usuarioEmail ';
		$sql .= ' AND usuarioContrasena LIKE :usuarioContrasena ';
		#Query SQL
		$resultado = ConexionM::SelectM($sql, $parameters);
		return $resultado;	
	}


	#------------------------------------Get Notas Legales
	public static function GetNotasLegalesM(){
		#Parametros fijos
		$parameters=array(
			'ID_notaLegal' => '-1'
		);
		#Sentencia SQL dinamica
		$sql = ' SELECT * ';
		$sql .= ' FROM nota_legal ';
		$sql .= ' WHERE ID_notaLegal NOT LIKE :ID_notaLegal ';
		#Query SQL
		$resultado = ConexionM::SelectAllM($sql, $parameters);
		return $resultado;
	}


	#------------------------------------Get Notas Legales
	public static function GetNotaLegalM($ID_notaLegal){
		#Parametros fijos
		$parameters=array(
			'ID_notaLegal' => $ID_notaLegal
		);
		#Sentencia SQL dinamica
		$sql = ' SELECT * ';
		$sql .= ' FROM nota_legal ';
		$sql .= ' WHERE ID_notaLegal LIKE :ID_notaLegal ';
		#Query SQL
		$resultado = ConexionM::SelectM($sql, $parameters);
		return $resultado;
	}


	#------------------------------------Get usuarios
	public static function GetUsuariosM($usuarioActivo = NULL){
		#Parametros fijos
		$parameters=array(
			'ID_usuario' => '-1'
		);
		#Sentencia SQL dinamica
		$sql = ' SELECT * ';
		$sql .= ' FROM usuarios ';
		$sql .= ' WHERE ID_usuario NOT LIKE :ID_usuario ';
		if($usuarioActivo != NULL){
			$sql .= ' AND usuarioActivo LIKE "'.$usuarioActivo.'" ';
		}
		$sql .= ' ORDER BY usuarioActivo DESC, usuarioRol DESC, usuarioNombre ASC ';
		#Query SQL
		$resultado = ConexionM::SelectAllM($sql, $parameters);
		return $resultado;
	}


	#------------------------------------Get usuario
	public static function GetUsuarioM($ID_usuario = NULL){
		#Parametros fijos
		$parameters=array(
			'ID_usuario' => $ID_usuario
		);
		#Sentencia SQL dinamica
		$sql = ' SELECT * ';
		$sql .= ' FROM usuarios ';
		$sql .= ' WHERE ID_usuario LIKE :ID_usuario ';
		#Query SQL
		$resultado = ConexionM::SelectM($sql, $parameters);
		return $resultado;
	}


	#------------------------------------Get usuario x Email
	public static function GetUsuarioPorEmailM($usuarioEmail = NULL){
		#Parametros fijos
		$parameters=array(
			'usuarioEmail' => $usuarioEmail
		);
		#Sentencia SQL dinamica
		$sql = ' SELECT * ';
		$sql .= ' FROM usuarios ';
		$sql .= ' WHERE usuarioEmail LIKE :usuarioEmail ';
		#Query SQL
		$resultado = ConexionM::SelectM($sql, $parameters);
		return $resultado;
	}
	


}
?>