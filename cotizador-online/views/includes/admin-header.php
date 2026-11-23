<?php #-------------Header?>
<div class="container" id="header">
	<div class="content">
       	<div class="logo">
            <a href="panel/dashboard">
                <img src="views/images/logo.svg" alt="Las Galias" />
            </a>
        </div>
        <div class="header_acciones">
        	<a href="panel/logout" id="btn_admin_salir">Salir</a>
            <a href="#" id="btn_admin_menu">Menu</a>
        </div>
    </div>
</div>



<?php #-------------Menu?>
<div class="container" id="menu">
	<div class="content">
    <?php include('views/includes/admin-menu.php'); ?>    
	</div>
</div>