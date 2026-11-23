<div class="logo_cliente fila">
    
    <img src="datas/<?php echo $proyecto['proyectoLogo'];?>" alt="<?php echo $proyecto['proyectoNombre'];?>">

    <?php if($proyecto['proyectoAreaConstruida'] != ''){?>
    <p class="proyecto-areas">        
        <strong>AREA CONSTRUIDA</strong>
        <br><?php echo $proyecto['proyectoAreaConstruida'];?>
    </p>
    <?php }?>

    <?php if($proyecto['proyectoAreaPrivada'] != ''){?>
    <p class="proyecto-areas">        
        <strong>AREA PRIVADA</strong>
        <br><?php echo $proyecto['proyectoAreaPrivada'];?>
    </p>
    <?php }?>

</div>