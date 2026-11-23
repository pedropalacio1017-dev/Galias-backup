<!doctype html>
<html>

<head>
    <?php
    $page_metatitle = 'Administrador | ' . PAGE_META_TITLE;
    $page_metadescription = '';
    include('views/includes/head.php');
    ?>
</head>

<body class="arbol_servicios">
	
	<div class="fila formulario">
		<div class="cols full tabla">
			
			<?php #----Header ?>
			<?php include('views/includes/header.php');?>

			<?php #----Formulario ?>
			<div class="col contents to_left formulario administrador">

				<h1 class="after gris to_left">
					<a href="admin-administradores" target="_self" class="volver">Volver</a>
				</h1>

				<h1 class="after gris to_left"><?php echo $txt_titulo;?></h1>

				<form action="admin-administrador/<?php echo $accionSubmit;?>" method="post" target="_self" enctype="multipart/form-data">
					
                    <input name="ID_admin" type="hidden" value="<?php echo $ID_admin;?>" />

					<div class="cols">
						<div class="col col_1">
							<label>Documento Empledo</label>
							<input name="empleadoDocumento" type="text" value="<?php echo $empleadoDocumento;?>">
						</div>
					</div>					
					
					<input type="submit" class="btn" value="<?php echo $txt_btn_form; ?>">

				</form>
			</div>

		</div>
	</div>


	<?php #----Footer ?>
	<?php include('views/includes/footer.php');?>


	<?php #--------------- Resaltar campos requeridos en formulario NEW y EDIT ?>
    <?php if($task == 'new' || $task == 'edit'){?>
    <script>
    $(document).ready(function() {
        var resaltar = [
            'input[name="empleadoDocumento"]'
        ].join(',');
        $(resaltar).addClass('requerido');
    });		
    </script>
    <?php }?>
	

</body>
</html>