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
    	<div class="titulo_seccion"><h1><?php echo $txt_titulo;?></h1></div>
	</div>
</div>


<?php #-------------Atrás?>
<div class="container">
	<div class="content acciones">
    	<a href="panel/notas-legales" class="btn_admin_atras" title="Atrás">Atrás</a>
	</div>
</div>


<?php #-------------Formulario?>
<div class="container">
	<div class="content">
    	<form action="panel/nota-legal/<?php echo $accionSubmit; ?>" method="post" target="_self">
        	
            <input name="ID_notaLegal" type="hidden" value="<?php echo $ID_notaLegal; ?>">

            <div class="row">
				<div class="row_x12">
                    <label>Nota Legal Tipo</label>
                    <input name="notaLegalTitulo" type="text" value="<?php echo $notaLegalTitulo; ?>">
                </div>
            	<div class="row_x12">
                    <label>Nota Legal</label>
                    <textarea name="notaLegal" class="alto"><?php echo $notaLegal; ?></textarea>
                </div> 
            </div>
           
           	<hr> 
            <div class="row">
                <div class="row_x12 boton">
                    <input type="submit" value="<?php echo $txt_btn_form;?>" class="boton_formulario">
                </div>
            </div>          
            
        </form>
	</div>
</div>



<?php #--------------- Footer ?>
<?php include('views/includes/admin-footer.php'); ?>




<?php #--------------- Resaltar campos requeridos ?>
<script>
$(document).ready(function() {
	var resaltar = [
		'input[name="notaLegalTitulo"]',
		'textarea[name="notaLegal"]'
	].join(',');
	$(resaltar).addClass('requerido');
});		
</script>


</body>
</html>