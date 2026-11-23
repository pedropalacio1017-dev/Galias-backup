<!doctype html>
<html>
<head>
<?php include('views/includes/admin-head.php'); ?>
</head>

<body>

<?php #-------------Login?>
<div class="container">
	<div class="content">
    	<div class="login">
       		<div class="logo"><img src="views/images/logo.svg" alt="Las Galias" /></div>
            <div class="titulo_seccion"><h1>Acceso Cotizador Online</h1></div>
            <form action="panel/login" method="post" target="_self">
            	<div class="row_x12">
                	<input name="usuarioEmail" type="text" placeholder="Email" autocomplete="no-complete">
                </div>
                <div class="row_x12">
                    <input name="usuarioContrasena" type="password" placeholder="Contraseña" autocomplete="no-complete">
                </div>
                <div class="row_x12 boton">
                    <input type="submit" value="ENTRAR" class="boton_formulario">
                </div>
                <?php if(isset($_GET['msg']) && $_GET['msg']=='login_error'){?>
                <hr>
				<p class="alerta error"><strong>ERROR</strong><br>Usuario o contraseña inexistentes. Inténtelo de nuevo.</p>
				<?php }?>
			</form>
            <a href="panel/restaurar-contrasena" target="_self">Olvidé mi contraseña</a>
        </div>
    </div>
</div>



<?php #--------------- Footer ?>
<?php include('views/includes/admin-footer.php'); ?>



<?php #echo crypt_pass('galias@54321');?>



</body>
</html>