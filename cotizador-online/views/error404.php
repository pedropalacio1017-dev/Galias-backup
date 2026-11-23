<!DOCTYPE html>
<html lang="es">
<head>
<?php
$page_metatitle = 'Pagina no encontrada | '.PAGE_META_TITLE;
$page_metadescription = '';
include('views/includes/head.php');
?>
</head>

<body>

	<?php #----------Header ?>
	<?php include('views/includes/header.php');?>


	<div class="fila formulario" id="formulario">
		<div class="cols">
			<div class="col col_1">


				<?php #----------Error 404 ?>
				<div class="cols" id="error404">
                    <div class="col col_1">
                        <div><img src="views/images/ico_carita_triste.svg" id="Error 404"></div>
                        <h1><strong>Lo sentimos</strong></h1>
                        <p>La página que buscas no existe.</p>
						<?php if(isset($msg_explica)){
							echo '<p>'.$msg_explica.'</p>';
						}?>
                    </div>                        
                </div>

				
			</div>
		</div>
	</div>


	<?php #----------Footer ?>
	<?php include('views/includes/footer.php');?>
	
</body>
</html>