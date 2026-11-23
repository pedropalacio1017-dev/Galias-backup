<!doctype html>
<html>

<head>
    <?php
    $page_metatitle = 'Hola | ' . PAGE_META_TITLE;
    $page_metadescription = '';
    include('views/includes/head.php');
    ?>
</head>

<body class="dashboard">
	
	<div class="fila">
		<div class="cols full tabla">
			
			<?php #----Header ?>
			<?php include('views/includes/header.php');?>
			
			<?php #----Iconos destacados ?>
			<div class="col contents listado_servicios">
				<h1 class="after">¿Qué estás buscando?</h1>
				<div class="cols">

					<?php foreach($seccionesMenu as $menu){?>
					<div class="col col_4">
						<div class="icon">
							<a href="<?php echo $menu['seccionSef'];?>" target="_self">
								<img src="views/images/<?php echo $menu['seccionIcono'];?>">
							</a>
						</div>
						<?php echo $menu['seccionHtmlHome'];?>
						<?php echo $menu['seccionIntro'];?>
					</div>
					<?php }?>

				</div>
			</div>

		</div>
		
	</div>

	<?php #----Footer ?>
	<?php include('views/includes/footer.php');?>

</body>
</html>