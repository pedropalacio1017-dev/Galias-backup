<?php if(isset($_SESSION['usuario']['usuarioNombre'])){?>
    <div id="saludo-asesor"><strong>Hola, <?php echo $_SESSION['usuario']['usuarioNombre'];?></strong></div>
<?php }?>

<header class="fila" id="header">
    <div class="cols">
        <div class="col col_2">
            <h1>COTIZADOR EN LINEA</h1>
            <div class="fila">
                <img src="views/images/logo.svg" id="logo">
            </div>
            <h4>¡Cumplir tu sueño<br/>
                <span>es más fácil de lo que piensas!</span>
            </h4>
        </div>
        <img src="views/images/foto_familia.png" id="foto_familia">
    </div>
</header>