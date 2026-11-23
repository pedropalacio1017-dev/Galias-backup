<?php
class GoogleC {

    public static function EsAdministradorC($email) {
        if (!defined('GOOGLE_ADMIN_EMAILS') || !is_array(GOOGLE_ADMIN_EMAILS)) {
            return false;
        }

        foreach (GOOGLE_ADMIN_EMAILS as $correoAutorizado) {
            if (is_string($correoAutorizado) && strcasecmp(trim($correoAutorizado), $email) === 0) {
                return true;
            }
        }

        return false;
    }

    public static function ValidarCsrfC($cookie, $formulario) {
        return is_string($cookie) && is_string($formulario)
            && $cookie !== '' && $formulario !== ''
            && hash_equals($cookie, $formulario);
    }

    public static function IdentidadC($datos) {
        if (!is_array($datos)
            || !isset($datos['hd'], $datos['email'], $datos['sub'], $datos['email_verified'])
            || !is_string($datos['hd']) || strcasecmp($datos['hd'], 'galias.com.co') !== 0
            || !is_string($datos['email'])
            || !filter_var($datos['email'], FILTER_VALIDATE_EMAIL)
            || !str_ends_with(strtolower($datos['email']), '@galias.com.co')
            || !in_array($datos['email_verified'], array(true, 'true'), true)
            || !is_string($datos['sub']) || $datos['sub'] === '') {
            return false;
        }

        $email = strtolower($datos['email']);
        $nombre = isset($datos['name']) && is_string($datos['name']) && trim($datos['name']) !== ''
            ? trim($datos['name']) : $email;

        return array(
            'empleadoNombre' => htmlspecialchars(strip_tags($nombre), ENT_QUOTES, 'UTF-8'),
            'empleadoEmail' => $email,
            'googleSub' => $datos['sub'],
            'loginMethod' => 'google',
            'esAdmin' => self::EsAdministradorC($email) ? 'SI' : 'NO'
        );
    }

    public static function LoginC() {
        header('Cache-Control: no-store');
        $error = URL_BASE . 'inicio/error-google';
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST'
            || !defined('GOOGLE_CLIENT_ID') || GOOGLE_CLIENT_ID === ''
            || !is_file(__DIR__ . '/../libs/google-auth/vendor/autoload.php')
            || !isset($_COOKIE['g_csrf_token'], $_POST['g_csrf_token'], $_POST['credential'])
            || !self::ValidarCsrfC($_COOKIE['g_csrf_token'], $_POST['g_csrf_token'])
            || !is_string($_POST['credential']) || $_POST['credential'] === ''
            || strlen($_POST['credential']) > 16384) {
            header('Location: ' . $error);
            die();
        }

        try {
            require_once __DIR__ . '/../libs/google-auth/vendor/autoload.php';
            $verificador = new Google\Auth\AccessToken();
            $datos = $verificador->verify($_POST['credential'], array(
                'audience' => GOOGLE_CLIENT_ID
            ));
            $identidad = self::IdentidadC($datos);
        } catch (Throwable $e) {
            $identidad = false;
        }

        if ($identidad === false) {
            header('Location: ' . $error);
            die();
        }

        if (!AccesoC::CrearCookieSSOC($identidad)) {
            header('Location: ' . $error);
            die();
        }

        AccesoC::LimpiarSesionPhpC();
        header('Location: ' . URL_BASE . 'home');
        die();
    }
}
