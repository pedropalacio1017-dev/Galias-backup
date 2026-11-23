<?php
class AdminC{
	
	
	#-----------------------------------------------------------------------------
	# VALIDACION
	#-----------------------------------------------------------------------------
	
	#------------Carga Pantalla Login
	public static function LoadC(){
		include('views/panel/acceso.php');
		die();
	}
	
public static function LoginC() {

    // Validar existencia de los campos del formulario
    if (
        !isset($_POST['usuarioEmail']) ||
        !isset($_POST['usuarioContrasena']) ||
        empty($_POST['usuarioEmail']) ||
        empty($_POST['usuarioContrasena'])
    ) {
        // Mostrar la vista de login si no se han enviado los datos
        self::LoadC(); // Carga acceso.php y hace die()
    }

    // Sanitizo variables
    $usuarioEmail = sanitize_xss($_POST['usuarioEmail']);
    $usuarioContrasena = sanitize_xss_url($_POST['usuarioContrasena']);

    // Consulto en BD
    $usuario = RegistrosBdM::LoginM($usuarioEmail, crypt_pass($usuarioContrasena));

    // No existe
    if ($usuario == NULL || $usuario['usuarioActivo'] == '0') {
        $redirige = URL_BASE . 'panel/login/error';
    }
    // Sí existe
    else {
        session_start();
        $_SESSION['logueado'] = 'SI';
        $_SESSION['usuario'] = $usuario;
        $redirige = URL_BASE . 'panel/dashboard';
    }

    // Redirige
    header('Location:' . $redirige);
    die();
}  // 👈 ESTA LLAVE CIERRA EL MÉTODO


	
	#------------Seguridad Admin: Redireccion para accesos a paginas sin haberse logueado
	public static function IsNotLoguedC($usuarioRol = NULL){

		#----Set
		$impedirAcceso = false;

		#----Abro sesion
		session_start();

		#-----Esta Logueado
		if(!isset($_SESSION['logueado']) || $_SESSION['logueado'] != 'SI') {
			$impedirAcceso = true;
		}

		#-----Permito solo a SuperAdmin
		if($usuarioRol == 'SUPERADMIN' && $_SESSION['usuario']['usuarioRol'] != 'SUPERADMIN'){
			$impedirAcceso = true;	
		}

		#-----Impedir acceso
		if($impedirAcceso == true){
			$redirige = URL_BASE.'panel/logout';	
			header('Location:'.$redirige);
			die();
		}



	}
	
	#------------Hace Logout
	public static function LogoutC(){

		session_start();
		session_unset();
		session_destroy();

		#------Redirige
		$redirige = URL_BASE.'acceso';	
		header('Location:'.$redirige);
		die();

	}
	
	
	#-----------------------------------------------------------------------------
	# DASHBOARD
	#-----------------------------------------------------------------------------
	
	#------------Carga Dashboard
	public static function DashboardC(){

		#------------Seguridad: ¿Está logueado?
		$IsNotLoguedC = AdminC::IsNotLoguedC();

		#------------Carga View
		include('views/panel/dashboard.php');
		die();

	}

	
	#-----------------------------------------------------------------------------
	# PROYECTOS
	#-----------------------------------------------------------------------------
	
	#------------Carga Proyectos (Lista)
	public static function ProyectosC(){

		#------------Seguridad: ¿Está logueado?
		$IsNotLoguedC = AdminC::IsNotLoguedC('SUPERADMIN');

		#------------Carga Registros
		$Registros = RegistrosBdM::GetProyectosM();

		#------------Carga View
		include('views/panel/proyectos.php');
		die();

	}
	
	#------------Carga Form Proyecto (Detalle)
	public static function ProyectoC(){

		#------------Seguridad: ¿Está logueado?
		$IsNotLoguedC = AdminC::IsNotLoguedC('SUPERADMIN');

		#------------Variables por defecto (Llenar formulario NEW)
		$ID_proyecto = '0';
		$ID_regional = '0';
		$proyectoNombre = '';
		$proyectoLogo = '';
		$proyectoActivo = '0';
		$proyectoCodigoCrm = '';
		$proyectoCodigoMacroSinco = '';
		$proyectoEmail = '';
		$proyectoAreaConstruida = '';
		$proyectoAreaPrivada = '';
		$proyectoLinkWeb = '';
		$proyectoLinkBrochure = '';
		$proyectoLinkWaze = '';
		$proyectoLinkDocumentosFirma = '';
		$proyectoImgMapa = '';
		$proyectoImgFortalezas = '';
		$ID_notaLegal = '0';

		#------------Defino cual es la tarea del formulario
		$task = sanitize_xss($_GET['task']);
		if($task == 'new'){
			$txt_titulo = 'Nuevo Proyecto';
			$txt_btn_form = 'GUARDAR';
			$accionSubmit = 'new/insert';	
		}
		if($task == 'edit'){
			$txt_titulo = 'Editar Proyecto';
			$txt_btn_form = 'GUARDAR';
			$accionSubmit = 'edit/update';	
		}

		#------------Cargo info de BD si es EDIT
		if($task == 'edit'){
			$ID_proyecto = sanitize_xss($_GET['ID_proyecto']);
			$proyecto = RegistrosBdM::GetProyectoM($ID_proyecto);
			$ID_regional = $proyecto['ID_regional'];
			$proyectoNombre = $proyecto['proyectoNombre'];
			$proyectoLogo = $proyecto['proyectoLogo'];
			$proyectoActivo = $proyecto['proyectoActivo'];
			$proyectoCodigoCrm = $proyecto['proyectoCodigoCrm'];
			$proyectoCodigoMacroSinco = $proyecto['proyectoCodigoMacroSinco'];
			$proyectoEmail = $proyecto['proyectoEmail'];
			$proyectoAreaConstruida = $proyecto['proyectoAreaConstruida'];
			$proyectoAreaPrivada = $proyecto['proyectoAreaPrivada'];
			$proyectoLinkWeb = $proyecto['proyectoLinkWeb'];
			$proyectoLinkBrochure = $proyecto['proyectoLinkBrochure'];
			$proyectoLinkWaze = $proyecto['proyectoLinkWaze'];
			$proyectoLinkDocumentosFirma = $proyecto['proyectoLinkDocumentosFirma'];
			$proyectoImgMapa = $proyecto['proyectoImgMapa'];
			$proyectoImgFortalezas = $proyecto['proyectoImgFortalezas'];
			$ID_notaLegal = $proyecto['ID_notaLegal'];
		}

		#------------Notas Legales
		$notasLegales = RegistrosBdM::GetNotasLegalesM();

		#------------Cargo Lista Regionales
		$regionales = RegistrosBdM::GetRegionalesM();

		#------------Subproyectos
		$subproyectos = RegistrosBdM::GetSubproyectosM($ID_proyecto, NULL, NULL);

		#------------Carga View
		include('views/panel/proyecto.php');
		die();

	}
	
	#------------Proyecto CREATE / UPDATE / DELETE
	public static function ProyectoCrudC(){

		#------------Seguridad: ¿Está logueado?
		$IsNotLoguedC = AdminC::IsNotLoguedC('SUPERADMIN');

		#------------Defino ID y accion
		if(isset($_POST['ID_proyecto'])){
			$ID_proyecto = sanitize_xss($_POST['ID_proyecto']);
		}
		if(isset($_GET['ID_proyecto'])){
			$ID_proyecto = sanitize_xss($_GET['ID_proyecto']);
		}
		$action = sanitize_xss($_GET['action']); //insert, update, delete

		#------------Defino primary key para Queries
        $table ='proyectos';
		$primarykey = 'ID_proyecto';
		$primaryvalue = $ID_proyecto;

		#------------CREATE o UPDATE
		if($action == 'insert' || $action == 'update'){

			#------------Validacion Campos Obligatorios
			if(
				$_POST['proyectoNombre'] == ''
				|| $_POST['ID_regional'] == '0'
				//|| $_POST['proyectoCodigoCrm'] == ''
				|| $_POST['proyectoCodigoMacroSinco'] == ''
				|| ($_POST['proyectoLogo'] == '' && $_FILES['proyectoLogo']['name'] == '')
			){
				$msg_error = 'Debe llenar todo los campos requeridos, resaltados con línea roja.<br><br>Por favor inténtelo de nuevo.';
				include('views/panel/error.php');
				die();
			}
			
			#------------LOGO
			$proyectoLogo_anterior = sanitize_xss($_POST['proyectoLogo']);
			$proyectoLogo = $proyectoLogo_anterior;
			if($_FILES['proyectoLogo']['size'] > 0){
				#------------Elimino anterior de existir
				@unlink(RUTA_UPLOADS.$proyectoLogo_anterior);
				#------------Campo imagen
				$file = $_FILES['proyectoLogo'];
				$imageType = $file['type'];
				$imageSize = $file['size'];
				$imageTmpName = $file['tmp_name'];
				$imageName = $file['name'];
				#------------Valido peso y formato imagen
				$invalido_txt = UploadsC::ValidaImagenC($imageType, $imageSize);
				if($invalido_txt != ''){
					$msg_error = $invalido_txt.'<br><br>Por favor inténtelo de nuevo.';
					include('views/panel/error.php');
					die();
				}
				#------------Upload y Resize Imagen
				$procesos = array(
					'grande_generar' => '1',
					'grande_escalado' => '1',
					'grande_width' => '240',
					'grande_height' => '200',
					'mini_generar' => '',
					'mini_escalado' => '',
					'mini_width' => '',
					'mini_height' => ''
				);
				$uploads = UploadsC::UploadImagenC($imageTmpName, $imageName, $procesos);
				$proyectoLogo = $uploads[0];	
			}

			#------------IMG MAPA
			$proyectoImgMapa_anterior = sanitize_xss($_POST['proyectoImgMapa']);
			$proyectoImgMapa = $proyectoImgMapa_anterior;
			if($_FILES['proyectoImgMapa']['size'] > 0){
				#------------Elimino anterior de existir
				@unlink(RUTA_UPLOADS.$proyectoImgMapa_anterior);
				#------------Campo imagen
				$file = $_FILES['proyectoImgMapa'];
				$imageType = $file['type'];
				$imageSize = $file['size'];
				$imageTmpName = $file['tmp_name'];
				$imageName = $file['name'];
				#------------Valido peso y formato imagen
				$invalido_txt = UploadsC::ValidaImagenC($imageType, $imageSize);
				if($invalido_txt != ''){
					$msg_error = $invalido_txt.'<br><br>Por favor inténtelo de nuevo.';
					include('views/panel/error.php');
					die();
				}
				#------------Upload y Resize Imagen
				$procesos = array(
					'grande_generar' => '1',
					'grande_escalado' => '2',
					'grande_width' => '600',
					'grande_height' => '',
					'mini_generar' => '',
					'mini_escalado' => '',
					'mini_width' => '',
					'mini_height' => ''
				);
				$uploads = UploadsC::UploadImagenC($imageTmpName, $imageName, $procesos);
				$proyectoImgMapa = $uploads[0];	
			}

			#------------IMG FLYER PROYECTO
			$proyectoImgFortalezas_anterior = sanitize_xss($_POST['proyectoImgFortalezas']);
			$proyectoImgFortalezas = $proyectoImgFortalezas_anterior;
			if($_FILES['proyectoImgFortalezas']['size'] > 0){
				#------------Elimino anterior de existir
				@unlink(RUTA_UPLOADS.$proyectoImgFortalezas_anterior);
				#------------Campo imagen
				$file = $_FILES['proyectoImgFortalezas'];
				$imageType = $file['type'];
				$imageSize = $file['size'];
				$imageTmpName = $file['tmp_name'];
				$imageName = $file['name'];
				#------------Valido peso y formato imagen
				$invalido_txt = UploadsC::ValidaImagenC($imageType, $imageSize);
				if($invalido_txt != ''){
					$msg_error = $invalido_txt.'<br><br>Por favor inténtelo de nuevo.';
					include('views/panel/error.php');
					die();
				}
				#------------Upload y Resize Imagen
				$procesos = array(
					'grande_generar' => '1',
					'grande_escalado' => '2',
					'grande_width' => '600',
					'grande_height' => '',
					'mini_generar' => '',
					'mini_escalado' => '',
					'mini_width' => '',
					'mini_height' => ''
				);
				$uploads = UploadsC::UploadImagenC($imageTmpName, $imageName, $procesos);
				$proyectoImgFortalezas = $uploads[0];	
			}

			#------------Array Valores Formulario
			$parameters = array(
				'ID_regional' => sanitize_xss($_POST['ID_regional']),
				'proyectoNombre' => sanitize_xss($_POST['proyectoNombre']),
				'proyectoLogo' => $proyectoLogo,
				'proyectoActivo' => '0',
				'proyectoCodigoCrm' => sanitize_xss($_POST['proyectoCodigoCrm']),
				'proyectoCodigoMacroSinco' => sanitize_xss($_POST['proyectoCodigoMacroSinco']),
				'proyectoEmail' => sanitize_xss($_POST['proyectoEmail']),
				'proyectoAreaConstruida' => sanitize_xss($_POST['proyectoAreaConstruida']),
				'proyectoAreaPrivada' => sanitize_xss($_POST['proyectoAreaPrivada']),
				'proyectoLinkWeb' => sanitize_xss_url($_POST['proyectoLinkWeb']),
				'proyectoLinkBrochure' => sanitize_xss_url($_POST['proyectoLinkBrochure']),
				'proyectoLinkWaze' => sanitize_xss_url($_POST['proyectoLinkWaze']),
				'proyectoLinkDocumentosFirma' => sanitize_xss_url($_POST['proyectoLinkDocumentosFirma']),
				'proyectoImgMapa' => $proyectoImgMapa,
				'proyectoImgFortalezas' => $proyectoImgFortalezas,
				'ID_notaLegal' => sanitize_xss($_POST['ID_notaLegal']),
			);
			if(isset($_POST['proyectoActivo'])){
				$parameters['proyectoActivo'] = '1';
			}

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

			#------------Elimino Logo
			$proyecto = RegistrosBdM::GetProyectoM($ID_proyecto);
			@unlink(RUTA_UPLOADS.$proyecto['proyectoLogo']);
			@unlink(RUTA_UPLOADS.$proyecto['proyectoImgMapa']);
			@unlink(RUTA_UPLOADS.$proyecto['proyectoImgFortalezas']);

			#------------Elimino Proyecto
			$query = PreparedStatementsM::DeleteM($table, $primarykey, $primaryvalue);

			#------------Elimina los subproyectos
			$table ='subproyectos';
			$query = PreparedStatementsM::DeleteM($table, $primarykey, $primaryvalue);

		}

		#------------Redirige
		$redirige = '../../proyectos/ok/'.$action;
		header('Location:'.$redirige);
		die();
	}



	#-----------------------------------------------------------------------------
	# SUBPROYECTO
	#-----------------------------------------------------------------------------
	
	#------------Carga Form Subproyecto (Detalle)
	public static function SubproyectoC(){

		#------------Seguridad: ¿Está logueado?
		$IsNotLoguedC = AdminC::IsNotLoguedC('SUPERADMIN');

		#------------Variables por defecto (Llenar formulario NEW)
		$ID_subproyecto = '0';
		$ID_proyecto = sanitize_xss($_GET['ID_proyecto']);
		$subproyectoNombre = '';
		$subproyectoEntregas = '';
		$subproyectoFechaMaxPago = '';
		$subproyectoPrecio = '0';
		$subproyectoValorSeparacion = '0';
		$subproyectoActivo = '0';
		$subproyectoCodigoCrm = '';

		#------------Proyecto
		$proyecto = RegistrosBdM::GetProyectoM($ID_proyecto);

		#------------Defino cual es la tarea del formulario
		$task = $_GET['task'];
		if($task == 'new'){
			$txt_titulo = 'Nuevo Subproyecto de '.$proyecto['proyectoNombre'];
			$txt_btn_form = 'GUARDAR';
			$accionSubmit = 'new/insert';	
		}
		if($task == 'edit'){
			$txt_titulo = 'Editar Subproyecto de '.$proyecto['proyectoNombre'];
			$txt_btn_form = 'GUARDAR';
			$accionSubmit = 'edit/update';	
		}

		#------------Cargo info de BD si es EDIT
		if($task == 'edit'){
			$ID_subproyecto = $_GET['ID_subproyecto'];
			$subproyecto = RegistrosBdM::GetSubproyectoM($ID_subproyecto);
			$subproyectoNombre = $subproyecto['subproyectoNombre'];
			$subproyectoEntregas = $subproyecto['subproyectoEntregas'];
			$subproyectoFechaMaxPago = $subproyecto['subproyectoFechaMaxPago'];
			$subproyectoPrecio = $subproyecto['subproyectoPrecio'];
			$subproyectoValorSeparacion = $subproyecto['subproyectoValorSeparacion'];
			$subproyectoActivo = $subproyecto['subproyectoActivo'];
			$subproyectoCodigoCrm = $subproyecto['subproyectoCodigoCrm'];
		}

		#------------Carga View
		include('views/panel/subproyecto.php');
		die();

	}

	#------------Subproyecto CREATE / UPDATE / DELETE
	public static function SubproyectoCrudC(){

		#------------Seguridad: ¿Está logueado?
		$IsNotLoguedC = AdminC::IsNotLoguedC('SUPERADMIN');

		#------------Defino ID y accion
		if(isset($_POST['ID_subproyecto'])){
			$ID_subproyecto = sanitize_xss($_POST['ID_subproyecto']);
			$ID_proyecto = sanitize_xss($_POST['ID_proyecto']);
		}
		if(isset($_GET['ID_subproyecto'])){
			$ID_subproyecto = sanitize_xss($_GET['ID_subproyecto']);
			$ID_proyecto = sanitize_xss($_GET['ID_proyecto']);
		}
		$action = sanitize_xss($_GET['action']); //insert, update, delete

		#------------Defino primary key para Queries
        $table ='subproyectos';
		$primarykey = 'ID_subproyecto';
		$primaryvalue = $ID_subproyecto;

		#------------CREATE o UPDATE
		if($action == 'insert' || $action == 'update'){

			#------------Validacion Campos Obligatorios
			if(
				$_POST['subproyectoNombre'] == ''
				|| $_POST['subproyectoEntregas'] == ''
				|| $_POST['subproyectoFechaMaxPago'] == ''
				|| $_POST['subproyectoPrecio'] == '' || $_POST['subproyectoPrecio'] == '0'
				|| $_POST['subproyectoValorSeparacion'] == '' || $_POST['subproyectoValorSeparacion'] == '0'
			){
				$msg_error = 'Debe llenar todo los campos requeridos, resaltados con línea roja.<br><br>Por favor inténtelo de nuevo.';
				include('views/panel/error.php');
				die();
			}

			#------------Formato Fecha y Precios
			$subproyectoFechaMaxPago = fecha_AAAAMMDD(sanitize_xss($_POST['subproyectoFechaMaxPago']));
			$subproyectoPrecio = sin_separadores_miles(sanitize_xss($_POST['subproyectoPrecio']));
			$subproyectoValorSeparacion = sin_separadores_miles(sanitize_xss($_POST['subproyectoValorSeparacion']));
			
			#------------Array Valores Formulario
			$parameters = array(
				'ID_proyecto' => sanitize_xss($_POST['ID_proyecto']),
				'subproyectoNombre' => sanitize_xss($_POST['subproyectoNombre']),
				'subproyectoEntregas' => sanitize_xss($_POST['subproyectoEntregas']),
				'subproyectoFechaMaxPago' => $subproyectoFechaMaxPago,
				'subproyectoPrecio' => $subproyectoPrecio,
				'subproyectoValorSeparacion' => $subproyectoValorSeparacion,
				'subproyectoCodigoCrm' => sanitize_xss($_POST['subproyectoCodigoCrm']),
				'subproyectoActivo' => '0'
			);
			if(isset($_POST['subproyectoActivo'])){
				$parameters['subproyectoActivo'] = '1';
			}

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
			$query = PreparedStatementsM::DeleteM($table, $primarykey, $primaryvalue);
		}

		#------------Redirige
		$redirige = sprintf(
			'../../../../proyecto/edit/%s#subproyectos',
			$ID_proyecto
		);
		header('Location:'.$redirige);
		die();
	}	
	
	
	#-----------------------------------------------------------------------------
	# CONTADORES COTIZACIONES
	#-----------------------------------------------------------------------------
	
	#------------Carga Contadores
	public static function ContadoresC(){

		#------------Seguridad: ¿Está logueado?
		$IsNotLoguedC = AdminC::IsNotLoguedC('SUPERADMIN');

		#------------Conteo Registros Totales
		$ContadorTodos = count(RegistrosBdM::GetConteoCotizacionesM('',''));

		#------------Conteo Mes Actual
		$mes_txt = mesNumero_a_mesTexto(date('n'));
		$year = date('Y');
		$desde = date('Y-m-01');
		$hasta = substr($desde,0,7).'-31';
		$ConteoMes = count(RegistrosBdM::GetConteoCotizacionesM($desde, $hasta));
		$ContadoresMeses = array(
			array(
				$mes_txt.' '.$year,
				$ConteoMes
			)
		);

		#------------6 Meses anteriores
		for ($i = 1; $i <= 6; $i++) {
			$mes_txt = mesNumero_a_mesTexto(date('n', strtotime(date('Y-m-d').'-'.$i.'months')));
			$year = date('Y', strtotime(date('Y-m-d').'-'.$i.'months'));
			$desde = date('Y-m-d', strtotime(date('Y-m-01').'-'.$i.'months'));
			$hasta = substr($desde,0,7).'-31';
			$ConteoMes = count(RegistrosBdM::GetConteoCotizacionesM($desde, $hasta));
			$ContadoresMeses[] = array(
				$mes_txt.' '.$year,
				$ConteoMes
			);
		}

		#------------Carga View
		include('views/panel/contadores.php');
		die();
	}



	#-----------------------------------------------------------------------------
	# VALORES BASE COTIZADOR
	#-----------------------------------------------------------------------------

	#------------Carga Formulario
	public static function VariablesCotizadorC(){

		#------------Seguridad: ¿Está logueado?
		$IsNotLoguedC = AdminC::IsNotLoguedC('SUPERADMIN');
		
		#---Variables Cotizador
		$varsCotizador = RegistrosBdM::GetVarsCotizadorM();

		#---Carga vista
		include('views/panel/variables-cotizador.php');
		die();

	}
	
	#------------Update Variables
	public static function VariablesCotizadorUpdateC(){

		#------------Seguridad: ¿Está logueado?
		$IsNotLoguedC = AdminC::IsNotLoguedC('SUPERADMIN');

		#------------Validacion Campos Obligatorios
		if(
			!is_numeric($_POST['smlv'])
			|| !is_numeric($_POST['subsidioVis_0_2'])
			|| !is_numeric($_POST['subsidioVis_2_4'])
			|| !is_numeric($_POST['factorMillonUvr30'])
		){
			$msg_error = 'Debe llenar todo los campos requeridos, resaltados con línea roja.<br><br>Los campos sólo pueden contener números, sin separadores de miles.<br><br>Por favor inténtelo de nuevo.';
			include('views/panel/error.php');
			die();
		}

		#------------ID_vars
		$ID_variables = sanitize_xss($_POST['ID_variables']);

		#------------Parametros
		$parameters = array(
			'smlv' => sanitize_xss($_POST['smlv']),
			'subsidioVis_0_2' => sanitize_xss($_POST['subsidioVis_0_2']),
			'subsidioVis_2_4' => sanitize_xss($_POST['subsidioVis_2_4']),
			'factorMillonUvr30' => sanitize_xss($_POST['factorMillonUvr30']),
			'subsidioConcurrente' => sanitize_xss($_POST['subsidioConcurrente'])
		);
		$table ='variables_cotizador';
		$primarykey = 'ID_variables';
		$primaryvalue = $ID_variables;
		$query = PreparedStatementsM::UpdateM($parameters, $table, $primarykey, $primaryvalue);

		#------------Redirige
		$redirige = '../panel/variables/ok';
		header('Location:'.$redirige);
		die();

	}




	#-----------------------------------------------------------------------------
	# NOTAS LEGALES
	#-----------------------------------------------------------------------------

	#------------Carga Formulario
	public static function NotasLegalesC(){

		#------------Seguridad: ¿Está logueado?
		$IsNotLoguedC = AdminC::IsNotLoguedC('SUPERADMIN');
		
		#---Variables Cotizador
		$notasLegales = RegistrosBdM::GetNotasLegalesM();

		#---Carga vista
		include('views/panel/notas-legales.php');
		die();

	}

	#------------Carga Form Nota Legal (Detalle)
	public static function NotaLegalC(){

		#------------Seguridad: ¿Está logueado?
		$IsNotLoguedC = AdminC::IsNotLoguedC('SUPERADMIN');

		#------------Variables por defecto (Llenar formulario NEW)
		$ID_notaLegal = '0';
		$notaLegalTitulo = '';
		$notaLegal = '';

		#------------Defino cual es la tarea del formulario
		$task = $_GET['task'];
		if($task == 'new'){
			$txt_titulo = 'Nueva Nota Legal';
			$txt_btn_form = 'GUARDAR';
			$accionSubmit = 'new/insert';	
		}
		if($task == 'edit'){
			$txt_titulo = 'Editar Nota Legal';
			$txt_btn_form = 'GUARDAR';
			$accionSubmit = 'edit/update';	
		}

		#------------Cargo info de BD si es EDIT o REMOVE
		if(
			$task == 'edit'
		){
			$ID_notaLegal = $_GET['ID_notaLegal'];
			$registro = RegistrosBdM::GetNotaLegalM($ID_notaLegal);			
			$notaLegalTitulo = $registro['notaLegalTitulo'];
			$notaLegal = $registro['notaLegal'];
		}

		#------------Carga View
		include('views/panel/nota-legal.php');
		die();

	}

	#------------Nota Legal CREATE / UPDATE / DELETE
	public static function NotaLegalCrudC(){

		#------------Seguridad: ¿Está logueado?
		$IsNotLoguedC = AdminC::IsNotLoguedC('SUPERADMIN');

		#------------Defino ID y accion
		$action = sanitize_xss($_GET['action']); //insert, update, delete

		#------------Defino ID
		if(isset($_GET['ID_notaLegal'])){ $ID_notaLegal = sanitize_xss($_GET['ID_notaLegal']); }
		if(isset($_POST['ID_notaLegal'])){ $ID_notaLegal = sanitize_xss($_POST['ID_notaLegal']); }

		#------------Defino primary key para Queries
		$primarykey = 'ID_notaLegal';
		$primaryvalue = $ID_notaLegal;
		$table = 'nota_legal';

		#------------CREATE o UPDATE
		if($action == 'insert' || $action == 'update'){

			#------------Validacion Campos Obligatorios
			if( $_POST['notaLegalTitulo'] == '' || $_POST['notaLegal'] == '' ){
				$msg_error = 'Debe llenar todo los campos requeridos, resaltados con línea roja.<br><br>Por favor inténtelo de nuevo.';
				include('views/panel/error.php');
				die();
			}

			#------------Array Valores Formulario
			$parameters = array(
				'notaLegalTitulo' => sanitize_xss($_POST['notaLegalTitulo']),
				'notaLegal' => $_POST['notaLegal'],
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
			$query = PreparedStatementsM::DeleteM($table, $primarykey, $primaryvalue);
		}

		#------------Redirige
		$redirige = URL_BASE.'panel/notas-legales/ok/'.$action;
		header('Location:'.$redirige);
		die();

	}



	#-----------------------------------------------------------------------------
	# USUARIOS
	#-----------------------------------------------------------------------------
	
	#------------Usuarios (Lista)
	public static function UsuariosC(){

		#------------Seguridad: ¿Está logueado?
		$IsNotLoguedC = AdminC::IsNotLoguedC('SUPERADMIN');

		#------------Carga Registros
		$usuarios = RegistrosBdM::GetUsuariosM();
		
		#------------Carga View
		include('views/panel/usuarios.php');
		die();

	}

	#------------Carga Form Usuario (Detalle)
	public static function UsuarioC(){

		#------------Seguridad: ¿Está logueado?
		$IsNotLoguedC = AdminC::IsNotLoguedC('SUPERADMIN');

		#------------Variables por defecto (Llenar formulario NEW)
		$ID_usuario = '0';
		$usuarioActivo = '0';
		$usuarioRol = 'ASESOR';
		$usuarioDocumento = '';
		$usuarioNombre = '';
		$usuarioEmail = '';
		$usuarioCelular = '';

		#------------Defino cual es la tarea del formulario
		$task = $_GET['task'];
		if($task == 'new'){
			$txt_titulo = 'Nuevo Usuario';
			$txt_btn_form = 'GUARDAR';
			$accionSubmit = 'new/insert';	
		}
		if($task == 'edit'){
			$txt_titulo = 'Editar Usuario';
			$txt_btn_form = 'GUARDAR';
			$accionSubmit = 'edit/update';	
		}

		#------------Cargo info de BD si es EDIT o REMOVE
		if(
			$task == 'edit'
		){
			$ID_usuario = $_GET['ID_usuario'];
			$usuario = RegistrosBdM::GetUsuarioM($ID_usuario);			
			$usuarioActivo = $usuario['usuarioActivo'];
			$usuarioRol = $usuario['usuarioRol'];
			$usuarioDocumento = $usuario['usuarioDocumento'];
			$usuarioNombre = $usuario['usuarioNombre'];
			$usuarioEmail = $usuario['usuarioEmail'];
			$usuarioCelular = $usuario['usuarioCelular'];
		}

		#------------Carga View
		include('views/panel/usuario.php');
		die();

	}
	
	#------------Usuario CREATE / UPDATE / DELETE
	public static function UsuarioCrudC(){

		#------------Seguridad: ¿Está logueado?
		$IsNotLoguedC = AdminC::IsNotLoguedC('SUPERADMIN');

		#------------Defino ID y accion
		$action = sanitize_xss($_GET['action']); //insert, update, delete

		#------------Defino ID
		if(isset($_GET['ID_usuario'])){ $ID_usuario = sanitize_xss($_GET['ID_usuario']); }
		if(isset($_POST['ID_usuario'])){ $ID_usuario = sanitize_xss($_POST['ID_usuario']); }

		#------------Defino primary key para Queries
		$primarykey = 'ID_usuario';
		$primaryvalue = $ID_usuario;
		$table = 'usuarios';

		#------------CREATE o UPDATE
		if($action == 'insert' || $action == 'update'){

			#------------Validacion Campos Obligatorios
			if( $_POST['usuarioNombre'] == '' || $_POST['usuarioDocumento'] == '' || $_POST['usuarioEmail'] == '' ){
				$msg_error = 'Debes ingresar el nombre, documento y email del usuario.<br><br>Por favor inténtelo de nuevo.';
				include('views/panel/error.php');
				die();
			}
			if( $action == 'insert' && $_POST['usuarioContrasena'] == '' ){
				$msg_error = 'Debes ingresar la contraseña.<br><br>Por favor inténtelo de nuevo.';
				include('views/panel/error.php');
				die();
			}
			if( $_POST['usuarioContrasena'] != $_POST['usuarioContrasenaRepite'] ){
				$msg_error = 'La contraseña ingresada y repetida no coinciden.<br><br>Por favor inténtelo de nuevo.';
				include('views/panel/error.php');
				die();
			}

			#------------Array Valores Formulario
			$parameters = array(
				'usuarioActivo' => '0',
				'usuarioRol' => sanitize_xss($_POST['usuarioRol']),
				'usuarioDocumento' => sanitize_xss($_POST['usuarioDocumento']),
				'usuarioNombre' => sanitize_xss($_POST['usuarioNombre']),
				'usuarioEmail' => sanitize_xss($_POST['usuarioEmail']),
				'usuarioCelular' => sanitize_xss($_POST['usuarioCelular']),
				'updatedAt' => date('Y-m-d H:i:s')
			);
			if(isset($_POST['usuarioActivo'])){ $parameters['usuarioActivo'] = '1'; }

			#------------Encripto Contraseña
			if($_POST['usuarioContrasena'] != ''){
				$parameters['usuarioContrasena'] = crypt_pass($_POST['usuarioContrasena']);	
			}

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
			$query = PreparedStatementsM::DeleteM($table, $primarykey, $primaryvalue);
		}

		#------------Redirige
		$redirige = URL_BASE.'panel/usuarios/ok/'.$action;
		header('Location:'.$redirige);
		die();

	}




	#-----------------------------------------------------------------------------
	# CAMBIO CONTRASEÑA
	#-----------------------------------------------------------------------------
	
	#-----------Contrasena (Detalle)
	public static function ContrasenaC(){

		#------------Seguridad: ¿Está logueado?
		$IsNotLoguedC = AdminC::IsNotLoguedC();

		#------------Carga Usuario
		$txt_titulo = 'Cambiar Contraseña';
		$txt_btn_form = 'EDITAR';

		#------------Carga Usuario
		$ID_usuario = $_SESSION['usuario']['ID_usuario'];
		$usuario = RegistrosBdM::GetUsuarioM($ID_usuario);

		#------------Carga View
		include('views/panel/contrasena.php');
		die();

	}
	
	#------------Contrasena Update
	public static function ContrasenaEditC(){

		#------------Seguridad: ¿Está logueado?
		$IsNotLoguedC = AdminC::IsNotLoguedC();

		#------------Validacion
		if(
			$_POST['usuarioContrasena'] == ''
			|| $_POST['usuarioContrasena'] != $_POST['usuarioContrasenaRepite']
		){
			$msg_error = 'Las contraseñas ingresadas no coinciden.<br><br>Por favor revise e inténtelo de nuevo.';
			include('views/panel/error.php');
			die();	
		}

		#------------Defino accion, tabla y primary key
		$ID_usuario = $_SESSION['usuario']['ID_usuario'];

		#------------Actualiza
		$parameters = array(
			'usuarioContrasena' => crypt_pass($_POST['usuarioContrasena'])
		);
		$query = PreparedStatementsM::UpdateM($parameters, 'usuarios', 'ID_usuario', $ID_usuario);

		#------------Redirige
		$redirige = URL_BASE.'panel/contrasena/ok';
		header('Location:'.$redirige);
		die();

	}
	

	

	#-----------------------------------------------------------------------------
	# RESTAURAR CONTRASEÑA
	#-----------------------------------------------------------------------------
	
	#-----------Contrasena (Detalle)
	public static function RestaurarContrasenaC(){
		include('views/panel/restaurar-contrasena.php');
		die();
	}


	#-----------Restaurar Contrasena
	public static function RestaurarContrasenaEnviaC(){

		#------Variable Email
		$usuarioEmail = sanitize_xss($_POST['usuarioEmail']);

		#------Consulto BD usuario
		$usuario = RegistrosBdM::GetUsuarioPorEmailM($usuarioEmail);

		#------Usuario NO Existe
		if($usuario == NULL){
			$redirige = URL_BASE.'panel/restaurar-contrasena/error';
		}

		#------Usuario Sí Existe
		if($usuario != NULL){

			#----Nueva contraseña aleatoria
			$usuarioContrasena = sprintf(
				'%s%s%s',
				$usuario['ID_usuario'],
				time(),
				$usuario['ID_usuario']
			);

			#----Actualiza en BD
			$parametros = array(
				'usuarioContrasena' => crypt_pass($usuarioContrasena),
				'updatedAt' => date('Y-m-d H:i:s')
			);
			$query = PreparedStatementsM::UpdateM($parametros, 'usuarios', 'ID_usuario', $usuario['ID_usuario']);

			#----$AsuntoMail
			$AsuntoMail = sprintf(
				'%s, esta es su nueva contraseña de acceso al cotizador on-line LAS GALIAS',
				$usuario['usuarioNombre']
			);

			#----Cuerpo Correo
			$CuerpoMail = '';
			$CuerpoMail .= '<p style="font-family:Arial, Helvetica, sans-serif; line-height:1.3em;">';
			$CuerpoMail .= sprintf(
				'%s, Esta es su nueva contraseña de acceso al cotizador on-line LAS GALIAS
				<br><br>---------------------------------<br><br>
				<strong>%s</strong>',
				$usuario['usuarioNombre'],
				$usuarioContrasena
			);
			$CuerpoMail .= '</p>';
			//echo $CuerpoMail; die;

			#---Integracion con LCOMMKT
			$envioIcommkt = MailerC::EnviaConIcommkt($AsuntoMail, array($usuarioEmail), $CuerpoMail);

			#----URL confirma
			$redirige = URL_BASE.'panel/restaurar-contrasena/ok';

		}

		#------Redirige
		header('Location:'.$redirige);
		die();

	}
	



	#-----------------------------------------------------------------------------
	# CONTRASEÑAS DEFAULT (email)
	#-----------------------------------------------------------------------------


	#------------Genera contraseñas default de todos los Usuarios
	public static function Encripta_contrasenasC(){

        #---Ususarios
        $usuarios = RegistrosBdM::GetUsuariosM();

		#---Loop Contraseñas
		foreach($usuarios as $usuario){
			if($usuario['usuarioContrasena'] == ''){
				$parameters = array(
					'usuarioContrasena' => crypt_pass($usuario['usuarioEmail']),
					'updatedAt' => date('Y-m-d H:i:s')
				);
				$query = PreparedStatementsM::UpdateM($parameters, 'usuarios', 'ID_usuario', $usuario['ID_usuario']);
			}
		}

		#---Carga vista
		echo('Las contraseñas fueron encriptadas');

    }


	



}
?>
