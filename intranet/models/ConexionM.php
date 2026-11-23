<?php
class ConexionM {
	
	#------------Conexion a la BD (credenciales son constantes definidas en CONFIG)
	public static function PdoM() {
		try {
			$bd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8', DB_USER, DB_PASS);
			$bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			//die('conexion hecha');
			return $bd;
		} catch (PDOException $e){
			die('Error: '. $e->getMessage() );
		}
	}
	
	#------------PDO Prepare & Execute SELECT varios rows
	public static function SelectAllM($sql, $parameters){
		$query = ConexionM::PdoM()->prepare($sql);
		$query->execute($parameters);
		$resultados = $query->fetchAll(PDO::FETCH_ASSOC);
		$query = null;
		return $resultados;
	}
	
	#------------PDO Prepare & Execute SELECT row especifico
	public static function SelectM($sql, $parameters){
		$query = ConexionM::PdoM()->prepare($sql);
		$query ->execute($parameters);
		$resultado = $query->fetch(PDO::FETCH_ASSOC);
		$query = null;
		return $resultado;	
	}
	
	#------------PDO Prepare & Execute INSERT
	public static function InsertM($sql, $parameters){
    		$bd = ConexionM::PdoM();
    		$query = $bd->prepare($sql);
    		$query ->execute($parameters);
    		$last_insert = $bd->lastInsertId();
    		$query = null;
    		return $last_insert;
	}


	
	#------------PDO Prepare & Execute UPDATE
	public static function UpdateM($sql, $parameters){
		$query = ConexionM::PdoM()->prepare($sql);
		$query ->execute($parameters);
		$resultado = $query ->rowCount();
		$query = null;
		return $resultado;
	}
	
	#------------PDO Prepare & Execute DELETE
	public static function DeleteM($sql, $parameters){
		$query = ConexionM::PdoM()->prepare($sql);
		$query ->execute($parameters);
		$resultado = $query ->rowCount();
		$query = null;
		return $resultado;
	}
	
	#------------PDO Truncate
	public static function TruncateM($sql){
		$query = ConexionM::PdoM()->prepare($sql);
		$query->execute();		
		$resultado = $query->rowCount();
		$query = null;
		return $resultado;
	}
		
}
?>
