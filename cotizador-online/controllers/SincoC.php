<?php
class SincoC{


	#-----Consumo Endpoint API
	public static function ConsumosApi($endpoint = NULL, $header = NULL, $method = NULL, $postfields = NULL){

		#----Set Opciones Curl
		$opcionesCurl = array(
			CURLOPT_URL => $endpoint,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => $method
		);

		#----Se definió algun Header
		if($header != NULL){
			$opcionesCurl[CURLOPT_HTTPHEADER] = $header;   
		}

		#----Tiene Body (postfields)
		if($postfields != NULL){
			$opcionesCurl[CURLOPT_POSTFIELDS] = encodeJsonConFlags($postfields);
		}

		#----Curl
		$curl = curl_init();
		curl_setopt_array($curl, $opcionesCurl);
		$response = curl_exec($curl);
		$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);

		#-----Hubo error
		if($httpCode != 200 && $httpCode != 300){ 
			$response = encodeJsonConFlags( array( 'error' => $response ));
		}

		#-----Salida
		return $response;
		die;

	}


	#------------Autenticacion Genero Token de Consumo
	public static function AutenticacionC(){

		#---Usuario y Clave
		$credenciales = array(
			'NomUsuario' => SINCO_API_NOMBRE,
			'ClaveUsuario' => SINCO_API_PASS
		);

		#---Header
		$header = array(
			'Content-Type: application/json; charset=utf-8',
			'Content-Length: '.strlen(encodeJsonConFlags($credenciales))	
		);

		#---Endpoint
		$endpoint = SINCO_API_URL.'/API/Auth/Usuario';

		#---Consumo API
		$response = SincoC::ConsumosApi($endpoint, $header, 'POST', $credenciales);
		//echo $response; die;

		#---Convierto a array PHP
		$result = json_decode($response, TRUE);

		#---Evalúo si hubo error
		if(isset($result['error'])){
			$token = array(
				'error' => $result['error']	
			);
		}

		#---OK
		else{
			$token = array(
				'token_type' => $result['token_type'],
				'access_token' =>$result['access_token']
			);
		}

		#---Salida
		return $token;
		die;		
		
	}


	#------------Envío Visita
	public static function SalaVentasExternoVisitasC($postfields = NULL){

		#----Set Response
		$response = array();

		#----Genero Token
		$token = SincoC::AutenticacionC();

		#---Evalúo si hubo error
		if(isset($token['error'])){
			$response = array(
				'error' => $token['error']	
			);
		}

		#---token OK
		else {

			#----Header
			$header = array(
				'Content-Type: application/json; charset=utf-8',
				'Authorization: '.$token['token_type'].' '.$token['access_token']
			);

			#---Endpoint
			$endpoint = SINCO_API_URL.'/CBRClientes/API/SalaVentas​/Externo​/Visitas';

			#---Consumo API
			$SalaVentasExternoVisitasC = SincoC::ConsumosApi($endpoint, $header, 'POST', $postfields);

			#---Convierto a array PHP
			$result = json_decode($SalaVentasExternoVisitasC, TRUE);

			#---Respuesta Sin Error
			if(!isset($result['error'])){
				$response = array(
					'message' => $result
				);	
			}

		}		

		#-----Salida
		return $response;
		die;

	}



}
?>