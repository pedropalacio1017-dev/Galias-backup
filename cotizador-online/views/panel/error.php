<!doctype html>
<html>
<head>
<?php include('views/includes/admin-head.php'); ?>
</head>

<body>
<?php include('views/includes/admin-header.php'); ?>



<?php //-------------Titulo Destacados?>
<div class="container">
	<div class="content">
    	<div class="titulo_seccion"><h1><strong>¡Error!</strong></h1></div>
	</div>
</div>


<?php //-------------Mensaje?>
<div class="container">
	<div class="content">
    	<p class="alerta error"><?php echo $msg_error; ?></p>
    </div>
</div>


<?php //-------------Acciones?>
<div class="container">
	<div class="content acciones">
    	<a href="#" onclick="window.history.go(-1); return false;" class="btn_admin_atras" title="Atrás">Atrás</a>
	</div>
</div>



<?php #--------------- Footer ?>
<?php include('views/includes/admin-footer.php'); ?>






</body>
</html>