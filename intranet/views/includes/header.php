<header class="col menu navegacion">

	<a id="pull" class="rspnsv"></a>

	<div id="logo">
		<img src="views/images/logo.svg" alt="Las Galias Constructora" />
	</div>
	
	<div class="fila desplegable">


		<?php #-----Menu Opciones ?>
		<ul class="margen">
			<li>
				<p>
					<span class="red"><strong>Hola</strong></span>
					<br /><span><?php echo $_SESSION['empleado']['empleadoNombre'];?></span>
				</p>
			</li>

			<?php foreach($seccionesMenu as $menu){?>
			<li>
				<a href="<?php echo $menu['seccionSef'];?>" target="_self" class="ico">
					<img src="views/images/<?php echo $menu['seccionIcono'];?>">
					<?php echo $menu['seccionHtmlMenu'];?>
				</a>
			</li>
			<?php }?>

			<li>
				<a href="logout" target="_self" class="ico">
					<img src="views/images/ico_salir.svg">
					<span>Salir</span>
				</a>
			</li>
		</ul>


		<?php #-----Menu Administrador ?>
		<?php if($_SESSION['empleado']['esAdmin'] == 'SI'){?>
		<h3>Administrador</h3>
		<ul class="normal">
			<li>
				<a href="admin-categorias" target="_self" class="ico">
					<img src="views/images/ico_categorias.svg">
					<span>Categorías</span>
				</a>
			</li>
			<li>
				<a href="admin-carpetas" target="_self" class="ico">
					<img src="views/images/ico_carpetas.svg">
					<span>Carpetas</span>
				</a>
			</li>
			<li>
				<a href="admin-links" target="_self" class="ico ">
					<img src="views/images/ico_vinculos.svg">
					<span>Vínculos</span>
				</a>
			</li>
			<li>
				<a href="admin-empleados" target="_self" class="ico">
					<img src="views/images/ico_usuarios.svg">
					<span>Empleados</span>
				</a>
			</li>
			<li>
				<a href="admin-administradores" target="_self" class="ico">
					<img src="views/images/ico_admins.svg">
					<span>Administradores</span>
				</a>
			</li>
		</ul>
		<?php }?>


	</div>
</header>