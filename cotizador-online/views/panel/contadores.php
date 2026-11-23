<!doctype html>
<html>
<head>
<?php include('views/includes/admin-head.php'); ?>
</head>

<body>
<?php include('views/includes/admin-header.php'); ?>


<?php //-------------Titulo Seccion?>
<div class="container">
	<div class="content">
    	<div class="titulo_seccion"><h1>Contador de Cotizaciones Guardadas</h1></div>
	</div>
</div>


<?php //-------------Contadores?>
<div class="container contadores">
	<div class="content">
        <div class="width_50">
            <div class="gran_total">
                <p>Consolidado</p>
                <p class="numero"><?php echo number_format($ContadorTodos);?></p>
            </div>
        </div> 
        <div class="width_50">
            <h2>Mes en curso y últimos 6 meses</h2>
            <ul>
                <?php foreach($ContadoresMeses as $contador){ ?>
                <li><span><?php echo $contador[0];?></span> <?php echo number_format($contador[1]);?></li>
                <?php } ?>
            </ul>
        </div>
	</div>
</div>



<?php #--------------- Footer ?>
<?php include('views/includes/admin-footer.php'); ?>



</body>
</html>