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


<?php #-------------Atrás?>
<div class="container">
	<div class="content acciones">
    	<a href="panel/usuarios" class="btn_admin_atras" title="Atrás">Atrás</a>
	</div>
</div>


<?php #-------------Formulario?>
<div class="container">
	<div class="content">
    	<form action="panel/usuario/<?php echo $accionSubmit; ?>" method="post" target="_self">
        	
            <input name="ID_usuario" type="hidden" value="<?php echo $ID_usuario; ?>">
        	
            
            <div class="row">
				<div class="row_x12 check">
                    <input name="usuarioActivo" type="checkbox" value="1" <?php if($usuarioActivo == '1'){echo('checked="checked"');}?>>
                    <label>Activo</label>
                </div>
				<div class="row_x3">
                    <label>Rol</label>
					<select name="usuarioRol">
						<option value="ASESOR" <?php if($usuarioRol == 'ASESOR'){echo 'selected';}?>>ASESOR</option>
						<option value="SUPERADMIN" <?php if($usuarioRol == 'SUPERADMIN'){echo 'selected';}?>>SUPERADMIN</option>
					</select>
                </div>
            	<div class="row_x3">
                    <label>Nombre</label>
                    <input name="usuarioNombre" type="text" value="<?php echo $usuarioNombre; ?>">
                </div>
                <div class="row_x3">
                    <label>Documento</label>
                    <input name="usuarioDocumento" type="text" value="<?php echo $usuarioDocumento; ?>">
                </div>
                <div class="row_x3">
                    <label>Celular</label>
                    <input name="usuarioCelular" type="text" value="<?php echo $usuarioCelular; ?>">
                </div>
            </div>


			<div class="row">
				<div class="row_x4">
                    <label>Email</label>
                    <input name="usuarioEmail" type="text" value="<?php echo $usuarioEmail; ?>">
                </div>
            	<div class="row_x4">
                    <label>Contraseña</label>
                    <input name="usuarioContrasena" type="password" value="">
                </div>
                <div class="row_x4">
                    <label>Repetir Contraseña</label>
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
		'input[name="usuarioNombre"]',
		'input[name="usuarioDocumento"]',
		'input[name="usuarioEmail"]'
	].join(',');
	$(resaltar).addClass('requerido');
});		
</script>


</body>
</html>