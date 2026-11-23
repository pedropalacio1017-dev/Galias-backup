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


			<div class="col contents to_left">
				<h1 class="after gris to_left"><em>Administración</em> Carpetas</h1>
				<div class="col col_1 float_left">
					<a href="admin-carpeta/new" class="btn_crear mediamargen">NUEVA</a>
				</div>				
				<table id="listado_tabla" class="tabla_filtrable mediamargen">
					<thead>
						<tr class="encabezado">
							<th>Sección</th>
							<th>Categoría</th>
                            <th>Carpeta</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($Registros as $registro){?>
						<tr>
							<td><span>Sección:</span><?php echo $registro['seccion'];?></td>
							<td><span>Categoría:</span><?php echo $registro['categoria'];?></td>
                            <td><span>Carpeta:</span><?php echo $registro['carpeta'];?></td>
							<td>

								<a href="admin-carpeta/remove/<?php echo $registro['ID_carpeta'];?>"
								class="btn_tabla_eliminar warning_eliminar"
								title="Eliminar"
								data-alerta="Si confirma se eliminará completa la carpeta <?php echo $registro['carpeta'];?>, incluyendo todos sus links."
								>Eliminar</a>

								<a href="admin-carpeta/edit/<?php echo $registro['ID_carpeta'];?>" class="btn_admin_editar" title="Editar">Editar</a>

							</td>
						</tr>
						<?php }?>
					</tbody>
				</table>
			</div>


		</div>
	</div>
	
	</div>


	<?php #----Footer ?>
	<?php include('views/includes/footer.php');?>



	<?php #----Fancy Table ?>
	<?php include('views/includes/fancyTable-config.php'); ?>



	<?php #--------------- Mensajes SweetAlert de Confirmación ?>
    <?php
    if(isset($_GET['msg'])){
        #---------------Hecho con exito
        $msg_titulo = 'Hecho!';
        $msg_type = 'success';
        #
        if($_GET['msg'] == 'insert'){
            $msg_texto = 'La Carpeta fue CREADA.';
        }
        if($_GET['msg'] == 'update'){
            $msg_texto = 'La Carpeta fue EDITADA.';
        }
        if($_GET['msg'] == 'delete'){
            $msg_texto = 'La Carpeta fue ELIMINADA.';
        }
        #--------------- Dispara alerta
        include('views/includes/alertas-swal.php');	
    }
    ?>



</body>

</html>