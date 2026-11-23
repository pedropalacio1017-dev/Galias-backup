<!doctype html>
<html lang="es">

<head>
    <?php
    $page_metatitle = 'Acceso | ' . PAGE_META_TITLE;
    $page_metadescription = '';
    include('views/includes/head.php');
    ?>

    <?php if(GOOGLE_CLIENT_ID !== ''){ ?>
    <script src="https://accounts.google.com/gsi/client" async></script>
    <?php } ?>
</head>

<body class="login">
    <header class="fila formulario">
        <div class="cols full tabla">
            <div class="col menu">
                <div id="logo" class="margen">
                    <img src="views/images/logo.svg" alt="Las Galias Constructora" />
                </div>

                <section class="sso-login-card" aria-labelledby="ssoLoginTitle">
                    <div class="sso-login-mark" aria-hidden="true">
                        <svg viewBox="0 0 48 48" focusable="false">
                            <path d="M24 4.5 7.5 11v10.7c0 10.2 6.7 17.8 16.5 21.8 9.8-4 16.5-11.6 16.5-21.8V11L24 4.5Z" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round" />
                            <path d="m16.5 24.5 5 5 10-11" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <p class="sso-login-eyebrow">ACCESO CORPORATIVO</p>
                    <h1 id="ssoLoginTitle" class="sso-login-title">Intranet Galias</h1>
                    <p class="sso-login-description">Inicia sesión de forma segura con tu cuenta de Google Workspace.</p>

                    <?php if(GOOGLE_CLIENT_ID !== ''){ ?>
                    <div class="google-login">
                        <div id="g_id_onload"
                             data-client_id="<?php echo htmlspecialchars(GOOGLE_CLIENT_ID, ENT_QUOTES, 'UTF-8'); ?>"
                             data-login_uri="<?php echo htmlspecialchars(URL_BASE.'google-login', ENT_QUOTES, 'UTF-8'); ?>"
                             data-ux_mode="redirect"
                             data-auto_prompt="false"></div>
                        <div class="g_id_signin" data-type="standard" data-size="large"
                             data-theme="outline" data-text="signin_with"
                             data-shape="rectangular" data-width="300"></div>
                    </div>
                    <p class="sso-login-domain">Solo se admiten cuentas <strong>@galias.com.co</strong></p>
                    <?php } else { ?>
                    <p class="sso-login-unavailable" role="status">El inicio de sesión con Google no está disponible. Comunícate con soporte.</p>
                    <?php } ?>
                </section>
            </div>
            <div class="col fondo" aria-hidden="true"></div>
        </div>
    </header>

    <?php include('views/includes/footer.php');?>

    <?php
    if(isset($_GET['msg']) && $_GET['msg'] === 'error-google'){
        $msg_titulo = 'Error';
        $msg_texto = 'No se pudo iniciar sesión con Google. Usa una cuenta de galias.com.co e inténtalo de nuevo.';
        $msg_type = 'error';
        include('views/includes/alertas-swal.php');
    }
    ?>
</body>
</html>
