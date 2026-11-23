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


			<div class="col contents to_left formulario administrador">

				<h1 class="after gris to_left"><em>Administración</em> Empleados <em>(<?php echo $cantidadEmpleados;?>)</em></h1>
				<div class="col col_1 float_left goodmargen">
					<a href="admin-empleados-download" class="btn_crear mediamargen">DESCARGAR PLANTILLA CSV</a>
				</div>


                <h1 class="after gris to_left"><em>Archivo</em> Planta de Empleados</h1>
                <form action="admin-empleados-upload" method="post" target="_self" enctype="multipart/form-data">
					<div class="cols">
						<div class="col col_1">
							<label>Archivo CSV</label>
							<input name="archivo_csv" type="file">
						</div>
					</div>		
					<input type="submit" class="btn" value="Cargar CSV" id="cargar_csv">
				</form>

			</div>


		</div>
	</div>
	
	</div>


	<?php #----Footer ?>
	<?php include('views/includes/footer.php');?>



	<?php #--------------- Mensajes SweetAlert de Confirmación ?>
    <?php
    if(isset($_GET['msg'])){
        #---------------Hecho con exito
        $msg_titulo = 'Hecho!';
        $msg_type = 'success';
        #
        if($_GET['msg'] == 'hecho'){
            $msg_texto = 'Se actualizó la base de datos de empleados.';
        }
        #--------------- Dispara alerta
        include('views/includes/alertas-swal.php');	
    }
    ?>



</body>

</html>