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
    	<div class="titulo_seccion"><h1>Valores Base del Cotizador</h1></div>
	</div>
</div>


<?php //-------------Formulario?>
<div class="container">
	<div class="content">
    	<form action="panel/variables-update" method="post" target="_self">
        	
            <input name="ID_variables" type="hidden" value="<?php echo $varsCotizador['ID_variables'];?>">
            <input name="subsidioConcurrente" type="hidden" value="<?php echo $varsCotizador['subsidioConcurrente'];?>">
        	
            
            <div class="row">
            	<div class="row_x3">
                    <label>SMLV</label>
                    <input name="smlv" type="text" value="<?php echo $varsCotizador['smlv'];?>">
                </div>
                <div class="row_x3">
                    <label>Subsidio VIS 0 a 2 SMLV</label>
                    <input name="subsidioVis_0_2" type="text" value="<?php echo $varsCotizador['subsidioVis_0_2'];?>">
                </div>
                <div class="row_x3">
                    <label>Subsidio VIS 2 a 4 SMLV</label>
                    <input name="subsidioVis_2_4" type="text" value="<?php echo $varsCotizador['subsidioVis_2_4'];?>">
                </div>
                <div class="row_x3">
                    <label>Factor por millón UVR 30 años</label>
                    <input name="factorMillonUvr30" type="text" value="<?php echo $varsCotizador['factorMillonUvr30'];?>">
                </div>
            </div>                      
            
           
           	<hr> 
            <div class="row">
                <div class="row_x12 boton">
                    <input type="submit" value="EDITAR" class="boton_formulario">
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
		'input[name="smlv"]',
        'input[name="subsidioVis_0_2"]',
        'input[name="subsidioVis_2_4"]',
        'input[name="factorMillonUvr30"]'
	].join(',');
	$(resaltar).addClass('requerido');
});		
</script>





<?php #--------------- Mensajes SweetAlert de Confirmación ?>
<?php
if(isset($_GET['msg'])){
	#---------------Hecho con exito
	$msg_titulo = 'Hecho!';
	$msg_type = 'success';
	#
	if($_GET['msg'] == 'update'){
		$msg_texto = 'Las variables base del cotizador fueron EDITADAS.';
	}
	#--------------- Dispara alerta
	include('views/includes/admin-alertas-swal.php');	
}
?>






</body>
</html>