<!DOCTYPE html>
<html lang="es">

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
                    <a href="#" onclick="window.history.go(-1); return false;" target="_self" class="volver">Atrás</a>
                </h1>
				<h1 class="after gris to_left">Error</h1>
                <p class="alerta error"><?php echo $msg_error; ?></p>   				
			</div>

		</div>
	</div>

	<?php #----Footer ?>
	<?php include('views/includes/footer.php');?>

</body>
</html>