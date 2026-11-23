<!doctype html>
<html>
<head>
<?php include('views/includes/admin-head.php'); ?>


</head>

<body class="parent">
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
    	<a href="panel/proyectos" class="btn_admin_atras" title="Atrás">Atrás</a>
	</div>
</div>


<?php #-------------Formulario?>
<div class="container">
	<div class="content">
    	<form action="panel/proyecto/<?php echo $accionSubmit; ?>" method="post" target="_self" enctype="multipart/form-data">
        	
            <input name="ID_proyecto" type="hidden" value="<?php echo $ID_proyecto; ?>">

            
            <?php #-------------INFO GENERAL ?>
            <div class="row mb_30">
                <div class="row_x12 check mb_30">
                    <input name="proyectoActivo" type="checkbox" value="1" <?php if($proyectoActivo == '1'){echo('checked="checked"');}?>>
                    <label>Activo</label>
                </div>
                <div class="row_x3">
                    <label>Nombre Proyecto(*)</label>
                    <input name="proyectoNombre" type="text" value="<?php echo $proyectoNombre; ?>">	
                </div>
                <div class="row_x3">
                    <label>Regional(*)</label>
                    <select name="ID_regional">
                        <option value="0">Seleccionar...</option>
                        <?php foreach($regionales as $regional){?>
                        <option
                        value="<?php echo $regional['ID_regional'];?>"
                        <?php if($ID_regional == $regional['ID_regional']){echo 'selected="selected"';}?>
                        ><?php echo $regional['regional'];?></option>
                        <?php }?>
                    </select>	
                </div>

                <div class="row_x2 invisible">
                    <label>Código CRM Sugar(*)</label>
                    <input name="proyectoCodigoCrm" type="text" value="<?php echo $proyectoCodigoCrm; ?>">	
                </div>

                <div class="row_x2">
                    <label>ID Macro Sinco(*)</label>
                    <input name="proyectoCodigoMacroSinco" type="text" value="<?php echo $proyectoCodigoMacroSinco; ?>">	
                </div>

                <div class="row_x4">
                    <label>Email</label>
                    <input name="proyectoEmail" type="text" value="<?php echo $proyectoEmail; ?>">	
                </div>
            </div>            

            <div class="row mb_20">
                <div class="row_x4">
                    <label>Área Construida</label>
                    <input name="proyectoAreaConstruida" type="text" value="<?php echo $proyectoAreaConstruida; ?>">	
                </div>
                <div class="row_x4">
                    <label>Área Privada</label>
                    <input name="proyectoAreaPrivada" type="text" value="<?php echo $proyectoAreaPrivada; ?>">	
                </div>
                <div class="row_x4">
                    <label>Tipo Nota Legal</label>
                    <select name="ID_notaLegal">
                        <?php foreach($notasLegales as $notaLegal){?>
                        <option value="<?php echo $notaLegal['ID_notaLegal'];?>" <?php if($ID_notaLegal == $notaLegal['ID_notaLegal']){echo 'selected';}?>><?php echo $notaLegal['notaLegalTitulo'];?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="row mb_20">
                <div class="row_x6">
                    <label>Link Web</label>
                    <input name="proyectoLinkWeb" type="text" value="<?php echo $proyectoLinkWeb; ?>">	
                </div>
                <div class="row_x6">
                    <label>Link Brochure</label>
                    <input name="proyectoLinkBrochure" type="text" value="<?php echo $proyectoLinkBrochure; ?>">	
                </div>
                <div class="row_x6">
                    <label>Link Waze</label>
                    <input name="proyectoLinkWaze" type="text" value="<?php echo $proyectoLinkWaze; ?>">	
                </div>
                <div class="row_x6">
                    <label>Link Documento Firma Contrato</label>
                    <input name="proyectoLinkDocumentosFirma" type="text" value="<?php echo $proyectoLinkDocumentosFirma; ?>">	
                </div>                
            </div>
            
            
            <?php #-------------IMAGENES ?>            
            <hr>
            <a id="macro_logo" class="admin_ancla"></a>
            <div class="titulo_seccion"><h1>Imágenes</h1></div>
            <div class="row">
                <div class="row_x4">
                    <label>Logo</label>
                    <input name="proyectoLogo" type="hidden" value="<?php echo $proyectoLogo; ?>">
                	<input name="proyectoLogo" type="file">                    
                    <?php if($proyectoLogo != ''){?>
                        <div class="row_x12 con_imagen">
                            <img src="datas/<?php echo $proyectoLogo;?>"> 
                        </div>                        
                    <?php }?> 
                </div>
                <div class="row_x4">
                    <label>Imagen mapa ubicación (600px ancho)</label>
                    <input name="proyectoImgMapa" type="hidden" value="<?php echo $proyectoImgMapa; ?>">
                	<input name="proyectoImgMapa" type="file">                    
                    <?php if($proyectoImgMapa != ''){?>
                        <div class="row_x12 con_imagen">
                            <img src="datas/<?php echo $proyectoImgMapa;?>"> 
                        </div>                        
                    <?php }?> 
                </div>
                <div class="row_x4">
                    <label>Flyer fortalezas del proyecto (600px ancho)</label>
                    <input name="proyectoImgFortalezas" type="hidden" value="<?php echo $proyectoImgFortalezas; ?>">
                	<input name="proyectoImgFortalezas" type="file">                    
                    <?php if($proyectoImgFortalezas != ''){?>
                        <div class="row_x12 con_imagen">
                            <img src="datas/<?php echo $proyectoImgFortalezas;?>"> 
                        </div>                        
                    <?php }?> 
                </div>
            </div>            

            
            <?php #-------------SUBPROYECTOS (Etapas) ?>
            <?php if($task == 'edit'){ ?>
            <hr>
            <a id="subproyectos" class="admin_ancla"></a>
            <div class="titulo_seccion"><h1>Subproyectos (etapas)</h1></div>
            <div class="container">
                <div class="content acciones delgado">
                    <a href="panel/proyecto/<?php echo $ID_proyecto;?>/subproyecto/new" class="btn_admin_anadir" title="Añadir">Añadir</a>
                </div>
            </div>
            <?php if($subproyectos != NULL){?>
            <div class="container">
                <div class="content">
                	<table id="listado_tabla" class="tabla_filtrable">
                        <thead>
                            <tr class="encabezado">
                                <th>Nombre</th>
                                <th>Entregas</th>
                                <th>Fecha Max Pago</th>
                                <th>Meses Pago CI</th>
                                <th>Precio</th>
                                <th>Valor Separación</th>
                                <th>Activo</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        	<?php foreach($subproyectos as $subproyecto){?>
							<tr>
                                <td><span>Nombre:</span><?php echo $subproyecto['subproyectoNombre'];?></td>
                                <td><span>Entregas:</span><?php echo $subproyecto['subproyectoEntregas'];?></td>
                                <td><span>Fecha Max Pago:</span><?php echo $subproyecto['subproyectoFechaMaxPago'];?></td>                                
                                <td><span>Meses Pago CI:</span><?php echo meses_hoy_hasta_fecha($subproyecto['subproyectoFechaMaxPago']);?></td>                                
                                <td><span>Precio:</span><?php echo number_format($subproyecto['subproyectoPrecio']);?></td>
                                <td><span>Valor Separación:</span><?php echo number_format($subproyecto['subproyectoValorSeparacion']);?></td>
                                <td><span>Activo:</span><?php echo booleano_a_si_no($subproyecto['subproyectoActivo']);?></td>                              
                                <td>
                                    <a
                                    href="panel/proyecto/<?php echo $ID_proyecto;?>/subproyecto/delete/<?php echo $subproyecto['ID_subproyecto'];?>"
                                    class="btn_admin_eliminar warning_eliminar"
                                    data-alerta="¿Con seguridad quiere eliminar el subproyecto <?php echo $subproyecto['subproyectoNombre'];?>?"
                                    title="Eliminar"
                                    >Eliminar</a>
                                    <a
                                    href="panel/proyecto/<?php echo $ID_proyecto;?>/subproyecto/edit/<?php echo $subproyecto['ID_subproyecto'];?>"
                                    class="btn_admin_editar"
                                    title="Editar"
                                    >Editar</a>
                                </td>
                            </tr>
							<?php } ?>
                        </tbody>
                    </table> 
                </div>
            </div>
            <?php } ?>
            <?php } ?>          
            
            
            
            
            
            
           	<?php #-------------BOTON FORMULARIO ?>        
           	<hr/>
           	<div class="row boton">
            	<input type="submit" value="<?php echo $txt_btn_form;?>" class="boton_formulario">
            </div>
            
            
            
            
        </form>
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



<?php #--------------- Resaltar campos requeridos en formulario NEW y EDIT ?>
<script>
$(document).ready(function() {
	var resaltar = [
		'input[name="proyectoNombre"]',
		'select[name="ID_regional"]',
        //'input[name="proyectoCodigoCrm"]',
        'input[name="proyectoCodigoMacroSinco"]',
        'input[name="proyectoLogo"]'
	].join(',');
	$(resaltar).addClass('requerido');
});		
</script>





</body>
</html>