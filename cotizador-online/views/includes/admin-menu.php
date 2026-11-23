<ul>

    <?php if($_SESSION['usuario']['usuarioRol'] == 'SUPERADMIN'){ ?>
        <li><a href="panel/proyectos"><span>Proyectos</span></a></li>    
        <li><a href="panel/variables"><span>Valores Base Cotizador</span></a></li>
        <li><a href="panel/notas-legales"><span>Notas Legales</span></a></li>
        <li><a href="panel/usuarios"><span>Usuarios</span></a></li>
        <li><a href="panel/contadores"><span>Contador Cotizaciones</span></a></li>
    <?php } ?>

    <li><a href="panel/contrasena"><span>Contraseña</span></a></li>
    <li><a href="inicio" target="_blank"><span>Ir al Cotizador</span></a></li>

</ul>