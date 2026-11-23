<!doctype html>
<html>
<head>
<?php include('views/includes/admin-head.php'); ?>
</head>

<body>
<?php include('views/includes/admin-header.php'); ?>


<?php #-------------Titulo Destacados?>
<div class="container">
	<div class="content">
    	<div class="titulo_seccion"><h1><strong>Hola</strong><br><?php echo $_SESSION['usuario']['usuarioNombre'];?></h1></div>
	</div>
</div>


<?php #-------------Destacados Inicio?>
<div class="container accesos_destacados">
	<div class="content">
    <?php include('views/includes/admin-menu.php'); ?>  
	</div>
</div>



<?php #--------------- Footer ?>
<?php include('views/includes/admin-footer.php'); ?>


</body>
</html>