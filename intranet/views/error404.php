<!doctype html>
<html>

<head>
    <?php
    $page_metatitle = 'Error 404 | ' . PAGE_META_TITLE;
    $page_metadescription = '';
    include('views/includes/head.php');
    ?>
</head>

<body class="arbol_servicios">
	
	<div class="fila formulario">
		<div class="cols full tabla">


			<?php #----Header ?>
			<?php include('views/includes/header.php');?>


			<?php #----Cuerpo ?>
			<div class="col contents to_left">

				<?php #----Titulo Seccion ?>
				<h1 class="after gris titulo_seccion">Error 404</h1>	
				<h2 class="to_left">Página inexistente</h2>

			</div>


		</div>
	</div>

	<?php #----Footer ?>
	<?php include('views/includes/footer.php');?>

</body>
</html>