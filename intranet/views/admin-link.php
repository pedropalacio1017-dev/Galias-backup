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
					<a href="admin-links" target="_self" class="volver">Volver</a>
				</h1>

				<h1 class="after gris to_left"><?php echo $txt_titulo;?></h1>

				<form action="admin-link/<?php echo $accionSubmit;?>" method="post" target="_self" enctype="multipart/form-data">
					
                    <input name="ID_link" type="hidden" value="<?php echo $ID_link;?>" />
					<input name="ID_seccion" type="hidden" value="<?php echo $ID_seccion;?>" />
                    <input name="ID_categoria" type="hidden" value="<?php echo $ID_categoria;?>" />

					<div class="cols">
						<div class="col col_1">
							<label>Carpeta</label>
							<select name="ID_carpeta">
								<option value="0" data-id-seccion ="0" data-id-categoria ="0">Seleccionar...</option>
								<?php foreach($carpetas as $carpeta){?>							
								<option
								value="<?php echo $carpeta['ID_carpeta'];?>"
								data-id-seccion ="<?php echo $carpeta['ID_seccion'];?>"
                                data-id-categoria ="<?php echo $carpeta['ID_categoria'];?>"
								<?php if($carpeta['ID_carpeta'] == $ID_carpeta){echo 'selected="selected"';}?>
								><?php echo $carpeta['seccion'].' -> '.$carpeta['categoria'].' -> '.$carpeta['carpeta'];?> </option>
								<?php }?>
							</select>
						</div>
                        <div class="col col_1">
							<label>Link Nombre</label>
							<input name="linkNombre" type="text" value="<?php echo $linkNombre;?>">
						</div>
						<div class="col col_1">
							<label>Link Enlace</label>
							<input name="linkEnlace" type="text" value="<?php echo $linkEnlace;?>">
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
            'select[name="ID_carpeta"]',
            'input[name="linkNombre"]',
            'input[name="linkEnlace"]'
        ].join(',');
        $(resaltar).addClass('requerido');
    });		
    </script>
    <?php }?>
	

</body>
</html>