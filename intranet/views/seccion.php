<!doctype html>
<html>

<head>
    <?php
    $page_metatitle = $seccion['seccion'].' | ' . PAGE_META_TITLE;
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
				<h1 class="after gris titulo_seccion"><?php echo $seccion['seccion'];?></h1>
				
				<?php #----Arbol Documentos ?>
				<?php if($arbol != NULL){?>
				<div class="fila arbol">
					<?php foreach($arbol as $categoryKey => $categoryValue){?>
					<div class="fila category">
						<a href="#" target="_self" class="toggler"><span><?php echo $categoryKey;?></span></a>
						<?php foreach($categoryValue as $carpetaKey => $carpetaValue){?>	
						<div class="fila">
							<a href="#" target="_self" class="toggler"><span><?php echo $carpetaKey;?></span></a>
							<div class="fila branch">
								<?php foreach($carpetaValue as $link){?>
								<div>
									<a href="<?php echo $link['linkEnlace'];?>" target="_blank"><?php echo $link['linkNombre'];?></a>
								</div>
								<?php }?>
							</div>
						</div>
						<?php }?>
					</div>
					<?php }?>
				</div>
				<?php }?>

				<?php #----No hay documentos ?>
				<?php if($arbol == NULL){?>
				<h2 class="to_left">Aún no hay links en esta sección</h2>
				<?php }?>


			</div>


		</div>
	</div>

	<?php #----Footer ?>
	<?php include('views/includes/footer.php');?>

</body>
</html>