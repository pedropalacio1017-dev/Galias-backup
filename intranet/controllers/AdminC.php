<?php
class AdminC{
	
	
	#-----------------------------------------------------------------------------
	# PERMISO ADMIN
	#-----------------------------------------------------------------------------
	
	#------------¿Tiene Permiso Admin?
	public static function EsAdminC(){

		#---Seguridad: ¿Está logueado?
		$IsNotLoguedC = AccesoC::IsNotLoguedC();

		#---Tiene Permiso para Administrar
		if($_SESSION['empleado']['esAdmin'] != 'SI'){
			$redirige = 'home';
			header('Location:'.$redirige);
			die();
		}
		
	}

	
	#-----------------------------------------------------------------------------
	# CATEGORIAS
	#-----------------------------------------------------------------------------
	
	#------------Listado Categorias
	public static function CategoriasC(){

		#------------¿Tiene Permiso Admin?
		$EsAdminC = AdminC::EsAdminC();

		#---Secciones Activas
		$seccionesMenu = RegistrosBdM::GetSeccionesM('1');

		#------------Carga Registros
		$Registros = RegistrosBdM::GetCategoriasM();
		//echo json_encode($Registros);die;

		#------------Carga View
		include('views/admin-categorias.php');
		die();

	}
	
	#------------Carga Form Categoria (Detalle)
	public static function CategoriaC(){
		
		#------------¿Tiene Permiso Admin?
		$EsAdminC = AdminC::EsAdminC();

		#---Secciones Activas
		$seccionesMenu = RegistrosBdM::GetSeccionesM('1');

		#------------Variables por defecto (Llenar formulario NEW)
		$ID_categoria = '0';
		$ID_seccion = '0';
		$categoria = '';

		#------------Defino cual es la tarea del formulario
		$task = sanitize_xss($_GET['task']);
		if($task == 'new'){
			$txt_titulo = 'Nueva Categoría';
			$txt_btn_form = 'Guardar';
			$accionSubmit = 'new/insert';	
		}
		if($task == 'edit'){
			$txt_titulo = 'Editar Categoría';
			$txt_btn_form = 'Guardar';
			$accionSubmit = 'edit/update';	
		}

		#------------Cargo info de BD si es EDIT
		if($task == 'edit'){
			$ID_categoria = sanitize_xss($_GET['ID_categoria']);
			$categoriaRegistro = RegistrosBdM::GetCategoriaM($ID_categoria);
			$ID_seccion = $categoriaRegistro['ID_seccion'];
			$categoria = $categoriaRegistro['categoria'];
		}

		#------------Cargo Lista Secciones
		$secciones = RegistrosBdM::GetSeccionesM('1');

		#------------Carga View
		include('views/admin-categoria.php');
		die();

	}
	
	#------------Categoria CREATE / UPDATE / DELETE
	public static function CategoriaCrudC(){

		#------------¿Tiene Permiso Admin?
		$EsAdminC = AdminC::EsAdminC();

		#------------Defino ID y accion
		if(isset($_POST['ID_categoria'])){
			$ID_categoria = sanitize_xss($_POST['ID_categoria']);
		}
		if(isset($_GET['ID_categoria'])){
			$ID_categoria = sanitize_xss($_GET['ID_categoria']);
		}
		$action = sanitize_xss($_GET['action']); //insert, update, delete

		#------------Defino primary key para Queries
        $table ='categorias';
		$primarykey = 'ID_categoria';
		$primaryvalue = $ID_categoria;

		#------------CREATE o UPDATE
		if($action == 'insert' || $action == 'update'){

			#------------Validacion Campos Obligatorios
			if(
				$_POST['ID_seccion'] == '0'
				|| $_POST['categoria'] == ''
			){
				$msg_error = 'Debe llenar todo los campos requeridos, resaltados con línea roja.<br><br>Por favor inténtelo de nuevo.';
				include('views/admin-error.php');
				die();
			}
			
			#------------Array Valores Formulario
			$parameters = array(
				'ID_seccion' => sanitize_xss($_POST['ID_seccion']),
				'categoria' => sanitize_xss($_POST['categoria'])
			);

			#------------CREATE
			if($action == 'insert'){
				$query = PreparedStatementsM::InsertM($parameters, $table);
			}

			#------------UPDATE
			if($action == 'update'){
				$query = PreparedStatementsM::UpdateM($parameters, $table, $primarykey, $primaryvalue);
			}

		}

		#------------DELETE
		if($action == 'delete'){

			#------------Elimino Categoria
			$query = PreparedStatementsM::DeleteM($table, $primarykey, $primaryvalue);

			#------------Elimino Carpetas
			$table ='carpetas';
			$query = PreparedStatementsM::DeleteM($table, $primarykey, $primaryvalue);

			#------------Elimino Links
			$table ='intranet_links';
			$query = PreparedStatementsM::DeleteM($table, $primarykey, $primaryvalue);

		}

		#------------Redirige
		$redirige = '../../admin-categorias/ok/'.$action;
		header('Location:'.$redirige);
		die();

	}



	#-----------------------------------------------------------------------------
	# CARPETAS
	#-----------------------------------------------------------------------------
	
	#------------Listado Carpetas
	public static function CarpetasC(){

		#------------¿Tiene Permiso Admin?
		$EsAdminC = AdminC::EsAdminC();

		#---Secciones Activas
		$seccionesMenu = RegistrosBdM::GetSeccionesM('1');

		#------------Carga Registros
		$Registros = RegistrosBdM::GetCarpetasM();
		//echo json_encode($Registros);die;

		#------------Carga View
		include('views/admin-carpetas.php');
		die();

	}
	
	#------------Carga Form Carpeta (Detalle)
	public static function CarpetaC(){
		
		#------------¿Tiene Permiso Admin?
		$EsAdminC = AdminC::EsAdminC();

		#---Secciones Activas
		$seccionesMenu = RegistrosBdM::GetSeccionesM('1');

		#------------Variables por defecto (Llenar formulario NEW)
		$ID_carpeta = '0';
		$ID_seccion = '0';
		$ID_categoria = '0';
		$carpeta = '';

		#------------Defino cual es la tarea del formulario
		$task = sanitize_xss($_GET['task']);
		if($task == 'new'){
			$txt_titulo = 'Nueva Carpeta';
			$txt_btn_form = 'Guardar';
			$accionSubmit = 'new/insert';	
		}
		if($task == 'edit'){
			$txt_titulo = 'Editar Carpeta';
			$txt_btn_form = 'Guardar';
			$accionSubmit = 'edit/update';	
		}

		#------------Cargo info de BD si es EDIT
		if($task == 'edit'){
			$ID_carpeta = sanitize_xss($_GET['ID_carpeta']);
			$carpetaRegistro = RegistrosBdM::GetCarpetaM($ID_carpeta);
			$ID_seccion = $carpetaRegistro['ID_seccion'];
			$ID_categoria = $carpetaRegistro['ID_categoria'];
			$carpeta = $carpetaRegistro['carpeta'];
		}

		#------------Cargo Lista Categorias
		$categorias = RegistrosBdM::GetCategoriasM();

		#------------Carga View
		include('views/admin-carpeta.php');
		die();

	}
	
	#------------Carpeta CREATE / UPDATE / DELETE
	public static function CarpetaCrudC(){

		#------------¿Tiene Permiso Admin?
		$EsAdminC = AdminC::EsAdminC();

		#------------Defino ID y accion
		if(isset($_POST['ID_carpeta'])){
			$ID_carpeta = sanitize_xss($_POST['ID_carpeta']);
		}
		if(isset($_GET['ID_carpeta'])){
			$ID_carpeta = sanitize_xss($_GET['ID_carpeta']);
		}
		$action = sanitize_xss($_GET['action']); //insert, update, delete

		#------------Defino primary key para Queries
        $table ='carpetas';
		$primarykey = 'ID_carpeta';
		$primaryvalue = $ID_carpeta;

		#------------CREATE o UPDATE
		if($action == 'insert' || $action == 'update'){

			#------------Validacion Campos Obligatorios
			if(
				$_POST['ID_seccion'] == '0'
				|| $_POST['ID_categoria'] == '0'
				|| $_POST['carpeta'] == ''
			){
				$msg_error = 'Debe llenar todo los campos requeridos, resaltados con línea roja.<br><br>Por favor inténtelo de nuevo.';
				include('views/admin-error.php');
				die();
			}
			
			#------------Array Valores Formulario
			$parameters = array(
				'ID_seccion' => sanitize_xss($_POST['ID_seccion']),
				'ID_categoria' => sanitize_xss($_POST['ID_categoria']),
				'carpeta' => sanitize_xss($_POST['carpeta'])
			);

			#------------CREATE
			if($action == 'insert'){
				$query = PreparedStatementsM::InsertM($parameters, $table);
			}

			#------------UPDATE
			if($action == 'update'){
				$query = PreparedStatementsM::UpdateM($parameters, $table, $primarykey, $primaryvalue);
			}

		}

		#------------DELETE
		if($action == 'delete'){

			#------------Elimino Carpeta
			$query = PreparedStatementsM::DeleteM($table, $primarykey, $primaryvalue);

			#------------Elimino Links
			$table ='intranet_links';
			$query = PreparedStatementsM::DeleteM($table, $primarykey, $primaryvalue);

		}

		#------------Redirige
		$redirige = '../../admin-carpetas/ok/'.$action;
		header('Location:'.$redirige);
		die();

	}



	#-----------------------------------------------------------------------------
	# LINKS
	#-----------------------------------------------------------------------------
	
	#------------Listado Links
	public static function LinksC(){

		#------------¿Tiene Permiso Admin?
		$EsAdminC = AdminC::EsAdminC();

		#---Secciones Activas
		$seccionesMenu = RegistrosBdM::GetSeccionesM('1');

		#------------Carga Registros
		$Registros = RegistrosBdM::GetLinksM();
		//echo json_encode($Registros);die;

		#------------Carga View
		include('views/admin-links.php');
		die();

	}
	
	#------------Carga Form Link (Detalle)
	public static function LinkC(){
		
		#------------¿Tiene Permiso Admin?
		$EsAdminC = AdminC::EsAdminC();

		#---Secciones Activas
		$seccionesMenu = RegistrosBdM::GetSeccionesM('1');

		#------------Variables por defecto (Llenar formulario NEW)
		$ID_link = '0';
		$ID_seccion = '0';		
		$ID_categoria = '0';
		$ID_carpeta = '0';
		$linkNombre = '';
		$linkEnlace = '';

		#------------Defino cual es la tarea del formulario
		$task = sanitize_xss($_GET['task']);
		if($task == 'new'){
			$txt_titulo = 'Nuevo Link';
			$txt_btn_form = 'Guardar';
			$accionSubmit = 'new/insert';	
		}
		if($task == 'edit'){
			$txt_titulo = 'Editar Link';
			$txt_btn_form = 'Guardar';
			$accionSubmit = 'edit/update';	
		}

		#------------Cargo info de BD si es EDIT
		if($task == 'edit'){
			$ID_link = sanitize_xss($_GET['ID_link']);
			$link = RegistrosBdM::GetLinkM($ID_link);
			$ID_seccion = $link['ID_seccion'];
			$ID_categoria = $link['ID_categoria'];
			$ID_carpeta = $link['ID_carpeta'];
			$linkNombre = $link['linkNombre'];
			$linkEnlace = $link['linkEnlace'];
		}

		#------------Cargo Lista Categorias
		$carpetas = RegistrosBdM::GetCarpetasM();

		#------------Carga View
		include('views/admin-link.php');
		die();

	}
	
	#------------Link CREATE / UPDATE / DELETE
	public static function LinkCrudC(){

		#------------¿Tiene Permiso Admin?
		$EsAdminC = AdminC::EsAdminC();

		#------------Defino ID y accion
		if(isset($_POST['ID_link'])){
			$ID_link = sanitize_xss($_POST['ID_link']);
		}
		if(isset($_GET['ID_link'])){
			$ID_link = sanitize_xss($_GET['ID_link']);
		}
		$action = sanitize_xss($_GET['action']); //insert, update, delete

		#------------Defino primary key para Queries
        $table ='intranet_links';
		$primarykey = 'ID_link';
		$primaryvalue = $ID_link;

		#------------CREATE o UPDATE
		if($action == 'insert' || $action == 'update'){

			#------------Validacion Campos Obligatorios
			if(
				$_POST['ID_seccion'] == '0'
				|| $_POST['ID_categoria'] == '0'
				|| $_POST['ID_carpeta'] == '0'
				|| $_POST['linkNombre'] == ''
				|| $_POST['linkEnlace'] == ''
			){
				$msg_error = 'Debe llenar todo los campos requeridos, resaltados con línea roja.<br><br>Por favor inténtelo de nuevo.';
				include('views/admin-error.php');
				die();
			}
			
			#------------Array Valores Formulario
			$parameters = array(
				'ID_seccion' => sanitize_xss($_POST['ID_seccion']),
				'ID_categoria' => sanitize_xss($_POST['ID_categoria']),
				'ID_carpeta' => sanitize_xss($_POST['ID_carpeta']),
				'linkNombre' => sanitize_xss($_POST['linkNombre']),
				'linkEnlace' => sanitize_xss_url($_POST['linkEnlace'])
			);

			#------------CREATE
			if($action == 'insert'){
				$query = PreparedStatementsM::InsertM($parameters, $table);
			}

			#------------UPDATE
			if($action == 'update'){
				$query = PreparedStatementsM::UpdateM($parameters, $table, $primarykey, $primaryvalue);
			}

		}

		#------------DELETE
		if($action == 'delete'){

			#------------Elimino Link
			$query = PreparedStatementsM::DeleteM($table, $primarykey, $primaryvalue);

		}

		#------------Redirige
		$redirige = '../../admin-links/ok/'.$action;
		header('Location:'.$redirige);
		die();

	}




	#-----------------------------------------------------------------------------
	# ADMINISTRADORES
	#-----------------------------------------------------------------------------
	
	#------------Listado Administradores
	public static function AdministradoresC(){

		#------------¿Tiene Permiso Admin?
		$EsAdminC = AdminC::EsAdminC();

		#---Secciones Activas
		$seccionesMenu = RegistrosBdM::GetSeccionesM('1');

		#------------Carga Registros
		$Registros = RegistrosBdM::GetAdministradoresM();
		//echo json_encode($Registros);die;

		#------------Carga View
		include('views/admin-administradores.php');
		die();

	}
	
	#------------Carga Form Administrador (Detalle)
	public static function AdministradorC(){
		
		#------------¿Tiene Permiso Admin?
		$EsAdminC = AdminC::EsAdminC();

		#---Secciones Activas
		$seccionesMenu = RegistrosBdM::GetSeccionesM('1');

		#------------Variables por defecto (Llenar formulario NEW)
		$ID_admin = '0';
		$empleadoDocumento = '';		

		#------------Defino cual es la tarea del formulario
		$task = sanitize_xss($_GET['task']);
		if($task == 'new'){
			$txt_titulo = 'Nuevo Administrador';
			$txt_btn_form = 'Guardar';
			$accionSubmit = 'new/insert';	
		}
		if($task == 'edit'){
			$txt_titulo = 'Editar Administrador';
			$txt_btn_form = 'Guardar';
			$accionSubmit = 'edit/update';	
		}

		#------------Cargo info de BD si es EDIT
		if($task == 'edit'){
			$ID_admin = sanitize_xss($_GET['ID_admin']);
			$administrador = RegistrosBdM::GetLinkM($ID_admin);
			$empleadoDocumento = $administrador['empleadoDocumento'];
		}

		#------------Carga View
		include('views/admin-administrador.php');
		die();

	}
	
	#------------Administrador CREATE / UPDATE / DELETE
	public static function AdministradorCrudC(){

		#------------¿Tiene Permiso Admin?
		$EsAdminC = AdminC::EsAdminC();

		#------------Defino ID y accion
		if(isset($_POST['ID_admin'])){
			$ID_admin = sanitize_xss($_POST['ID_admin']);
		}
		if(isset($_GET['ID_admin'])){
			$ID_admin = sanitize_xss($_GET['ID_admin']);
		}
		$action = sanitize_xss($_GET['action']); //insert, update, delete

		#------------Defino primary key para Queries
        $table ='administradores';
		$primarykey = 'ID_admin';
		$primaryvalue = $ID_admin;

		#------------CREATE o UPDATE
		if($action == 'insert' || $action == 'update'){

			#------------Validacion Campos Obligatorios
			if(
				$_POST['empleadoDocumento'] == ''
			){
				$msg_error = 'Debe llenar todo los campos requeridos, resaltados con línea roja.<br><br>Por favor inténtelo de nuevo.';
				include('views/admin-error.php');
				die();
			}
			
			#------------Array Valores Formulario
			$parameters = array(
				'empleadoDocumento' => sanitize_xss($_POST['empleadoDocumento'])
			);

			#------------CREATE
			if($action == 'insert'){
				$query = PreparedStatementsM::InsertM($parameters, $table);
			}

			#------------UPDATE
			if($action == 'update'){
				$query = PreparedStatementsM::UpdateM($parameters, $table, $primarykey, $primaryvalue);
			}

		}

		#------------DELETE
		if($action == 'delete'){

			#------------Elimino Link
			$query = PreparedStatementsM::DeleteM($table, $primarykey, $primaryvalue);

		}

		#------------Redirige
		$redirige = '../../admin-administradores/ok/'.$action;
		header('Location:'.$redirige);
		die();

	}




	#-----------------------------------------------------------------------------
	# CSV EMPLEADOS
	#-----------------------------------------------------------------------------

	#------------Pantalla Formulario
	public static function CsvEmpleadosC(){

		#------------¿Tiene Permiso Admin?
		$EsAdminC = AdminC::EsAdminC();

		#---Secciones Activas
		$seccionesMenu = RegistrosBdM::GetSeccionesM('1');

		#------Consulto cantidad total de empleados en BD
		$empleados = RegistrosBdM::GetEmpleadosM();
		$cantidadEmpleados = count($empleados);

		#---Carga Vista
		include('views/admin-empleados.php');
		die();
		
	}

	#------------Descarga Plantilla CSV
	public static function CsvEmpleadosDownloadC(){

		#------------¿Tiene Permiso Admin?
		$EsAdminC = AdminC::EsAdminC();

		#---Secciones Activas
		$seccionesMenu = RegistrosBdM::GetSeccionesM('1');

		#------Consulto cantidad total de empleados en BD
		$empleados = RegistrosBdM::GetEmpleadosM();

		#---Incluye vista
		$fileName = 'planta-empleados-intranet.csv';
		include('views/admin-empleados-csv-download.php');
		die();

	}

	#------------Upload CSV
	public static function CsvEmpleadosUploadC(){

		#------------¿Tiene Permiso Admin?
		$EsAdminC = AdminC::EsAdminC();

		#------------Validaciones archivo CSV (tipo y peso)
		$file = $_FILES['archivo_csv'];
		$fileType = $file['type'];
		$fileSize = $file['size'];
		$msg_error = UploadsC::ValidaCsvC($fileType, $fileSize);
		if($msg_error != ''){
			include('views/admin-error.php');
			die();
		}

		#------------CSV Parser
		require_once('libs/parsecsv-for-php/parsecsv.lib.php');
		$csv = new parseCSV();
		$csv->auto($file['tmp_name']);
		//print_r($csv->data);die();

		#------------Validaciones formato correcto de cada fila
		foreach ($csv->data as $linea){

			#---Empleado Documento
			if(
				$linea['empleadoDocumento'] == ''
				|| ( $linea['empleadoDocumento'] != '' && !is_numeric($linea['empleadoDocumento']) )
			){
				$msg_error = 'La columna empleadoDocumento no puede estar vacía; sólo puede contener números.<br><br>';
				include('views/admin-error.php');
				die();	
			}

			#---Campos Requeridos 
			if(
				$linea['empleadoNombre'] == ''
				|| $linea['empleadoEmail'] == ''
			){
				$msg_error = 'Las columnas empleadoNombre y empleadoEmail no pueden estar vacías.<br><br>Problema en la fila del empleado con documento '.$linea['empleadoDocumento'].'<br><br>';
				include('views/admin-error.php');
				die();	
			}

			#---Empleado Email
			if( !filter_var($linea['empleadoEmail'], FILTER_VALIDATE_EMAIL) ){
				$msg_error = 'La columna empleadoEmail deben tener un email válido.<br><br>Problema en la fila del empleado con documento '.$linea['empleadoDocumento'].'<br><br>';
				include('views/admin-error.php');
				die();	
			}

		}

		
		#------------Dejo completamente limpia la Tabla (truncate table)
		$table = 'empleados';
		$limpiarEmpleados = PreparedStatementsM::TruncateTableM($table);

		#------------Fecha UpdatedAt
		$updatedAt = date('Y-m-d H:i:s');

		#------------Inserto usuario SuperAdmin EMOTICA
		$parameters = array(
			'empleadoDocumento' => '98623455',
			'empleadoNombre' => 'JUAN PABLO RAMIREZ JARAMILLO',
			'empleadoEmail' => 'info@emoticaweb.com',
			'empleadoPassword' => crypt_pass('98623455'),
			'updatedAt' => $updatedAt
		);
		$ID_empleado = PreparedStatementsM::InsertM($parameters, $table);
		//echo $ID_empleado; die;

		#------------Array Control de Documentos de Empleados creados (Emótica por defecto)
		$controlDocumentosCreados = array(
			'98623455'
		);

		#------------Loop CSV
		foreach ($csv->data as $linea){

			#------------Insertar empleado					
			$parameters = array(
				'empleadoDocumento' => trim($linea['empleadoDocumento']),
				'empleadoNombre' => $linea['empleadoNombre'],
				'empleadoEmail' => trim(strtolower($linea['empleadoEmail'])),
				'empleadoPassword' => crypt_pass(trim($linea['empleadoDocumento'])),
				'updatedAt' => $updatedAt
			);
			$ID_empleado = PreparedStatementsM::InsertM($parameters, $table);

			#----Inserto Numero de Documento en Array Control
			$controlDocumentosCreados[] = $linea['empleadoDocumento'];

		}
		//echo json_encode($controlDocumentosCreados);die;

		#------------Primary Key para Elimino empleados inexistentes en tablas relacionadas
		$primarykey = 'empleadoDocumento';

		#------------Elimino de tabla Admin
		$administradores = RegistrosBdM::GetAdministradoresM();
		foreach($administradores as $registro){
			$empleadoDocumento = $registro['empleadoDocumento'];
			if(!in_array($empleadoDocumento, $controlDocumentosCreados)){
				$primaryvalue = $empleadoDocumento;
				$table ='administradores';
				$query = PreparedStatementsM::DeleteM($table, $primarykey, $primaryvalue);	
			}
		}

		#------------Redirige
		$redirige = 'admin-empleados-hecho';
		header('Location:'.$redirige);
		die();

	}




	#-----------------------------------------------------------------------------
	# ENCRIPTAR PASSWORDS
	#-----------------------------------------------------------------------------

	#------------Encripta masivamente todas las contraseñas (usando el # documento como contraseña)
	public static function EncriptarContrasenasC(){
		AdminC::EsAdminC();
		#Tabla a actualizar
		$table ='empleados';
		$primarykey = 'ID_empleado';
		#Loop todos los empleados en BD
		$Registros = RegistrosBdM::GetEmpleadosM();
		foreach($Registros as $registro){
			$primaryvalue = $registro['ID_empleado'];
			$parameters = array(
                'empleadoEmail' => trim(strtolower($registro['empleadoEmail'])),
				'empleadoPassword' => crypt_pass($registro['empleadoDocumento']),
				'updatedAt' => date('Y-m-d H:i:s')
			);
			#Actualizo en BD
			$query = PreparedStatementsM::UpdateM($parameters, $table, $primarykey, $primaryvalue);
		}
		#Termina
		echo('Se actualizaron todas las contraseñas encriptadas');
		die();	
	}



}
?>
