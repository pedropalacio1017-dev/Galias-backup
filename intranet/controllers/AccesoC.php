<?php
class AccesoC{

	private const AUTH_COOKIE_NAME = 'GALIASINTRANET_AUTH';
	private const AUTH_COOKIE_TTL = 28800;
	private const AUTH_COOKIE_PATH = '/intranet/';

	public static function IniciarSesionC(){
		if (session_status() === PHP_SESSION_ACTIVE) {
			return;
		}

		session_name('GALIASINTRANET');
		session_set_cookie_params(array(
			'lifetime' => 0,
			'path' => '/intranet/',
			'secure' => true,
			'httponly' => true,
			'samesite' => 'Lax'
		));
		session_start();
	}

	private static function TieneClaveSSOC(){
		return defined('GOOGLE_SSO_SIGNING_KEY')
			&& is_string(GOOGLE_SSO_SIGNING_KEY)
			&& strlen(GOOGLE_SSO_SIGNING_KEY) >= 64;
	}

	private static function CodificarBase64UrlC($valor){
		return rtrim(strtr(base64_encode($valor), '+/', '-_'), '=');
	}

	private static function DecodificarBase64UrlC($valor){
		if (!is_string($valor) || $valor === '' || !preg_match('/\\A[A-Za-z0-9_-]+\\z/', $valor)) {
			return false;
		}

		$base64 = strtr($valor, '-_', '+/');
		$base64 .= str_repeat('=', (4 - (strlen($base64) % 4)) % 4);
		$decodificado = base64_decode($base64, true);

		return $decodificado === false ? false : $decodificado;
	}

	public static function CrearCookieSSOC($identidad){
		if (!self::TieneClaveSSOC()
			|| !is_array($identidad)
			|| !isset($identidad['empleadoEmail'], $identidad['empleadoNombre'], $identidad['googleSub'])
			|| !is_string($identidad['empleadoEmail'])
			|| !is_string($identidad['empleadoNombre'])
			|| !is_string($identidad['googleSub'])) {
			return false;
		}

		$email = strtolower(trim($identidad['empleadoEmail']));
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)
			|| !str_ends_with($email, '@galias.com.co')
			|| $identidad['googleSub'] === '') {
			return false;
		}

		$ahora = time();
		$datos = array(
			'v' => 1,
			'iat' => $ahora,
			'exp' => $ahora + self::AUTH_COOKIE_TTL,
			'email' => $email,
			'name' => $identidad['empleadoNombre'],
			'sub' => $identidad['googleSub']
		);
		$json = json_encode($datos, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
		if (!is_string($json)) {
			return false;
		}

		$payload = self::CodificarBase64UrlC($json);
		$firma = hash_hmac('sha256', $payload, GOOGLE_SSO_SIGNING_KEY, true);
		$ticket = $payload . '.' . self::CodificarBase64UrlC($firma);

		return setcookie(self::AUTH_COOKIE_NAME, $ticket, array(
			'expires' => $datos['exp'],
			'path' => self::AUTH_COOKIE_PATH,
			'secure' => true,
			'httponly' => true,
			'samesite' => 'Lax'
		));
	}

	private static function LeerCookieSSOC(){
		if (!self::TieneClaveSSOC()
			|| !isset($_COOKIE[self::AUTH_COOKIE_NAME])
			|| !is_string($_COOKIE[self::AUTH_COOKIE_NAME])
			|| strlen($_COOKIE[self::AUTH_COOKIE_NAME]) > 4096) {
			return false;
		}

		$partes = explode('.', $_COOKIE[self::AUTH_COOKIE_NAME]);
		if (count($partes) !== 2) {
			return false;
		}

		$firmaRecibida = self::DecodificarBase64UrlC($partes[1]);
		if ($firmaRecibida === false) {
			return false;
		}

		$firmaEsperada = hash_hmac('sha256', $partes[0], GOOGLE_SSO_SIGNING_KEY, true);
		if (!hash_equals($firmaEsperada, $firmaRecibida)) {
			return false;
		}

		$json = self::DecodificarBase64UrlC($partes[0]);
		if ($json === false) {
			return false;
		}

		$datos = json_decode($json, true);
		if (!is_array($datos)
			|| !isset($datos['v'], $datos['iat'], $datos['exp'], $datos['email'], $datos['name'], $datos['sub'])
			|| $datos['v'] !== 1
			|| !is_int($datos['iat'])
			|| !is_int($datos['exp'])
			|| !is_string($datos['email'])
			|| !is_string($datos['name'])
			|| !is_string($datos['sub'])) {
			return false;
		}

		$ahora = time();
		$email = strtolower($datos['email']);
		if ($datos['iat'] <= 0
			|| $datos['iat'] > $ahora + 60
			|| $datos['exp'] <= $ahora
			|| $datos['exp'] > $datos['iat'] + self::AUTH_COOKIE_TTL
			|| !filter_var($email, FILTER_VALIDATE_EMAIL)
			|| !str_ends_with($email, '@galias.com.co')
			|| $datos['sub'] === ''
			|| strlen($datos['name']) > 512) {
			return false;
		}

		$nombre = trim(strip_tags($datos['name']));
		if ($nombre === '') {
			$nombre = $email;
		}

		return array(
			'empleadoNombre' => htmlspecialchars(html_entity_decode($nombre, ENT_QUOTES, 'UTF-8'), ENT_QUOTES, 'UTF-8'),
			'empleadoEmail' => $email,
			'googleSub' => $datos['sub'],
			'loginMethod' => 'google',
			'esAdmin' => GoogleC::EsAdministradorC($email) ? 'SI' : 'NO'
		);
	}

	private static function EliminarCookieSSOC(){
		setcookie(self::AUTH_COOKIE_NAME, '', array(
			'expires' => time() - 3600,
			'path' => self::AUTH_COOKIE_PATH,
			'secure' => true,
			'httponly' => true,
			'samesite' => 'Lax'
		));
	}

	public static function LimpiarSesionPhpC(){
		self::IniciarSesionC();
		if (session_status() !== PHP_SESSION_ACTIVE) {
			return false;
		}

		$nombreCookie = session_name();
		$_SESSION = array();
		session_unset();
		$destruida = session_destroy();
		setcookie($nombreCookie, '', array(
			'expires' => time() - 3600,
			'path' => self::AUTH_COOKIE_PATH,
			'secure' => true,
			'httponly' => true,
			'samesite' => 'Lax'
		));

		return $destruida;
	}

	#------------Carga Login
	public static function LoadC(){
		header('Cache-Control: no-store, private');
		include('views/login.php');
		die();
	}


	#------------Validacion Recaptcha Google V.3
	public static function ValidaRecaptchaGoogleC($recaptcha_response, $redireccionInvalida){
		$url = 'https://www.google.com/recaptcha/api/siteverify';
		$recaptcha_secret = RECAPTCHA_PRIVATE; 
		$data = array(
			'secret' => $recaptcha_secret,
			'response' => $recaptcha_response,
			'remoteip' => $_SERVER['REMOTE_ADDR']
		); 
		$curlConfig = array( 
			CURLOPT_URL => $url, 
			CURLOPT_POST => true, 
			CURLOPT_RETURNTRANSFER => true, 
			CURLOPT_POSTFIELDS => $data
		); 
		$ch = curl_init(); 
		curl_setopt_array($ch, $curlConfig); 
		$response = curl_exec($ch); 
		curl_close($ch);
		$jsonResponse = json_decode($response);
		if($jsonResponse->success !== true || $jsonResponse->score < 0.6) { 
			$redirige = URL_BASE.$redireccionInvalida;
			header('Location:'.$redirige);
            die;
		}
	}
	
	
	#------------Hace Login
	public static function LoginC(){
		header('Cache-Control: no-store, private');
		header('Location: ' . URL_BASE . 'inicio');
		die();
	}


	#------------Seguridad: Redireccion para accesos a paginas sin haberse logueado
	public static function IsNotLoguedC(){
		self::IniciarSesionC();
		$identidad = self::LeerCookieSSOC();

		if ($identidad === false) {
			self::LimpiarSesionPhpC();
			self::EliminarCookieSSOC();
			header('Cache-Control: no-store, private');
			header('Location: ' . URL_BASE . 'inicio');
			die();
		}

		$_SESSION = array(
			'empleado' => $identidad,
			'logueado' => 'SI'
		);
		header('Cache-Control: no-store, private');
	}


	#------------Hace Logout
	public static function LogoutC(){
		self::EliminarCookieSSOC();
		self::LimpiarSesionPhpC();
		header('Cache-Control: no-store, private');
		header('Location: ' . URL_BASE . 'inicio');
		die();
	}


}
?>