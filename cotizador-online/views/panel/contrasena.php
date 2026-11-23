<!doctype html>
<html>
<head>
<?php include('views/includes/admin-head.php'); ?>
</head>

<body>
<?php include('views/includes/admin-header.php'); ?>


<?php #-------------Titulo Seccion?>
<div class="container">
	<div class="content">
    	<div class="titulo_seccion"><h1><?php echo $txt_titulo;?></h1></div>
	</div>
</div>


<?php #-------------Formulario?>
<div class="container">
	<div class="content">
    	<form action="panel/contrasena-cambiar" method="post" target="_self">
        	
            <div class="row">
				<div class="row_x6">
                    <label>Nueva Contraseña</label>
                    <input name="usuarioContrasena" type="password" value="">
                </div>
                <div class="row_x6">
                    <label>Repetir Nueva Contraseña</label>
                    <input name="usuarioContrasenaRepite" type="password" value="">
                </div>
            </div>                      
           
           	<hr> 
            <div class="row">
                <div class="row_x12 boton">
                    <input type="submit" value="<?php echo $txt_btn_form;?>" class="boton_formulario">
                </div>
            </div>      
            
        </form>
	</div>
</div>



<?php #--------------- Footer ?>
<?php include('views/includes/admin-footer.php'); ?>



<?php #--------------- Resaltar campos requeridos ?>
<script>
$(document).ready(function() {
	var resaltar = [
		'input[name="usuarioContrasena"]',
		'input[name="usuarioContrasenaRepite"]'
	].join(',');
	$(resaltar).addClass('requerido');
});		
</script>


<?php #--------------- Mensajes SweetAlert de Confirmación ?>
<?php
if(isset($_GET['msg'])){
	#---------------Hecho con exito
	$msg_titulo = 'Hecho!';
	$msg_type = 'success';
	if($_GET['msg'] == 'update'){
		$msg_texto = 'La contraseña fue EDITADA.';
	}
	#--------------- Dispara alerta
	include('views/includes/admin-alertas-swal.php');	
}
?>



</body>
</html>