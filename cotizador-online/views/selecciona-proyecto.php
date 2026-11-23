<!DOCTYPE html>
<html lang="es">
<head>
<?php
$page_metatitle = PAGE_META_TITLE;
$page_metadescription = 'Aquí cotiza muy fácil tu futura vivieda propia';
include('views/includes/head.php');
?>
</head>

<body>

	<?php #----------Header ?>
	<?php include('views/includes/header.php');?>


	<div class="fila formulario" id="formulario">
		<div class="cols">
			<div class="col col_1">


				<?php #----------Titulo ?>
				<?php include('views/includes/titulo.php');?>


				<?php #----------PASO 1: Selecciona Ciudad / Proyecto ?>
				<form>					
					<div class="fila paso paso_1">
						<div class="cols minimargen">
							<div class="col col_2">
								<select id="select_regional">
									<option value="0">Selecciona tu ciudad..</option>
									<?php foreach($arrayRegionales as $regional){?>
									<option value="<?php echo $regional['ID_regional'];?>"><?php echo $regional['regional'];?></option>
									<?php }?>
								</select>
							</div>
							<div class="col col_2">
								<script>
									var proyectos = <?php echo json_encode($arrayProyectos); ?>;
								</script>
								<select id="select_proyecto" disabled>
									<option value="0">Selecciona tu proyecto...</option>
								</select>
							</div>
						</div>
						<a href="#" class="boton" id="btn_selecciona_proyecto">CONTINUAR</a>
					</div>					
				</form>

				
			</div>
		</div>
	</div>


	<?php #----------Footer ?>
	<?php include('views/includes/footer.php');?>
	
</body>
</html>