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
    	<div class="titulo_seccion"><h1>Notas Legales</h1></div>
	</div>
</div>


<?php #-------------Acciones?>
<div class="container">
	<div class="content acciones">
        <a href="panel/nota-legal/new" class="btn_admin_anadir" title="Añadir">Añadir</a>
	</div>
</div>


<?php #-------------Tabla Listado?>
<div class="container">
	<div class="content">
    
		<?php if($notasLegales != NULL) { ?>
		<table id="listado_tabla" class="tabla_filtrable">
            <thead>
                <tr class="encabezado">
					<th>Tipo Nota</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
			<?php } ?>
            <?php foreach($notasLegales as $notaLegal) { ?>
            	<tr>
					<td><span>Tipo Nota:</span><?php echo $notaLegal['notaLegalTitulo'];?></td>
                    <td>

						<?php /*
						<a
						href="panel/nota-legal/delete/<?php echo $notaLegal['ID_notaLegal'];?>"
						title="Eliminar"
						class="btn_admin_eliminar warning_eliminar"
						data-alerta="¿Con seguridad quiere eliminar la nota legal tipo <?php echo $notaLegal['notaLegalTitulo'];?>?"
						>Eliminar</a>
						*/ ?>						

                    	<a href="panel/nota-legal/edit/<?php echo $notaLegal['ID_notaLegal'];?>" class="btn_admin_editar" title="Editar">Editar</a>

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
		$msg_texto = 'La nota legal fue CREADA.';
	}
	if($_GET['msg'] == 'update'){
		$msg_texto = 'La nota legalfue EDITADA.';
	}
	if($_GET['msg'] == 'delete'){
		$msg_texto = 'La nota legal fue ELIMINADA.';
	}
	#--------------- Dispara alerta
	include('views/includes/admin-alertas-swal.php');	
}
?>





</body>
</html>