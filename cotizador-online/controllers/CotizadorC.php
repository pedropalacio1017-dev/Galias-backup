<?php
class CotizadorC{


	#------------Defino cual es el CRM integrado (SUGAR; SINCO)
	public static function RetornaCrmIntegradoC(){
		$CrmIntegrado = 'SINCO';
		return $CrmIntegrado;
	}


	#------------Es un Asesor Logueado?
	public static function esAsesorLogueadoC(){

		#---Abro sesión
		if(session_id()=='') {session_start();}

		#---Set Default
		$infoUsuario = array(
			'ID_usuario' => '0',
			'usuarioEmail' => '',
			'esAsesor' => false
		);

		#---Es Asesor Logueado
		if(isset($_SESSION['usuario']['ID_usuario'])){
			$infoUsuario['ID_usuario'] =  $_SESSION['usuario']['ID_usuario'];
			$infoUsuario['usuarioEmail'] =  $_SESSION['usuario']['usuarioEmail'];
			$infoUsuario['esAsesor'] = true;
		}

		#---Salida
		return $infoUsuario;

	}

	
	#------------Carga Seleccion Ciudad / Proyecto
	public static function LoadC(){

		#---Es Asesor Logueado?
		$infoUsuario = CotizadorC::esAsesorLogueadoC();
		$ID_usuario = $infoUsuario['ID_usuario'];
		$usuarioEmail = $infoUsuario['usuarioEmail'];
		$esAsesor = $infoUsuario['esAsesor'];

		#---Set arrays
		$arrayIdsRegionales = array();
		$arrayRegionales = array();
		$arrayIdsProyectos = array();		
		$arrayProyectos = array();

		#---Proyectos/Subproyectos Activos ($ID_proyecto, $proyectoActivo, $subproyectoActivo)
		$subproyectosActivos = RegistrosBdM::GetSubproyectosM(NULL, '1', '1');
		//print_r($subproyectosActivos);die;

		#---Loop para llenar Arrays
		foreach($subproyectosActivos as $subproyecto){

			#---Variables Regional
			$ID_regional = $subproyecto['ID_regional'];
			$regional = $subproyecto['regional'];

			#---Variables Proyecto
			$ID_proyecto = $subproyecto['ID_proyecto'];
			$proyectoNombre = $subproyecto['proyectoNombre'];

			#---Push Regional
			if(!in_array($ID_regional, $arrayIdsRegionales)){
				#---Control
				$arrayIdsRegionales[] = $ID_regional;
				#---Push
				$arrayRegionales['regional_'.$ID_regional] = array(
					'ID_regional' => $ID_regional,
					'regional' => $regional
				);
			}

			#---Push Proyecto
			if(!in_array($ID_proyecto, $arrayIdsProyectos)){
				#---Control
				$arrayIdsProyectos[] = $ID_proyecto;
				#---Push
				$arrayProyectos[] = array(
					'ID_regional' => $ID_regional,
					'proyectoNombre' => $proyectoNombre,
					'linkCotizador' => sprintf(
						'cotizador/%s/%s',
						$ID_proyecto,
						seo_url($proyectoNombre)
					)
				);
			}

		}		
		//print_r($arrayRegionales);die;
		//print_r($arrayProyectos);die;

		#---Carga vista
		include('views/selecciona-proyecto.php');
		die();

	}


	#------------Carga Cotizador
	public static function CargaCotizadorC(){

		#---Es Asesor Logueado?
		$infoUsuario = CotizadorC::esAsesorLogueadoC();
		$ID_usuario = $infoUsuario['ID_usuario'];
		$usuarioEmail = $infoUsuario['usuarioEmail'];
		$esAsesor = $infoUsuario['esAsesor'];

		#---Variables URL
		$ID_proyecto = sanitize_xss($_GET['ID_proyecto']);

		#---Proyecto
		$proyecto = RegistrosBdM::GetProyectoM($ID_proyecto);
		//echo json_encode($proyecto);die;

		#---Valido proyecto
		if($proyecto == NULL || $proyecto['proyectoActivo'] != '1'){
			include('views/error404.php');
			die();
		}

		#---Regional
		$ID_regional = $proyecto['ID_regional'];

		#---Subproyectos Activos
		$subproyectosActivos = RegistrosBdM::GetSubproyectosM($ID_proyecto, '1', '1');
		//echo json_encode($subproyectosActivos);die;

		#---Si no hay subproyectos Activos
		if($subproyectosActivos == NULL){
			$msg_explica = 'Para el proyecto <strong>'.$proyecto['proyectoNombre'].'</strong> actualmente no hay etapas en venta.';
			include('views/error404.php');
			die();	
		}

		#---Array Limpio Subproyectos
		$subproyectos = array();
		foreach($subproyectosActivos as $subproyecto){
			$subproyectos[] = array(
				'ID_subproyecto' => $subproyecto['ID_subproyecto'],
				'subproyectoNombre' => $subproyecto['subproyectoNombre'],
				'subproyectoEntregas' => $subproyecto['subproyectoEntregas'],
				'subproyectoFechaMaxPago' => $subproyecto['subproyectoFechaMaxPago'],
				'subproyectoMesesPago' => meses_hoy_hasta_fecha($subproyecto['subproyectoFechaMaxPago']),
				'subproyectoPrecio' => $subproyecto['subproyectoPrecio'],
				'subproyectoValorSeparacion' => $subproyecto['subproyectoValorSeparacion'],
				'subproyectoCodigoCrm' => $subproyecto['subproyectoCodigoCrm'],
			);	
		}
		//echo json_encode($subproyectos);die;

		#---Ordeno proyectos por fecha de entrega (de más proximo a mas lejano)
		$ordenoSubproyectos = array_column($subproyectos, 'subproyectoFechaMaxPago');
		array_multisort($ordenoSubproyectos, SORT_ASC, $subproyectos);
		//echo json_encode($subproyectos);die;

		#---Variables Cotizador
		$varsCotizador = RegistrosBdM::GetVarsCotizadorM();
		//echo json_encode($varsCotizador);die;

		#---IP Cliente
		$clienteIp = '';
		if(!empty($_SERVER['REMOTE_ADDR'])){
			$clienteIp = $_SERVER['REMOTE_ADDR'];
		}
		if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
			$clienteIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		if(!empty($_SERVER['HTTP_CLIENT_IP'])){
			$clienteIp = $_SERVER['HTTP_CLIENT_IP'];
		}

		#---Set datos usuario
		$clienteNombres = '';
		$clienteApellidos = '';
		$clienteEmail = '';
		$clienteCelular = '';
		$clienteDocumento = '';

		#---Evaluo si hay sesion creada con datos de usuario
		if(isset($_SESSION['infoCliente'])){
			$clienteNombres = $_SESSION['infoCliente']['clienteNombres'];
			$clienteApellidos = $_SESSION['infoCliente']['clienteApellidos'];
			$clienteEmail = $_SESSION['infoCliente']['clienteEmail'];
			$clienteCelular = $_SESSION['infoCliente']['clienteCelular'];
			$clienteDocumento = $_SESSION['infoCliente']['clienteDocumento'];	
		}

		#---Carga vista
		include('views/cotizador.php');
		die();

	}


	#------------Crea Nueva Cotizacion en BD (datos cliente)
	public static function NuevaCotizacionC(){

		#---Debug
		//echo json_encode($_POST);die;

		#---Es Asesor Logueado?
		$infoUsuario = CotizadorC::esAsesorLogueadoC();
		$ID_usuario = $infoUsuario['ID_usuario'];
		$usuarioEmail = $infoUsuario['usuarioEmail'];
		$esAsesor = $infoUsuario['esAsesor'];

		#----Validacion
		if(
			!isset($_POST['ID_regional']) || $_POST['ID_regional'] == ''
			|| !isset($_POST['ID_proyecto']) || $_POST['ID_proyecto'] == ''
			|| !isset($_POST['clienteNombres']) || $_POST['clienteNombres'] == ''
			|| !isset($_POST['clienteApellidos']) || $_POST['clienteApellidos'] == ''
			|| !isset($_POST['clienteEmail']) || $_POST['clienteEmail'] == ''
			|| !isset($_POST['clienteCelular']) || $_POST['clienteCelular'] == ''
			|| !isset($_POST['clienteDocumento']) || $_POST['clienteDocumento'] == ''
		){
			die();
		}

		#----Proyecto
		$ID_proyecto = sanitize_xss($_POST['ID_proyecto']);
		$proyecto = RegistrosBdM::GetProyectoM($ID_proyecto);

		#----Variables
		$ID_regional = sanitize_xss($_POST['ID_regional']);		
		$fechaCotizacion = date('Y-m-d H:i:s');
		$infoCliente = array(
			'clienteNombres' => sanitize_xss($_POST['clienteNombres']),
			'clienteApellidos' => sanitize_xss($_POST['clienteApellidos']),
			'clienteEmail' => sanitize_xss($_POST['clienteEmail']),
			'clienteCelular' => sanitize_xss($_POST['clienteCelular']),
			'clienteDocumento' => sanitize_xss($_POST['clienteDocumento']),
			'clienteAceptaPolitica' => sanitize_xss($_POST['clienteAceptaPolitica']),
			'clienteIp' => sanitize_xss($_POST['clienteIp']),
		);
		$jsonCliente = encodeJsonConFlags($infoCliente);

		#----Crea sesion con datos de cliente por si hace mas de una cotizacion
		$_SESSION['infoCliente'] = $infoCliente;

		#----Parametros Cotizacion
		$parameters = array(
			'ID_regional' => $ID_regional,
			'ID_proyecto' => $ID_proyecto,
			'ID_subproyecto' => '0',
			'ID_usuario' => $ID_usuario,
			'fechaCotizacion' => $fechaCotizacion,
			'jsonCliente' => $jsonCliente,
			'jsonRequestSugar' => '',
			'responseSugar' => '',
			'jsonRequestSinco' => '',
			'responseSinco' => ''
		);

		#---CrmIntegrado
		$CrmIntegrado = CotizadorC::RetornaCrmIntegradoC();

		#---Integración con SUGAR
		if($CrmIntegrado == 'SUGAR'){

			#----Integracion 1 con CRM Sugar (Envia solo Lead) (Hecha por cliente o asesor)
			if($esAsesor == false || $esAsesor == true){

				#----Datos Lead
				$arrayLead = array(
					'first_name' => sanitize_xss($_POST['clienteNombres']),
					'last_name' => sanitize_xss($_POST['clienteApellidos']),
					'email1' => sanitize_xss($_POST['clienteEmail']),
					'phone_mobile' => sanitize_xss($_POST['clienteCelular']),
					'd_cedula_c' => sanitize_xss($_POST['clienteDocumento']),
					'd_habeas_c' => '1',
					'd_fecha_habeas_c' => date('Y-m-d'),
					'ged_productoleads_c' => sanitize_xss($_POST['proyectoCodigoCrm']),
					'd_momento_c' => '1',
					'd_megusta_c' => '0',
					'd_solicita_instrucciones_c' => '0'
				);

				#----Variables comunes Sugar
				$sugarConstantes = SugarC::SugarValoresConstantes($usuarioEmail);

				#----Uno Arrays
				$jSonRequestSugar = array_merge($arrayLead, $sugarConstantes);

				#----Envío a API Sugar
				$responseSugar = SugarC::IntegracionCrmSugarC($jSonRequestSugar);

				#----Añado Request a Array
				$parameters['jsonRequestSugar'] = encodeJsonConFlags($jSonRequestSugar);

				#----Añado Response a Array
				$parameters['responseSugar'] = $responseSugar;

			}

		}			
		
		#---Integración con SINCO
		if($CrmIntegrado == 'SINCO' && $proyecto['proyectoCodigoMacroSinco'] != ''){

			#----PostFields
			$jsonRequestSinco = array(    
				'idMacroProyecto' => $proyecto['proyectoCodigoMacroSinco'],
				//'idProyecto' => 0,			
				'nombres' => $infoCliente['clienteNombres'],
				'apellidos' => $infoCliente['clienteApellidos'],
				'correo' => $infoCliente['clienteEmail'],
				'celular' => $infoCliente['clienteCelular'],				
				'numeroIdentificacion' => $infoCliente['clienteDocumento'],
				'tipoIdentificacion' => 'CC',				
				'haAutorizadoManejoInformacion' => true,   
				'haAutorizadoEnvioCorreo' => true,
				'haAutorizadoEnvioSMS' => true,
				'HaAutorizadoEnvioWhatsApp' => true,
				'HaAutorizadoLlamada' => true,				
				'origenInformacion' => 1, //Web
				'idMedioPublicitario' => 20, //Sitio web GALIAS			
			);

			#----Debug (Proyecto PRUEBAS)
			//$jsonRequestSinco['idMacroProyecto'] = 150;

			#----Envio a Sinco
			$responseSinco =  json_encode(SincoC::SalaVentasExternoVisitasC($jsonRequestSinco));
			
			#----Añado Request a Array
			$parameters['jsonRequestSinco'] = encodeJsonConFlags($jsonRequestSinco);

			#----Añado Response a Array
			$parameters['responseSinco'] = $responseSinco;

		}		

		#----Inserta en BD
		$ID_cotizacion = PreparedStatementsM::InsertM($parameters, 'cotizaciones');

		#----Retorno Json Respuesta
		$respuesta = array(
			'estatus' => '200',
			'mensaje' => array(
				'ID_cotizacion' => $ID_cotizacion
			)
		);
		echo encodeJsonConFlags($respuesta);
		die();

	}


	#------------Actualiza Valores Cotizacion en BD
	public static function UpdateCotizacionC(){

		#----Debug
		//echo json_encode($_POST);die();

		#---Es Asesor Logueado?
		$infoUsuario = CotizadorC::esAsesorLogueadoC();
		$ID_usuario = $infoUsuario['ID_usuario'];
		$usuarioEmail = $infoUsuario['usuarioEmail'];
		$esAsesor = $infoUsuario['esAsesor'];

		#----Validacion
		if(!isset($_POST['ID_cotizacion']) || $_POST['ID_cotizacion'] == ''){ die(); }

		#----ID_cotizacion
		$ID_cotizacion = sanitize_xss($_POST['ID_cotizacion']);

		#----jSon Valores Cotizacion
		$jsonCotizacion = array(
			'subproyectoPrecio' => sanitize_xss($_POST['subproyectoPrecio']),
			'subproyectoValorSeparacion' => sanitize_xss($_POST['subproyectoValorSeparacion']),
			'valorIngresosFamiliares' => sanitize_xss($_POST['valorIngresosFamiliares']),
			'formaDePago' => sanitize_xss($_POST['formaDePago']),
			'tipoDeCredito' => sanitize_xss($_POST['tipoDeCredito']),
			'tipoDeContado' => sanitize_xss($_POST['tipoDeContado']),
			'aporteAhorros' => sanitize_xss($_POST['aporteAhorros']),
			'aporteCesantias' => sanitize_xss($_POST['aporteCesantias']),
			'aportePrimas' => sanitize_xss($_POST['aportePrimas']),
			'aporteTotal' => sanitize_xss($_POST['aporteTotal']),
			'valorSubsidio' => sanitize_xss($_POST['valorSubsidio']),
			'valorCuotaInicial' => sanitize_xss($_POST['valorCuotaInicial']),
			'mesesCuotaInicial' => sanitize_xss($_POST['mesesCuotaInicial']),
			'valorCuotaInicialMes' => sanitize_xss($_POST['valorCuotaInicialMes']),
			'valorCuotaFinal40' => sanitize_xss($_POST['valorCuotaFinal40']),
			'valorFinanciado' => sanitize_xss($_POST['valorFinanciado']),
			'valorCuotaFinanciadoMes' => sanitize_xss($_POST['valorCuotaFinanciadoMes']),
			'valorGastosEscrituracion' => sanitize_xss($_POST['valorGastosEscrituracion']),
			'observaciones' => '',
		);
		if(isset($_POST['observaciones'])){
			$jsonCotizacion['observaciones'] = sanitize_xss($_POST['observaciones']);	
		}
		$jsonCotizacion = encodeJsonConFlags($jsonCotizacion);

		#--------Plan de Pago Detallado (2023)
		$jsonPlanDePagoDetallado = '';
		if(isset($_POST['planPagoDetallado']) && $_POST['planPagoDetallado'] =='SI'){

			#----Set Array
			$jsonPlanDePagoDetallado = array(
				'conceptos' => array()
			);
			
			#----Totales Recalculados
			if(isset($_POST['ppd_separacionRecalculado'])){$jsonPlanDePagoDetallado['ppd_separacionRecalculado'] = sanitize_xss($_POST['ppd_separacionRecalculado']);}
			if(isset($_POST['ppd_aportesRecalculado'])){$jsonPlanDePagoDetallado['ppd_aportesRecalculado'] = sanitize_xss($_POST['ppd_aportesRecalculado']);}
			if(isset($_POST['ppd_subsidioRecalculado'])){$jsonPlanDePagoDetallado['ppd_subsidioRecalculado'] = sanitize_xss($_POST['ppd_subsidioRecalculado']);}
			if(isset($_POST['ppd_cuotaInicialRecalculado'])){$jsonPlanDePagoDetallado['ppd_cuotaInicialRecalculado'] = sanitize_xss($_POST['ppd_cuotaInicialRecalculado']);}
			if(isset($_POST['ppd_financiadoRecalculado'])){$jsonPlanDePagoDetallado['ppd_financiadoRecalculado'] = sanitize_xss($_POST['ppd_financiadoRecalculado']);}

			#----Recorro Array de Conceptos
			foreach($_POST['ppd_conceptoNombre'] as $key => $value){
				$concepto = array(
					'ppd_conceptoNombre' => $value,
					'ppd_conceptoFecha' => sanitize_xss($_POST['ppd_conceptoFecha'][$key]),
					'ppd_conceptoValorGalias' => sanitize_xss(limpioCantidad(round($_POST['ppd_conceptoValorGalias'][$key]))),
					'ppd_escrituraValorGalias' => sanitize_xss(limpioCantidad(round($_POST['ppd_escrituraValorGalias'][$key]))),
					'ppd_conceptoValor' => sanitize_xss(limpioCantidad($_POST['ppd_conceptoValor'][$key])),
					'ppd_escrituraValor' => sanitize_xss(limpioCantidad($_POST['ppd_escrituraValor'][$key])),					
					'ppd_subtotal' => sanitize_xss(limpioCantidad($_POST['ppd_subtotal'][$key])),
				);
				$jsonPlanDePagoDetallado['conceptos'][] = $concepto;
			}

			#----Codifico como Json
			$jsonPlanDePagoDetallado = encodeJsonConFlags($jsonPlanDePagoDetallado);

		}
		//echo $jsonPlanDePagoDetallado; die;

		#----Parametros Cotizacion en BD
		$parameters = array(
			'ID_subproyecto' => sanitize_xss($_POST['ID_subproyecto']),
			'jsonCotizacion' => $jsonCotizacion,
			'jsonPlanDePagoDetallado' => $jsonPlanDePagoDetallado
		);

		#---CrmIntegrado
		$CrmIntegrado = CotizadorC::RetornaCrmIntegradoC();

		#---integracion con SUGAR
		if($CrmIntegrado == 'SUGAR'){		
		
			#----Set integrar con Sugar
			$integrarConSugarLaCotizacion = true;

			#----Evita integracion con Sugar si es un asesor
			//if($esAsesor == true){$integrarConSugarLaCotizacion = false;}

			#----Integracion 2 con CRM Sugar (Envio datos Lead + Cotizacion completa 'ME GUSTA')
			if($integrarConSugarLaCotizacion == true){

				#----Variables cotizacion para Sugar
				$d_como_paga_c = '1';
				$d_tipo_credito_c = sanitize_xss($_POST['tipoDeCredito']);
				if($_POST['formaDePago'] == 'Contado'){
					$d_como_paga_c = '0';
					$d_tipo_credito_c = sanitize_xss($_POST['tipoDeContado']);	
				}

				#----Array Completo
				$arrayLead = array(
					'first_name' => sanitize_xss($_POST['clienteNombres']),
					'last_name' => sanitize_xss($_POST['clienteApellidos']),
					'email1' => sanitize_xss($_POST['clienteEmail']),
					'phone_mobile' => sanitize_xss($_POST['clienteCelular']),
					'd_cedula_c' => sanitize_xss($_POST['clienteDocumento']),
					'd_habeas_c' => '1',
					'd_fecha_habeas_c' => date('Y-m-d'),
					'ged_productoleads_c' => sanitize_xss($_POST['proyectoCodigoCrm']),
					'd_momento_c' => '2',
					'd_megusta_c' => '1',
					'd_solicita_instrucciones_c' => '0',
					'd_etapa_c' => sanitize_xss($_POST['subproyectoNombre']),
					'd_rango_ingreso_c' => sanitize_xss($_POST['valorIngresosFamiliares']),
					'd_como_paga_c' => $d_como_paga_c,
					'd_tipo_credito_c' => $d_tipo_credito_c,
					'd_ahorro_c' => sanitize_xss($_POST['aporteAhorros']),
					'd_cesantias_c' => sanitize_xss($_POST['aporteCesantias']),
					'd_primas_c' => sanitize_xss($_POST['aportePrimas'])
				);

				#----Unifico Array con datos generales Sugar
				$sugarConstantes = SugarC::SugarValoresConstantes($usuarioEmail);
				$jSonRequestSugar = array_merge($arrayLead, $sugarConstantes);

				#----Envio a Sugar
				$responseSugar = SugarC::IntegracionCrmSugarC($jSonRequestSugar);

				#----Añado Request Sugar
				$parameters['jsonRequestSugar'] = encodeJsonConFlags($jSonRequestSugar);

				#----Añado Response Sugar
				$parameters['responseSugar'] = $responseSugar;

			}

		}

		#----Update Cotizacion
		$query = PreparedStatementsM::UpdateM($parameters, 'cotizaciones', 'ID_cotizacion', $ID_cotizacion);

		#----Retorno Json Respuesta
		$respuesta = array(
			'estatus' => '200',
			'mensaje' => array(
				'ID_cotizacion' => $ID_cotizacion,
				'asunto' => 'Actualizada'
			)
		);
		echo encodeJsonConFlags($respuesta);
		die();


	}

	
	#------------Acciones de botones Finales ¿Que hago con tu cotizacion?
	public static function AccionesCotizacionC(){

		#---Es Asesor Logueado?
		$infoUsuario = CotizadorC::esAsesorLogueadoC();
		$ID_usuario = $infoUsuario['ID_usuario'];
		$usuarioEmail = $infoUsuario['usuarioEmail'];
		$esAsesor = $infoUsuario['esAsesor'];

		#----Validacion
		if(!isset($_GET['accion']) || $_GET['accion'] == '' ){die();}

		#----Accion
		$accion = $_GET['accion'];
		//print_r($_POST);die();

		#----Proyecto
		$ID_proyecto = sanitize_xss($_POST['ID_proyecto']);
		$proyecto = RegistrosBdM::GetProyectoM($ID_proyecto);

		#----Subproyecto
		$ID_subproyecto = sanitize_xss($_POST['ID_subproyecto']);
		$subproyecto = RegistrosBdM::GetSubproyectoM($ID_subproyecto);

		#----ID_cotizacion
		$ID_cotizacion = sanitize_xss($_POST['ID_cotizacion']);
		$cotizacion = RegistrosBdM::GetCotizacionM($ID_cotizacion);
		//echo json_encode($cotizacion);die;

		#----json Cliente, Cotizacion, Plan de Pagos
		$jsonCliente = json_decode($cotizacion['jsonCliente'],true);
		$jsonCotizacion = json_decode($cotizacion['jsonCotizacion'],true);
		$jsonPlanDePagoDetallado = json_decode($cotizacion['jsonPlanDePagoDetallado'],true);

		#----Enviar EMAILs 
		if($accion == 'email' || $accion == 'copias-email'){

			#---Construyo body Email
			include('views/includes/plantilla-correo-cotizacion.php');
			//echo($CuerpoMail);die();

			#---Headers Email
			if($accion == 'email'){
				$AsuntoMail = sprintf(
					'%s, esta tu cotización de %s, %s',
					$jsonCliente['clienteNombres'],
					$proyecto['proyectoNombre'],
					$subproyecto['subproyectoNombre']
				);
				$EnviarA = array($jsonCliente['clienteEmail']);
			}
			if($accion == 'copias-email'){
				$emailsCopias = array();
				if($_POST['copiaEmail1'] != ''){
					$emailsCopias[] = $_POST['copiaEmail1'];
				}
				if($_POST['copiaEmail2'] != ''){
					$emailsCopias[] = $_POST['copiaEmail2'];
				}
				$AsuntoMail = sprintf(
					'%s %s, te compartió esta cotización de %s, %s',
					$jsonCliente['clienteNombres'],
					$jsonCliente['clienteApellidos'],
					$proyecto['proyectoNombre'],
					$subproyecto['subproyectoNombre']
				);
				$EnviarA = $emailsCopias;
			}
			$ResponderA = $jsonCliente['clienteEmail'];
			
			#---Envio Email a traves de PhpMyAdmin
			//$Mailer = MailerC::EnviaMailC($AsuntoMail, $ResponderA, $EnviarA, $CuerpoMail);

			#---Integracion con LCOMMKT
			$envioIcommkt = MailerC::EnviaConIcommkt($AsuntoMail, $EnviarA, $CuerpoMail);

			#---Texto mensaje confirmacion
			if($accion == 'email'){
				$asunto = 'La cotización fue enviada a tu correo.';
			}
			if($accion == 'copias-email'){
				$asunto = 'Una copia de la cotización fue enviada a los correos que indicaste.';
			}
			
		}

		#----Enviar Solicitar Separacion
		if($accion == 'solicitar-separacion'){

			#---CrmIntegrado
			$CrmIntegrado = CotizadorC::RetornaCrmIntegradoC();

			#---Integración 3 con SUGAR
			if($CrmIntegrado == 'SUGAR'){

				#---Integracion 3 con CRM Sugar (Envio datos cotizacion completa + 'Solicita Separacion') - Se hace cuando NO ES un Asesor Logueado
				if($esAsesor == false || $esAsesor == true){

					#-----Variables SUGAR
					$d_como_paga_c = '1';
					$d_tipo_credito_c = sanitize_xss($_POST['tipoDeCredito']);
					if($_POST['formaDePago'] == 'Contado'){
						$d_como_paga_c = '0';
						$d_tipo_credito_c = sanitize_xss($_POST['tipoDeContado']);	
					}

					#-----Array actualizado
					$arrayLead = array(
						'first_name' => sanitize_xss($_POST['clienteNombres']),
						'last_name' => sanitize_xss($_POST['clienteApellidos']),
						'email1' => sanitize_xss($_POST['clienteEmail']),
						'phone_mobile' => sanitize_xss($_POST['clienteCelular']),
						'd_cedula_c' => sanitize_xss($_POST['clienteDocumento']),
						'd_habeas_c' => '1',
						'd_fecha_habeas_c' => date('Y-m-d'),
						'ged_productoleads_c' => sanitize_xss($_POST['proyectoCodigoCrm']),
						'd_momento_c' => '3',
						'd_megusta_c' => '1',
						'd_solicita_instrucciones_c' => '1',
						'd_etapa_c' => sanitize_xss($_POST['subproyectoNombre']),
						'd_rango_ingreso_c' => sanitize_xss($_POST['valorIngresosFamiliares']),
						'd_como_paga_c' => $d_como_paga_c,
						'd_tipo_credito_c' => $d_tipo_credito_c,
						'd_ahorro_c' => sanitize_xss($_POST['aporteAhorros']),
						'd_cesantias_c' => sanitize_xss($_POST['aporteCesantias']),
						'd_primas_c' => sanitize_xss($_POST['aportePrimas'])
					);

					#-----Array completo con Valores constantes de Sugar
					$sugarConstantes = SugarC::SugarValoresConstantes($usuarioEmail);
					$jSonRequestSugar = array_merge($arrayLead, $sugarConstantes);
					//echo json_encode($jSonRequestSugar);die;

					#-----Envío a Sugar
					$responseSugar = SugarC::IntegracionCrmSugarC($jSonRequestSugar);

					#----Update Cotizacion en BD
					$parameters = array(
						'jsonRequestSugar' => encodeJsonConFlags($jSonRequestSugar),
						'responseSugar' => $responseSugar
					);
					$query = PreparedStatementsM::UpdateM($parameters, 'cotizaciones', 'ID_cotizacion', $ID_cotizacion);

				}

			}

			#----Envia a Sala de Ventas una alerta por correo
			if($proyecto['proyectoEmail'] != ''){

				#----Array Receptores Sala de Ventas
				$proyectoEmail = str_replace(' ', '', $proyecto['proyectoEmail']);
				$proyectoEmail = str_replace(',', ';', $proyectoEmail);
				$proyectoEmail = explode(';', $proyectoEmail);

				#----Headers Correo
				$AsuntoMail = sprintf(
					'Prospecto muy interesado en proyecto %s (%s), solicitó instrucciones de separación en cotizador on-line',
					$proyecto['proyectoNombre'],
					$subproyecto['subproyectoNombre']
				);
				$EnviarA = $proyectoEmail;
				$ResponderA = $jsonCliente['clienteEmail'];

				#----Cuerpo Correo
				$CuerpoMail = '';
				$CuerpoMail .= '<p style="font-family:Arial, Helvetica, sans-serif; line-height:1.3em;">';
				$CuerpoMail .= sprintf('
					Prospecto muy interesado, solicitó instrucciones de separación
					<br><br>---------------------------------<br><br>
					<b>INTERÉS EN</b>
					<br>Proyecto: %s
					<br>Subproyecto: %s
					<br><br>---------------------------------<br><br>
					<b>DATOS PROSPECTO</b>
					<br>Nombre: %s
					<br>Apellido: %s					
					<br>Email: %s
					<br>Celular: %s
					<br>Documento: %s
				',
					$proyecto['proyectoNombre'],
					$subproyecto['subproyectoNombre'],
					$jsonCliente['clienteNombres'],
					$jsonCliente['clienteApellidos'],
					$jsonCliente['clienteEmail'],
					$jsonCliente['clienteCelular'],
					$jsonCliente['clienteDocumento']
				);
				$CuerpoMail .= '</p>';
				//echo $CuerpoMail; die;

				#----Envio Correo a través de PhpMyAdmin
				//$Mailer = MailerC::EnviaMailC($AsuntoMail, $ResponderA, $EnviarA, $CuerpoMail);

				#---Integracion con LCOMMKT
                $envioIcommkt = MailerC::EnviaConIcommkt($AsuntoMail, $EnviarA, $CuerpoMail);

			}

			#---Texto mensaje confirmacion
			$asunto = 'Tu solicitud de separación fue enviada. Nuestros asesores de contactarán pronto.';

		}

		#----Retorno Json Respuesta
		if($accion == 'email' || $accion == 'copias-email' || $accion == 'solicitar-separacion'){
			$respuesta = array(
				'estatus' => '200',
				'mensaje' => array(
					'ID_cotizacion' => $ID_cotizacion,
					'asunto' => $asunto
				)
			);
			echo encodeJsonConFlags($respuesta);
			die();
		}

		#----Generar PDF
		if($accion == 'pdf'){

			#---Construyo Nombre PDF
			$nombreArchivoPdf = sprintf(
                'cotizacion_%s_%s',
                seo_url($proyecto['proyectoNombre']),
                seo_url($subproyecto['subproyectoNombre'])
            );

            #---Arma Hoja HTML
            $htmlImprime = '';
            include('views/includes/plantilla-impresion-cotizacion.php');

            #---Libreria DomPDF            
            require 'libs/dompdf/autoload.inc.php';
            $dompdf = new Dompdf\Dompdf(
                array(
                    'enable_remote' => true,
                )
            );
            $dompdf->loadHtml($htmlImprime);
            $dompdf->setPaper('letter', 'portrait');
            $dompdf->render();
            $dompdf->stream($nombreArchivoPdf, array('Attachment' => 0));

            die();

		}


	}



}
?>