<?php //-----------------JQuery?>
<script src="views/scripts/jquery-3.4.1.min.js"></script>
<?php include('views/scripts/jquery-scripts.min.php'); ?>
<?php //include('views/scripts/jquery-scripts.php'); ?>


<?php //-----------------Date Time Picker?>
<link rel="stylesheet" href="views/scripts/datetimepicker/jquery.datetimepicker.min.css">
<script src="views/scripts/datetimepicker/jquery.datetimepicker.full.min.js"></script>


<?php //-----------------Sweet Alert?>
<link rel="stylesheet" href="views/scripts/sweet-alert-2/sweetalert2.min.css">
<script src="views/scripts/sweet-alert-2/sweetalert2.min.js"></script>


<?php #-------Separador de miles en las cifras?>    
<script src="views/scripts/jquery-number/jquery.number.min.js"></script>
<script>
$(document).ready(function(){
	$('.miles').number(true);
})
</script>