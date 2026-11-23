<?php
class SugarC{


	#------------Propiedades Constantes en jSon Request CRM Sugar
	public static function SugarValoresConstantes($usuarioEmail = NULL){

		#-----Constantes
		$sugarConstantes = array(
			'ged_paisleads_c' => 'Colombia',
			'lead_source' => 'Web Site',
			'status' => 'New',
			'd_indica_carga_c' => 'Emotica',
			'campaign_id' => '32ddb24e-41d7-5967-ff56-5cc8a834c4b3',
			//'assigned_user_id' => '97be05d8-a39b-e7ac-a149-55ea113193d7',
			'assigned_user_id' => 'no sala'
		);

		#-----Sobre-escribo con el email del agente, si es una atención de sala
		if($usuarioEmail != NULL && $usuarioEmail != ''){
			$sugarConstantes['assigned_user_id'] = $usuarioEmail;	
		}

		#-----Salida
		return $sugarConstantes;

	}


	#------------Request API Sugar
	public static function IntegracionCrmSugarC($jSonRequestSugar){

		#----Set respuesta
		$respuesta = '';

		#----Request
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://crmgalias.com/service/ws/ws_cotizador.php', 
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',                               
			CURLOPT_POSTFIELDS => encodeJsonConFlags($jSonRequestSugar)
		));
		$response = curl_exec($curl);
		$error = curl_error($curl);
		curl_close($curl);
		$respuesta = $response;
		if($error != ''){
			$respuesta = $error;
		}

		#----Salida
		return $respuesta;

	}


}
?>