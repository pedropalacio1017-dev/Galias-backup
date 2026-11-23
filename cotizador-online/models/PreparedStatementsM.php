<?php
class PreparedStatementsM extends ConexionM {


	#------------------------------------Prepared Statement INSERT
	public static function InsertM($parameters = NULL, $table = NULL){
		if($parameters != NULL && $table != NULL){
			#Sentencia SQL dinamica
			$sql='INSERT INTO '.$table.' ( ';
			foreach($parameters as $clave=>$valor){
				$sql.= $clave.', ';	
			}
			$sql=substr($sql,0,-2);
			$sql.=' ) VALUES( ';
			foreach($parameters as $clave=>$valor){
				$sql.=':'.$clave.', ';	
			}
			$sql=substr($sql,0,-2);
			$sql.=' )';
			#Query SQL
			$last_insert = ConexionM::InsertM($sql, $parameters);
			return $last_insert;
		}
	}
	
	
	#------------------------------------Prepared Statement UPDATE
	public static function UpdateM($parameters = NULL, $table = NULL, $primarykey = NULL, $primaryvalue = NULL){
		if($parameters != NULL && $table != NULL && $primarykey != NULL && $primaryvalue != NULL){
			#Sentencia SQL dinamica
			$sql=' UPDATE '.$table.' SET ';
			foreach($parameters as $clave=>$valor){
				$sql.= $clave.' =:'.$clave.', ';	
			}
			$sql=substr($sql,0,-2);
			$sql.=' WHERE '.$primarykey.' LIKE '.$primaryvalue;
			#Query SQL
			$resultado = ConexionM::UpdateM($sql, $parameters);
			return $resultado;
		}
	}
	
	
	#------------------------------------Prepared Statement DELETE
	public static function DeleteM($table = NULL, $primarykey = NULL, $primaryvalue = NULL){
		if($table != NULL && $primarykey != NULL && $primaryvalue != NULL){
			#parametro para eliminar
			$parameters=array(
				$primarykey => $primaryvalue
			);
			#Sentencia SQL dinamica
			$sql = ' DELETE FROM '.$table;
			$sql .= ' WHERE '.$primarykey;
			$sql .= ' LIKE :'.$primarykey;
			#Query SQL
			$resultado = ConexionM::DeleteM($sql, $parameters);
			return $resultado;
		}
	}
	
	
}
?>