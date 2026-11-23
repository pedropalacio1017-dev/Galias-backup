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
            <div class="titulo_seccion"><h1>Restaurar Contraseña</h1></div>
            <form action="panel/restaurar-contrasena-envia" method="post" target="_self">
                <div class="row_x12">
                    <p>Se enviará una nueva contraseña al correo que tiene registrado en el sistema.</p>
                </div>
            	<div class="row_x12">
                	<input name="usuarioEmail" type="text" placeholder="Email" autocomplete="no-complete">
                </div>
                <div class="row_x12 boton">
                    <input type="submit" value="ENVIAR" class="boton_formulario">
                </div>

                <?php if(isset($_GET['msg'])){?>
                    <hr>
                    <?php if($_GET['msg']=='ok'){?>
                        <p class="alerta success"><strong>HECHO</strong><br>La nueva contraseña fue enviada al correo.</p>
				    <?php }?>
                    <?php if($_GET['msg']=='error'){?>
                        <p class="alerta error"><strong>ERROR</strong><br>El correo ingresado no está activo en el sistema.</p>
				    <?php }?>
				<?php }?>

			</form>
            <a href="acceso" target="_self">Volver a acceso</a>
        </div>
    </div>
</div>



<?php #--------------- Footer ?>
<?php include('views/includes/admin-footer.php'); ?>



<?php #echo crypt_pass('galias@54321');?>



</body>
</html>