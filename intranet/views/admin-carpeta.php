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
					<a href="admin-carpetas" target="_self" class="volver">Volver</a>
				</h1>

				<h1 class="after gris to_left"><?php echo $txt_titulo;?></h1>

				<form action="admin-carpeta/<?php echo $accionSubmit;?>" method="post" target="_self" enctype="multipart/form-data">
					
					<input name="ID_carpeta" type="hidden" value="<?php echo $ID_carpeta;?>" />
					<input name="ID_seccion" type="hidden" value="<?php echo $ID_seccion;?>" />

					<div class="cols">
						<div class="col col_1">
							<label>Categoría</label>
							<select name="ID_categoria">
								<option value="0" data-id-seccion ="0">Seleccionar...</option>
								<?php foreach($categorias as $categoria){?>							
								<option
								value="<?php echo $categoria['ID_categoria'];?>"
								data-id-seccion ="<?php echo $categoria['ID_seccion'];?>"
								<?php if($categoria['ID_categoria'] == $ID_categoria){echo 'selected="selected"';}?>
								><?php echo $categoria['seccion'].' -> '.$categoria['categoria'];?></option>
								<?php }?>
							</select>
						</div>
						<div class="col col_1">
							<label>Carpeta</label>
							<input name="carpeta" type="text" value="<?php echo $carpeta;?>">
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
            'select[name="ID_categoria"]',
            'input[name="carpeta"]'
        ].join(',');
        $(resaltar).addClass('requerido');
    });		
    </script>
    <?php }?>
	

</body>
</html>