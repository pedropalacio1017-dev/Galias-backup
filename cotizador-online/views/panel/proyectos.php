<!doctype html>
<html>
<head>
<?php include('views/includes/admin-head.php'); ?>
</head>

<body>
<?php include('views/includes/admin-header.php'); ?>


<?php #-------------Titulo Seccion?>
<div class="container">
	<div class="content">
    	<div class="titulo_seccion"><h1>Proyectos</h1></div>
	</div>
</div>


<?php #-------------Acciones?>
<div class="container">
	<div class="content acciones">
        <a href="panel/proyecto/new" class="btn_admin_anadir" title="Añadir">Añadir</a>
	</div>
</div>


<?php #-------------Tabla Listado?>
<div class="container">
	<div class="content">
    
		<?php if($Registros != NULL) { ?>
		<table id="listado_tabla" class="tabla_filtrable">
            <thead>
                <tr class="encabezado">
					<th>ID</th>
					<th>Logo</th>
                	<th>Proyecto</th>
                    <th>Regional</th>
                	<th>Activo</th>
					<?php /* <th>Cod_Sugar</th> */ ?>
					<th>ID Macro Sinco</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
			<?php } ?>
            <?php foreach($Registros as $registro) { ?>
            	<tr>
					<td><span>ID:</span><?php echo $registro['ID_proyecto'];?></td>
					<td class="imagen"><img src="datas/<?php echo $registro['proyectoLogo'];?>"/></td>
                	<td><span>Proyecto:</span><?php echo $registro['proyectoNombre'];?></td>
					<td><span>Regional:</span><?php echo $registro['regional'];?></td>
					<td><span>Activo:</span><?php echo booleano_a_si_no($registro['proyectoActivo']);?></td>
					<?php /* <td><span>Cod_Sugar:</span><?php echo $registro['proyectoCodigoCrm'];?></td>  */ ?>
					<td><span>ID Macro Sinco:</span><?php echo $registro['proyectoCodigoMacroSinco'];?></td>
                    <td>

						<a
						href="panel/proyecto/delete/<?php echo $registro['ID_proyecto'];?>"
						title="Eliminar"
						class="btn_admin_eliminar warning_eliminar"
						data-alerta="¿Con seguridad quiere eliminar el proyecto <?php echo $registro['proyectoNombre'];?>? Se eliminará toda su información y no se podrá recuperar."
						>Eliminar</a>

                    	<a href="panel/proyecto/edit/<?php echo $registro['ID_proyecto'];?>" class="btn_admin_editar" title="Editar">Editar</a>

                    </td>
                </tr>
                <?php }?>
            </tbody>
    	</table> 
    </div>
</div>



<?php #--------------- Footer ?>
<?php include('views/includes/admin-footer.php'); ?>




<?php #--------------- jQuery plugin: Fancy Table ?>
<?php
$pagination = 'true';
$globalSearch = 'false';
$perPage = '500';
include('views/includes/admin-fancyTable-config.php');
?>





<?php #--------------- Mensajes SweetAlert de Confirmación ?>
<?php
if(isset($_GET['msg'])){
	#---------------Hecho con exito
	$msg_titulo = 'Hecho!';
	$msg_type = 'success';
	#
	if($_GET['msg'] == 'insert'){
		$msg_texto = 'El nuevo Proyecto fue CREADO.';
	}
	if($_GET['msg'] == 'update'){
		$msg_texto = 'El Proyecto fue EDITADO.';
	}
	if($_GET['msg'] == 'delete'){
		$msg_texto = 'El Proyecto fue ELIMINADO.';
	}
	#--------------- Dispara alerta
	include('views/includes/admin-alertas-swal.php');	
}
?>





</body>
</html>