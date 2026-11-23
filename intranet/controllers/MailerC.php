<?php
class MailerC{
	
	#------------Carga PHP Mailer
	public static function EnviaMailC($AsuntoMail = NULL, $ResponderA = NULL, $EnviarA = NULL, $CuerpoMail = NULL, $Adjuntos = NULL){
		//use PHPMailer\PHPMailer\PHPMailer;
		//use PHPMailer\PHPMailer\SMTP;
		//use PHPMailer\PHPMailer\Exception;
		#
		require_once('libs/phpmailer/src/PHPMailer.php');
		require_once('libs/phpmailer/src/SMTP.php');
		require_once('libs/phpmailer/src/Exception.php');
		$mail = new PHPMailer\PHPMailer\PHPMailer(true);
		#
		$mail->Mailer = 'localhost';
		$mail->FromName = MAILER_FROM_NAME;
		$mail->Subject = $AsuntoMail;
		$mail->AddReplyTo($ResponderA);
		$mail->From = MAILER_FROM;
		#
		if(MAILER_SMTP == '1'){
			//$mail->Mailer = 'smtp';
			$mail->SMTPAuth = true;
			$mail->Host = MAILER_HOST;
			$mail->Username = MAILER_USER;
			$mail->Password = MAILER_PASS;
			$mail->Port= MAILER_PORT;
			if(MAILER_SECU != ''){
				$mail->SMTPSecure = MAILER_SECU;
			}
			$mail->Timeout=30;
			//$mail->SMTPDebug = 2;
		}
		#$EnviarA es un array
		$mail->AddAddress($EnviarA[0]);
		if(count($EnviarA) > 1){
			foreach(array_slice($EnviarA,1) as $email){
				$mail->AddCC($email);	
			}
		}
		#Adjuntos
		if($Adjuntos != NULL){
			foreach($Adjuntos as $adjunto){
				$mail->AddAttachment($adjunto['tmp_name'], $adjunto['name']);
			}
		}
		#
		$mail->CharSet = 'UTF-8';
		$mail->IsHTML(true);
		$mail->Body = $CuerpoMail;
		$mail->Send();
	}

	#------------Envia con ICOMMKT
	public static function EnviaConIcommkt($AsuntoMail = NULL, $EnviarA = NULL, $CuerpoMail = NULL){

		#-----Header
		$header = array(
			'X-Trx-Api-Key: '.ICOMMKT_KEY,
			'Content-Type: application/json'
		);

		#-----Fields
		$fields = array(
			'From' => 'no-replay@notificacionesgalias.com.co',
			'ReplyTo' => 'crmnotificaciones@galias.com.co',
			'To' => $EnviarA[0],
			'Subject' => $AsuntoMail,
			'HtmlBody' => $CuerpoMail,
			'TrackOpens' => true
		);

		#-----Curl
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://api.trx.icommarketing.com/email',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => json_encode($fields),
			CURLOPT_HTTPHEADER => $header
		));
		$response = curl_exec($curl);
		curl_close($curl);

		#-----Salida
		return $response;

	}

}
?>