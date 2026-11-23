<!doctype html>
<html>
<head>
<?php include('views/includes/admin-head.php'); ?>


</head>

<body class="parent">
<?php include('views/includes/admin-header.php'); ?>


<?php //-------------Titulo Seccion?>
<div class="container">
	<div class="content">
    	<div class="titulo_seccion"><h1><?php echo $txt_titulo;?></h1></div>
	</div>
</div>


<?php //-------------Atrás?>
<div class="container">
	<div class="content acciones">
    	<a href="panel/proyecto/edit/<?php echo $ID_proyecto; ?>#subproyectos" class="btn_admin_atras" title="Atrás">Atrás</a>
	</div>
</div>


<?php //-------------Formulario?>
<div class="container">
	<div class="content">
    	<form action="panel/proyecto/<?php echo $ID_proyecto;?>/subproyecto/<?php echo $accionSubmit;?>" method="post" target="_self" enctype="multipart/form-data">
       
            <input name="ID_subproyecto" type="hidden" value="<?php echo $ID_subproyecto; ?>">
            <input name="ID_proyecto" type="hidden" value="<?php echo $ID_proyecto; ?>">
            
            
            <?php //-------------INFO GENERAL ?>
            <div class="row">
            	<div class="row_x3">
                	<label>Nombre(*)</label>
                    <input name="subproyectoNombre" type="text" value="<?php echo $subproyectoNombre; ?>">	
                </div>
				<div class="row_x3">
                	<label>Texto Entregas(*)</label>
                    <input name="subproyectoEntregas" type="text" value="<?php echo $subproyectoEntregas; ?>">	
                </div>
				<div class="row_x3">
                	<label>Fecha Máxima Pago(*)</label>
                    <input name="subproyectoFechaMaxPago" type="text" value="<?php echo $subproyectoFechaMaxPago; ?>" placeholder="AAAA-MM-DD">	
                </div>
				<div class="row_x3">
					<label>Precio(*)</label>
					<input name="subproyectoPrecio" type="text" value="<?php echo $subproyectoPrecio; ?>">	
				</div>
            </div>
			<div class="row">				
				<div class="row_x3">
					<label>Valor Separación(*)</label>
					<input name="subproyectoValorSeparacion" type="text" value="<?php echo $subproyectoValorSeparacion; ?>">	
				</div>
				<div class="row_x3 invisible">
                	<label>Sobreescribir Código Sugar</label>
                    <input name="subproyectoCodigoCrm" type="text" value="<?php echo $subproyectoCodigoCrm; ?>">	
                </div>				
				<div class="row_x3 check">
					<input name="subproyectoActivo" type="checkbox" value="1" <?php if($subproyectoActivo == '1'){echo('checked="checked"');}?>>
					<label>Activo</label>
				</div>
			</div>

            
            <?php //-------------BOTON FORMULARIO ?>        
           	<hr/>
           	<div class="row boton">
            	<input type="submit" value="<?php echo $txt_btn_form;?>" class="boton_formulario">
            </div>
            
            
            
            
        </form>
	</div>
</div>



<?php #--------------- Footer ?>
<?php include('views/includes/admin-footer.php'); ?>




<?php #--------------- Resaltar campos requeridos en formulario NEW y EDIT ?>
<?php if($task == 'new' || $task == 'edit'){?>
<script>
$(document).ready(function() {
	var resaltar = [
		'input[name="subproyectoNombre"]',
		'input[name="subproyectoEntregas"]',
		'input[name="subproyectoFechaMaxPago"]',
		'input[name="subproyectoPrecio"]',
		'input[name="subproyectoValorSeparacion"]'
	].join(',');
	$(resaltar).addClass('requerido');
});		
</script>
<?php }?>





</body>
</html>