<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<base href="<?php echo URL_BASE; ?>">
<link rel="canonical" href="<?php echo CANONICAL; ?>"/>
<link rel="icon" type="image/png" href="<?php echo URL_BASE; ?>views/images/favicon.png">
<?php if($page_metatitle != '') { ?><title><?php echo $page_metatitle; ?></title><?php } ?>
<?php if($page_metadescription != '') { ?><meta name="description" content="<?php echo $page_metadescription; ?>"/><?php } ?>
<link rel="stylesheet" href="views/css/estilos.css?v=3" type="text/css">

<?php //-----------------JQuery?>
<script src="views/scripts/jquery-3.4.1.min.js"></script>

<?php //-----------------Sweet Alert?>
<link rel="stylesheet" href="views/scripts/sweet-alert-2/sweetalert2.min.css">
<script src="views/scripts/sweet-alert-2/sweetalert2.min.js"></script>

<?php //-----------------JQuery Scripts?>
<?php include('views/scripts/jquery-scripts.min.php'); ?>
<?php //include('views/scripts/jquery-scripts.php'); ?>