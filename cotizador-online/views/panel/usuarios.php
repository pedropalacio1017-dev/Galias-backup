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
    	<div class="titulo_seccion"><h1>Usuarios</h1></div>
	</div>
</div>


<?php #-------------Acciones?>
<div class="container">
	<div class="content acciones">
        <a href="panel/usuario/new" class="btn_admin_anadir" title="Añadir">Añadir</a>
	</div>
</div>


<?php #-------------Tabla Listado?>
<div class="container">
	<div class="content">
    
		<?php if($usuarios != NULL) { ?>
		<table id="listado_tabla" class="tabla_filtrable">
            <thead>
                <tr class="encabezado">
					<th>Activo</th>
                	<th>Rol</th>
                    <th>Nombre</th>
                	<th>Email</th>
					<th>Documento</th>
                    <th>Celular</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
			<?php } ?>
            <?php foreach($usuarios as $usuario) { ?>
                <?php if($usuario['ID_usuario'] != '1'){?>
                    <tr>
                        <td><span>Activo:</span><?php echo booleano_a_si_no($usuario['usuarioActivo']);?></td>
                        <td><span>Rol:</span><?php echo $usuario['usuarioRol'];?></td>
                        <td><span>Nombre:</span><?php echo $usuario['usuarioNombre'];?></td>
                        <td><span>Email:</span><?php echo $usuario['usuarioEmail'];?></td>
                        <td><span>Documento:</span><?php echo $usuario['usuarioDocumento'];?></td>
                        <td><span>Celular:</span><?php echo $usuario['usuarioCelular'];?></td>
                        <td>    
                            
                            <a
                            href="panel/usuario/delete/<?php echo $usuario['ID_usuario'];?>"
                            title="Eliminar"
                            class="btn_admin_eliminar warning_eliminar"
                            data-alerta="¿Con seguridad quiere eliminar el usuario <?php echo $usuario['usuarioNombre'];?>?"
                            >Eliminar</a>
                            
                            <a href="panel/usuario/edit/<?php echo $usuario['ID_usuario'];?>" class="btn_admin_editar" title="Editar">Editar</a>
                        </td>
                    </tr>
                <?php } ?>
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
		$msg_texto = 'El nuevo usuario fue CREADO.';
	}
	if($_GET['msg'] == 'update'){
		$msg_texto = 'El usuario fue EDITADO.';
	}
	if($_GET['msg'] == 'delete'){
		$msg_texto = 'El usuario fue ELIMINADO.';
	}
	#--------------- Dispara alerta
	include('views/includes/admin-alertas-swal.php');	
}
?>





</body>
</html>