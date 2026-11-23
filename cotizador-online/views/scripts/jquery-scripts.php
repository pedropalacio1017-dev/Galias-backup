<script>
$(document).ready(function() {

	/*------------------------------------------------------------------------
	GENERALES
	------------------------------------------------------------------------*/

	var width = $(window).width();

	function deslizar_a(destino, margen_top_menu, velocidad){
		$('html, body').animate({ scrollTop: $('#' + destino).offset().top - margen_top_menu + 'px' }, velocidad);
	}


	/*------------------------------------------------------------------------
	PASO 1: Seleccion proyecto
	------------------------------------------------------------------------*/

	/*--------------Al regresar, desmarca regional*/
	if($('#select_regional').length > 0){
		$('#select_regional option[value="0"]').prop('selected', true);	
	}

	/*--------------Limpia Select Proyectos*/
	function limpia_select_proyectos(){
		$('#select_proyecto option[value!="0"]').remove();
		$('#select_proyecto option[value="0"]').prop('selected', true);
		$('#select_proyecto').prop('disabled', 'disabled');	
	}

	/*--------------Seleccion Ciudad*/
	$('#select_regional').on('change', function() {
		var ID_regional = $(this).find('option:selected').val();
		//Ninguna regional seleccionada
		if(ID_regional == '0'){			
			limpia_select_proyectos();
		}
		else{
			//Limpio opciones
			limpia_select_proyectos();
			//Recorro Json todos los proyectos
			$.each(proyectos, function(index, value) {
				if(ID_regional == proyectos[index].ID_regional){
					var proyectoNombre = proyectos[index].proyectoNombre;
					var linkCotizador = proyectos[index].linkCotizador;
					//Cargo las opciones de esa regional
					$('#select_proyecto').append('<option value="'+linkCotizador+'">'+proyectoNombre+'</option>');
				}
			});
			//Desbloqueo Select proyectos
			$('#select_proyecto').prop('disabled', false);
		}
	});
	
	/*--------------Va a Paso 2*/
	$('#btn_selecciona_proyecto').on('click', function(e) {
		e.preventDefault();
		var linkCotizador = $('#select_proyecto').val();
		//Valida
		if(linkCotizador == '0'){
			swal('Error', 'Debes seleccionar algún proyecto', 'error');
			return false;
		}
		$(this).text('Cargando...');
		window.location.href = linkCotizador;
	});


	/*------------------------------------------------------------------------
	PASO 2: Ingreso de Datos personales
	------------------------------------------------------------------------*/

	/*--------------Al cargar Cotizador en movil, desliza a titulo*/
	if(width < 1180 && $('input[name="ID_cotizacion"]').length > 0){
		deslizar_a('formulario_ancla', 30, 'slow');	
	}

	/*--------------Privacidad check formulario*/
	$('#clienteAceptaPolitica').on('click', function(e){
		if($(this).is(':checked')){
			$('#continuaAPaso3').removeClass('disabled');
		}
		else {
			$('#continuaAPaso3').addClass('disabled');
		}
	});

	/*--------------Clic Btn Continuar a PASO 3*/
	$('#continuaAPaso3').on('click', function(e) {

		e.preventDefault();

		//Variables Regional y Proyecto
		var ID_regional = $('input[name="ID_regional"]').val();
		var ID_proyecto = $('input[name="ID_proyecto"]').val();

		//Variables Cliente
		var clienteNombres = $('#clienteNombres').val();
		var clienteApellidos = $('#clienteApellidos').val();
		var clienteEmail = $('#clienteEmail').val();
		var clienteEmailRepite = $('#clienteEmailRepite').val();
		var clienteCelular = $('#clienteCelular').val();
		var clienteDocumento = $('#clienteDocumento').val();
		var clienteAceptaPolitica = 'SI';
		var clienteIp = $('input[name="clienteIp"]').val();

		//Valida
		if (clienteNombres.length < 3) {
			swal('Error', 'Debes ingresar tu nombre.', 'error');
			return false;
		}
		if (clienteApellidos.length < 3) {
			swal('Error', 'Debes ingresar tu apellido.', 'error');
			return false;
		}
		if (!/(.+)@(.+){2,}\.(.+){2,}/.test(clienteEmail)) {
			swal('Error', 'Debes usar un email válido.<br>A este te enviaremos tu cotización.', 'error');
			return false;
		}
		if (clienteEmail != clienteEmailRepite) {
			swal('Error', 'Debes llenar el campo de verificación de email, igual a como llenaste el de tu email.', 'error');
			return false;
		}
		if (clienteCelular.length != 10) {
			swal('Error', 'Debes ingresar tu número de celular. Sin espacios ni guiones.', 'error');
			return false;
		}
		if (clienteDocumento.length < 3) {
			swal('Error', 'Debes ingresar tu documento de identidad.', 'error');
			return false;
		}

		//Llena Inputs ocultos
		$('input[name="clienteNombres"]').val(clienteNombres);
		$('input[name="clienteApellidos"]').val(clienteApellidos);
		$('input[name="clienteEmail"]').val(clienteEmail);
		$('input[name="clienteCelular"]').val(clienteCelular);
		$('input[name="clienteDocumento"]').val(clienteDocumento);
		$('input[name="clienteAceptaPolitica"]').val(clienteAceptaPolitica);

		//Escribe Nombre de Cliente en títulos
		$('.txtClienteNombre').text(clienteNombres);

		//Inhabilita botón para evitar envíos múltiples
		$(this).addClass('disabled').html('Procesando');

		//Inserta Nueva Cotizacion + Integra CRM
		$.ajax({
			type: 'POST',
			url: 'nueva-cotizacion',
			data: $('#formCotizacion').serialize(),
			success:function(respuesta){
				json = jQuery.parseJSON(respuesta);
				//Obtengo ID_cotizacion y lleno campo oculto
				ID_cotizacion = json.mensaje.ID_cotizacion;
				$('input[name="ID_cotizacion"]').val(ID_cotizacion);
				//Va a paso 3
				$('#paso_2').hide();
				$('#paso_3').show();
				deslizar_a('paso_3_ancla', 0, 'fast');
			}
		});

	});


	/*------------------------------------------------------------------------
	PASO 3: Selecciona Subproyecto
	------------------------------------------------------------------------*/
	$('.seleccionaSubproyecto').on('click', function(e) {

		e.preventDefault();

		//Variables Subproyecto
		let subproyecto = $(this);
		let ID_subproyecto = $(subproyecto).data('subproyecto-id');
		let subproyectoNombre = $(subproyecto).data('subproyecto-nombre');
		let subproyectoEntregas = $(subproyecto).data('subproyecto-entregas');
		let subproyectoMesesPago = $(subproyecto).data('subproyecto-meses-pago');
		let subproyectoPrecio = $(subproyecto).data('subproyecto-precio');
		let subproyectoValorSeparacion = $(subproyecto).data('subproyecto-valor-separacion');
		let subproyectoCodigoCrm = $(subproyecto).data('subproyecto-codigo-crm');
        
		//Inserto en campos ocultos
		$('input[name="ID_subproyecto"]').val(ID_subproyecto);
		$('input[name="subproyectoNombre"]').val(subproyectoNombre);
		$('input[name="subproyectoPrecio"]').val(subproyectoPrecio);
		$('input[name="subproyectoValorSeparacion"]').val(subproyectoValorSeparacion);
		$('input[name="subproyectoEntregas"]').val(subproyectoEntregas);
		$('input[name="subproyectoMesesPago"]').val(subproyectoMesesPago);
		$('input[name="subproyectoCodigoCrm"]').val(subproyectoCodigoCrm);

		//Escribo textos visibles subproyecto
		$('.txtSubproyectoNombre').text(subproyectoNombre);
		$('.txtSubproyectoPrecio').text( '$'+ $.number(subproyectoPrecio) );
		$('.txtSubproyectoEntregas').text(subproyectoEntregas);
		$('.txtSubproyectoMesesPago').text(subproyectoMesesPago);
		$('#txtSubproyectoValorSeparacion').text( '$'+ $.number(subproyectoValorSeparacion) );

		//Set Campos Ocultos Cotizador
		$('input[name="valorIngresosFamiliares"]').val(0);
		$('input[name="formaDePago"]').val('Credito');
		$('input[name="tipoDeCredito"]').val('70_30');
		$('input[name="tipoDeContado"]').val('');
		$('input[name="aporteAhorros"]').val(0);
		$('input[name="aporteCesantias"]').val(0);
		$('input[name="aportePrimas"]').val(0);
		$('input[name="aporteTotal"]').val(0);
		$('input[name="valorSubsidio"]').val(0);
		$('input[name="valorCuotaInicial"]').val(0);
		$('input[name="mesesCuotaInicial"]').val(0);
		$('input[name="valorCuotaInicialMes"]').val(0);
		$('input[name="valorCuotaFinal40"]').val(0);
		$('input[name="valorFinanciado"]').val(0);
		$('input[name="valorCuotaFinanciadoMes"]').val(0);
		$('input[name="valorGastosFinanciacion"]').val('');

		//Set Campos visibles Cotizador
		$('#formaDePago option, #tipoDeCredito option, #tipoDeContado option').attr('selected', false);
		$('#formaDePago option[value="Credito"], #tipoDeCredito option[value="70_30"]').attr('selected', true);
		$('#valorIngresosFamiliares, #aporteAhorros, #aporteCesantias, #aportePrimas').val(0);

		//Visibilidad Selects Cotizador
		$('#opcionesCredito').show();
		$('#opcionesContado').hide();

		//Ejecuto funciones carga cotizador
		leeValoresBase();
		leeFormaDePago();
		calculaSubsidio();
		calculaAportes();
		calculaFinanciacion();
		calculaCuotaInicial();
		textosResultados();
        deshabilitoPlanPagoDetallado();

		//Va a paso 4
		$('#paso_3').hide();
		$('#paso_4').show();
		deslizar_a('paso_4_ancla', 0, 'fast');

	});


	/*------------------------------------------------------------------------
	FUNCIONES COTIZADOR
	------------------------------------------------------------------------*/

	//Lee valores base
	function leeValoresBase(){			
		valorTotal = parseInt($('input[name="subproyectoPrecio"]').val());
		valorSeparacion = parseInt($('input[name="subproyectoValorSeparacion"]').val());
	}

	//Lee Forma de Pago
	function leeFormaDePago(){			
		formaDePago = $('input[name="formaDePago"]').val();
		tipoDeCredito = $('input[name="tipoDeCredito"]').val();
		tipoDeContado = $('input[name="tipoDeContado"]').val();	
	}

	//Funcion Calcula Subsidio
	function calculaSubsidio(){			
		valorIngresosFamiliares = parseInt($('input[name="valorIngresosFamiliares"]').val());
		if(valorIngresosFamiliares != 0){
			ingresoComoSmlv = valorIngresosFamiliares/smlv;
			if(ingresoComoSmlv > 0 && ingresoComoSmlv <= 2){
				valorSubsidio = subsidioVis_0_2;
			}
			if(ingresoComoSmlv > 2 && ingresoComoSmlv <= 4){
				valorSubsidio = subsidioVis_2_4;
			}
			if(ingresoComoSmlv > 4 ){
				valorSubsidio = 0;
			}
		}
		else{
			valorSubsidio = 0;	
		}
		$('input[name="valorSubsidio"]').val(valorSubsidio);
	}

	//Funcion Calcula Aportes
	function calculaAportes(){			
		aporteAhorros = parseInt($('input[name="aporteAhorros"]').val());
		aporteCesantias = parseInt($('input[name="aporteCesantias"]').val());
		aportePrimas = parseInt($('input[name="aportePrimas"]').val());
		aporteTotal = aporteAhorros + aporteCesantias + aportePrimas;
		$('input[name="aporteTotal"]').val(aporteTotal);			
	}

	//Funcion Calcula Financiacion
	function calculaFinanciacion(){
		if(formaDePago == 'Credito'){
			//Valor por defecto 60-40
			if(tipoDeCredito == '60_40'){
				valorFinanciado = parseInt(valorTotal * 0.6);
			}
			//Valor por defecto 70-30
			if(tipoDeCredito == '70_30'){
				valorFinanciado = parseInt(valorTotal * 0.7);
			}
			//Valor por defecto 80-20
			if(tipoDeCredito == '80_20'){
				valorFinanciado = parseInt(valorTotal * 0.8);
			}
			//Si se definió el ingreso familiar, calculo el prestamo maximo que le harían
			if(valorIngresosFamiliares != 0){
				valorFinanciadoMax = parseInt(((valorIngresosFamiliares*0.4)/factorMillonUvr30)*1000000 );
				//Controlo que el valorFinanciado NO supere el valorFinanciadoMaximo que le harían
				if(valorFinanciado > valorFinanciadoMax) {
					valorFinanciado = valorFinanciadoMax;
				}
			}
			//Calculo Cuota Mensual Financiada
			valorCuotaFinanciadoMes = calculaValorCuotaFinanciadoMes(valorFinanciado, factorMillonUvr30);
			//Lleno inputs
			$('input[name="valorFinanciado"]').val(valorFinanciado);
			$('input[name="valorCuotaFinanciadoMes"]').val(valorCuotaFinanciadoMes);
		}
		//Contado
		else {
			valorFinanciado = 0;
			valorCuotaFinanciadoMes = 0;
			$('input[name="valorFinanciado"]').val(valorFinanciado);
			$('input[name="valorCuotaFinanciadoMes"]').val(valorCuotaFinanciadoMes);
		}	
	}

	function calculaValorCuotaFinanciadoMes(valorFinanciado, factorMillonUvr30){
		valorCuotaFinanciadoMes = parseInt((valorFinanciado/1000000)*factorMillonUvr30);
		return valorCuotaFinanciadoMes;	
	}

	//Funcion Calcula Cuota Inicial
	function calculaCuotaInicial(){
		valorCuotaInicial = parseInt(valorTotal - valorSeparacion - valorSubsidio - aporteTotal - valorFinanciado);
		mesesCuotaInicial = $('input[name="subproyectoMesesPago"]').val();
		//valorCuotaInicialMes = parseInt(valorCuotaInicial / mesesCuotaInicial);
        valorCuotaInicialMes = parseFloat(valorCuotaInicial / mesesCuotaInicial);
		valorCuotaFinal40 = 0;
		//--Contado 1 Cuota
		if(formaDePago == 'Contado' && tipoDeContado == 'Una_Cuota'){
			mesesCuotaInicial = '1';
			valorCuotaInicialMes = valorCuotaInicial;
		}
		//--CuotaFinal_40%
		if(formaDePago == 'Contado' && tipoDeContado == '40_al_final'){
			mesesCuotaInicial = mesesCuotaInicial - 1;
			//valorCuotaInicialMes = parseInt((valorCuotaInicial*0.6) / mesesCuotaInicial);
            valorCuotaInicialMes = parseFloat((valorCuotaInicial*0.6) / mesesCuotaInicial);
			valorCuotaFinal40 = valorCuotaInicial*0.4;
		}
		//---Evito Cuota Inicial Negativa
		if(valorCuotaInicial < 0){
			diferenciaAfavor = parseInt(0-valorCuotaInicial);
			//Actualiza Cuota Inicial
			valorCuotaInicial = 0;
			valorCuotaInicialMes = 0;
			$('#paso_4 input[name="valorCuotaInicial"]').val(valorCuotaInicial);
			$('#paso_4 input[name="valorCuotaInicialMes"]').val(valorCuotaInicialMes);				
			//Recalcula Credito
			valorFinanciado = valorFinanciado - diferenciaAfavor;
			valorCuotaFinanciadoMes = calculaValorCuotaFinanciadoMes(valorFinanciado, factorMillonUvr30);
			$('input[name="valorFinanciado"]').val(valorFinanciado);
			$('input[name="valorCuotaFinanciadoMes"]').val(valorCuotaFinanciadoMes);
		}
		//---Escribe en campos ocultos
		$('input[name="valorCuotaInicial"]').val(valorCuotaInicial);
		$('input[name="mesesCuotaInicial"]').val(mesesCuotaInicial);
		$('input[name="valorCuotaInicialMes"]').val(valorCuotaInicialMes);
		$('input[name="valorCuotaFinal40"]').val(valorCuotaFinal40);				
	}

	//Funcion Muestra textos en pantalla
	function textosResultados(){

		$('#txtAportesAhorrosCesantias').text( '$'+ $.number(aporteTotal) );
		$('#txtValorSubsidio').text( '$'+ $.number(valorSubsidio) );
		$('#txtValorCuotaInicial').text( '$'+ $.number(valorCuotaInicial) );
		$('#txtMesesCuotaInicial').text(mesesCuotaInicial);
		$('#txtValorCuotaInicialMes').text( '$'+ $.number(valorCuotaInicialMes) );
		$('#txtValorCuotaFinal_40').text( '$'+ $.number(valorCuotaFinal40) );
		$('#txtValorFinanciado').text( '$'+ $.number(valorFinanciado) );
		$('#txtValorCuotaFinanciadoMes').text( '$'+ $.number(valorCuotaFinanciadoMes) );

        //Muestro párrafos con datos de cuotas mensuales C.Inicial y Financiacón, por si los había ocultado en el PPdetallado
        $('#bloqueCuotaInicial p').removeClass('invisible');
        $('#bloqueFinanciacion p').removeClass('invisible');
		
        //Visibilidad bloque Aportes
		if(aporteTotal == 0){
			$('#bloqueAportes').hide();
		} else {
			$('#bloqueAportes').show();	
		}
		
        //Visibilidad bloque Subsidios
		if(valorSubsidio == 0){
			$('#bloqueSubsidio').hide();
		} else {
			$('#bloqueSubsidio').show();	
		}
		
        //Visibilidad bloque Cuota Final 40% (Contado 60+40)
		if(formaDePago == 'Contado' && tipoDeContado=='40_al_final'){
			$('#bloqueCuotaFinal_40').show();	
		} else {
			$('#bloqueCuotaFinal_40').hide();	
		}
		
        //Visibilidad bloque Financiacion
		if(formaDePago == 'Contado'){
			$('#bloqueFinanciacion').hide();
		} else {
			$('#bloqueFinanciacion').show();	
		}


	}


	/*------------------------------------------------------------------------
	PASO 4: COTIZADOR
	------------------------------------------------------------------------*/

	/*-------Impide valores vacios o negativos en campos de ingreso de cantidad (miles)*/
	$('body').on('change keydown keyup', '.miles', function(e) {
		if(e.key === '-'){ $(this).val(0); }
		if($(this).val() == ''){ $(this).val(0); }
	});

	/*--------------Btn Cambiar de Etapa*/
	$('#btn_cambiarEtapa').on('click', function(e) {
		e.preventDefault();
		$('#paso_4').hide();
		$('#paso_3').show();
		deslizar_a('paso_3_ancla', 0, 'fast');
	});

	/*--------------Variables y eventos Cotizador*/
	if($('input[name="ID_cotizacion"]').length > 0){
		
		//Set variables
		var smlv = $('input[name="smlv"]').val();
		var subsidioVis_0_2 = $('input[name="subsidioVis_0_2"]').val();
		var subsidioVis_2_4 = $('input[name="subsidioVis_2_4"]').val();
		var factorMillonUvr30 = $('input[name="factorMillonUvr30"]').val();
		var valorTotal;
		var valorSeparacion;
        var mesesSeparacion = 1;
		var valorIngresosFamiliares;
		var ingresoComoSmlv;
		var formaDePago;
		var tipoDeCredito;
		var tipoDeContado;
		var aporteAhorros;
		var aporteCesantias;
		var aportePrimas;
		var aporteTotal;
		var valorSubsidio;
		var valorCuotaInicial;
		var mesesCuotaInicial;
		var valorCuotaInicialMes;
		var valorCuotaFinal40;
		var valorFinanciado;
		var valorFinanciadoMax;
		var valorCuotaFinanciadoMes;
		var diferenciaAfavor;
		var valorGastosEscrituracion = 0;
		var valorEscrituraMes;

		/*-------Plan Pago Personalizado: Vars */
		var fechaProximoPago = '';
		var planPagoDetallado;

		/*-------Variables para recálculo Plan de Pago Detallado */
		var valorConceptosLocked;
		var valorConceptosARepartir;
		var cantConceptosUnlocked;
		var valorConceptoUnlocked;
		var valorEscriturasLocked;
		var valorEscriturasARepartir;
		var cantEscriturasUnlocked;
		var valorEscrituraUnlocked;
		var ppd_conceptoValor;
		var ppd_escrituraValor;
		var ppd_subtotal;
		var ppd_conceptosTotal;
		var ppd_escriturasTotal;
		var ppd_granTotal;
		var ppd_diferenciaConceptos;
		var ppd_diferenciaEscrituracion;		
		var ppd_separacionRecalculado;
		var ppd_aportesRecalculado;
		var ppd_subsidioRecalculado;
		var ppd_cuotaInicialRecalculado;
		var ppd_financiadoRecalculado;


		//Evento: escribir en campos de Ingreso Familiar, Ahorros, Cesantias, Primas
		$('body').on('change keydown keyup', '#valorIngresosFamiliares, #aporteAhorros, #aporteCesantias, #aportePrimas', function() {

			valorIngresosFamiliares = $('#valorIngresosFamiliares').val();
			aporteAhorros = $('#aporteAhorros').val();
			aporteCesantias = $('#aporteCesantias').val();
			aportePrimas = $('#aportePrimas').val();

			//Lleno campos ocultos
			$('input[name="valorIngresosFamiliares"]').val(valorIngresosFamiliares);
			$('input[name="aporteAhorros"]').val(aporteAhorros);
			$('input[name="aporteCesantias"]').val(aporteCesantias);
			$('input[name="aportePrimas"]').val(aportePrimas);

			//Corro funciones
			leeValoresBase();
			leeFormaDePago();
			calculaSubsidio();
			calculaAportes();
			calculaFinanciacion();
			calculaCuotaInicial();
			textosResultados();
            deshabilitoPlanPagoDetallado();

		});

		//Evento: Cambia forma de pago
		$('#formaDePago').on('change', function() {
			formaDePago = $(this).val();
			$('input[name="formaDePago"]').val(formaDePago);
			//Cambia Tipo
			if(formaDePago == 'Credito'){
				//opciones Credito
				$('#opcionesCredito').show();
				$('#tipoDeCredito option[val="70_30"]').attr('selected', true);
				$('input[name="tipoDeCredito"]').val('70_30');
				//opciones Contado
				$('#opcionesContado').hide();
				$('input[name="tipoDeContado"]').val('');
			}
			if(formaDePago == 'Contado'){
				//opciones Credito
				$('#opcionesCredito').hide();
				$('input[name="tipoDeCredito"]').val('');
				//opciones Contado
				$('#opcionesContado').show();
				$('#tipoDeContado option[val="Cuotas_Iguales"]').attr('selected', true);
				$('input[name="tipoDeContado"]').val('Cuotas_Iguales');
			}
			//Corro funciones
			leeValoresBase();
			leeFormaDePago();
			calculaSubsidio();
			calculaAportes();
			calculaFinanciacion();
			calculaCuotaInicial();
			textosResultados();
            deshabilitoPlanPagoDetallado();
		});

		//Evento: Cambia Tipo Financiacion
		$('#tipoDeCredito').on('change', function() {
			tipoDeCredito = $(this).val();
			$('input[name="tipoDeCredito"]').val(tipoDeCredito);
			//Corro funciones
			leeValoresBase();
			leeFormaDePago();
			calculaSubsidio();
			calculaAportes();
			calculaFinanciacion();
			calculaCuotaInicial();
			textosResultados();
            deshabilitoPlanPagoDetallado();	
		});

		//Evento: Cambia Tipo Contado
		$('#tipoDeContado').on('change', function() {
			tipoDeContado = $(this).val();
			$('input[name="tipoDeContado"]').val(tipoDeContado);
			//Corro funciones
			leeValoresBase();
			leeFormaDePago();
			calculaSubsidio();
			calculaAportes();
			calculaFinanciacion();
			calculaCuotaInicial();
			textosResultados();
            deshabilitoPlanPagoDetallado();
		});

		//Evento: llena Valor Gastos Escrituracion
		$('body').on('change keydown keyup', '#valorGastosEscrituracion', function() {
			
			//Valor
			valorGastosEscrituracion = $('#valorGastosEscrituracion').val();
		
			//Lleno el campo oculto
			$('input[name="valorGastosEscrituracion"]').val(valorGastosEscrituracion);

			//Corro funciones
			leeValoresBase();
			leeFormaDePago();
			calculaSubsidio();
			calculaAportes();
			calculaFinanciacion();
			calculaCuotaInicial();
			textosResultados();
            deshabilitoPlanPagoDetallado();

		});


	}

	/*--------------Btn ME GUSTA: Guarda Cotizacion*/
	$('#btn_guardaCotizacion').on('click', function(e) {

		e.preventDefault();

		//Inhabilita botón para evitar envíos múltiples
		$(this).addClass('disabled');
		$(this).find('span').html('Procesando...');

		//Actualiza Cotizacion en BD + Integra CRM
		$.ajax({
			type: 'POST',
			url: 'guarda-valores-cotizacion',
			data: $('#formCotizacion').serialize(),
			success:function(respuesta){
				json = jQuery.parseJSON(respuesta);
				//Obtengo ID_cotizacion y lleno campo oculto
				console.log(json);
				//Va a paso 4
				$('#paso_4').hide();
				$('#paso_5').show();
				deslizar_a('paso_5_ancla', 0, 'fast');
			}
		});
		
	});



	/*------------------------------------------------------------------------
	PLAN DE PAGO PERSONALIZADO
	------------------------------------------------------------------------*/

	/*-------Deshabilito */
	function deshabilitoPlanPagoDetallado(){
		if($('#plan_personalizado').length > 0){
			planPagoDetallado = 'NO';
			$('#paso_4 input[name="inputPlanPagoDetallado"][value="NO"]').prop('checked',true);
			$('#paso_4 input[name="planPagoDetallado"]').val('NO');
			$('#plan_personalizado #ppdCuotas *').remove();
			$('#plan_personalizado').hide();
            textosResultados();
		}	
	}

	/*-------Convierte Tasa EFectiva Anual a Tasa Efectiva Mensual */
	function convierteATasaEfectivaMensual(tasaEfectivaAnual){
		tasaEfectivaMensual = (((1+(tasaEfectivaAnual))**(1/12)-1)*100).toFixed(10);
		return tasaEfectivaMensual;
	}

	/*-------Genero Texto Fecha Hoy */
	function textoFechaHoy(){
		hoy = new Date();
		hoyYear = hoy.getFullYear();
		hoyMes = hoy.getMonth()+1;
		if(hoyMes < 10){
			hoyMes = '0'+hoyMes;
		}
		hoyDia = hoy.getDate();
		if(hoyDia < 10){
			hoyDia = '0'+hoyDia;	
		}
		txtFechaHoy = hoyYear+'-'+hoyMes+'-'+hoyDia;
		return txtFechaHoy;
	}

	/*-------Función Suma Meses a partir de fecha */
	function sumaMeses(fecha, $cantidadMeses){
		let fechaNueva = new Date(fecha);
		fechaNueva.setMonth(fechaNueva.getMonth() + $cantidadMeses);
		let fechaNuevaYear = fechaNueva.getFullYear();
		let fechaNuevaMes = fechaNueva.getMonth()+1;
		if(fechaNuevaMes < 10){
			fechaNuevaMes = '0'+fechaNuevaMes;
		}
		let fechaNuevaTxt = `${fechaNuevaYear}-${fechaNuevaMes}-28`;
		return fechaNuevaTxt;
	}

	/*-------Separadores de miles en numeros (lo use para ver mejor en el console.log) */
	function thousands_separators(num){
		var num_parts = num.toString().split('.');
		num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
		return num_parts.join('.');
	}

	/*-------Plan Pago Detallado: Funcion Construyo y muestro PPP */
	function seteoPlanPagoDetallado(){

		//Reset
		$('#plan_personalizado #ppdCuotas *').remove();

		//Fecha Separacion
		fechaSeparacionTxt = textoFechaHoy();
        //console.log(fechaSeparacionTxt);return false;
		
		//Valor escrituracion Mes
		valorEscrituraMes = valorGastosEscrituracion/mesesCuotaInicial;

		//En forma de pago 40% cuota final, ajusto valor escrituracion
		if(valorCuotaFinal40 > 0){
			valorEscrituraMes = valorGastosEscrituracion/(mesesCuotaInicial+1);	
		}

        //Separacion
		for(i=0; i<mesesSeparacion; i++){

            html = `
                <div class="fila">
                    <div class="txtConcepto">Separación</div>
                    <div>${fechaSeparacionTxt}</div>
                    <div>$${$.number(valorSeparacion/mesesSeparacion)}</div>
                    <div class="vacia">&nbsp;</div>
                    <div class="infoPropuesta item-concepto locked">
                        <span class="etiqueta-rspnsv">Propuesta del cliente:</span>						
                        <input 
                            type="text" 
                            name="ppd_conceptoValor[]" 
                            value="${Math.round(valorSeparacion/mesesSeparacion)}" 
                            class="miles conceptoValor ppd_separacionRecalculado" 
                        />
                        <a href="#" class="pin">pin</a>
                    </div>					
                    <div class="infoPropuesta vacia">
                        <input type="hidden" name="ppd_escrituraValor[]" value="0" class="escrituraValor"/>
                    </div>
                    <div class="txt_ppd_subtotal"><strong>$${$.number(valorSeparacion/mesesSeparacion)}</strong></div>
                    <input type="hidden" name="ppd_conceptoNombre[]" value="Separación"/>
                    <input type="hidden" name="ppd_conceptoFecha[]" class="ppd_conceptoFecha" value="${fechaSeparacionTxt}"/>
                    <input type="hidden" name="ppd_conceptoValorGalias[]" value="${Math.round(valorSeparacion/mesesSeparacion)}"/>
                    <input type="hidden" name="ppd_escrituraValorGalias[]" value="0"/>					
                    <input type="hidden" name="ppd_subtotal[]" class="ppd_subtotal" value="${Math.round(valorSeparacion/mesesSeparacion)}"/>
                </div>
            `;
            $('#ppdCuotas').append(html);

            //Actualizo Fecha proximo pago
            //fechaProximoPago = sumaMeses(fechaProximoPago, 1);

        }
		
		//Aporta Cesantías
		if(aporteCesantias > 0){
			html = `
				<div class="fila">
					<div class="txtConcepto">Aporte Cesantías</div>
					<div>${fechaSeparacionTxt}</div>
					<div>$${$.number(aporteCesantias)}</div>
					<div class="vacia">&nbsp;</div>
					<div class="infoPropuesta item-concepto locked">
						<span class="etiqueta-rspnsv">Propuesta del cliente:</span>
						<input 
							type="text" 
							name="ppd_conceptoValor[]" 
							value="${aporteCesantias}"
							class="miles conceptoValor ppd_aportesRecalculado"
							id="aporte_cesantias"
						/>
						<a href="#" class="pin">pin</a>
					</div>
					<div class="infoPropuesta vacia">
						<input type="hidden" name="ppd_escrituraValor[]" value="0" class="escrituraValor"/>
					</div>
					<div class="txt_ppd_subtotal"><strong>$${$.number(aporteCesantias)}</strong></div>
					<input type="hidden" name="ppd_conceptoNombre[]" value="Aporte Cesantías"/>
					<input type="hidden" name="ppd_conceptoFecha[]" class="ppd_conceptoFecha" value="${fechaSeparacionTxt}"/>
					<input type="hidden" name="ppd_conceptoValorGalias[]" value="${aporteCesantias}"/>
					<input type="hidden" name="ppd_escrituraValorGalias[]" value="0"/>					
					<input type="hidden" name="ppd_subtotal[]" class="ppd_subtotal" value="${Math.round(aporteCesantias)}"/>
				</div>
			`;
			$('#ppdCuotas').append(html);
		}
		
		//Aporta Ahorros
		if(aporteAhorros > 0){
			html = `
				<div class="fila">
					<div class="txtConcepto">Aporte Ahorros</div>
					<div>${fechaSeparacionTxt}</div>
					<div>$${$.number(aporteAhorros)}</div>
					<div class="vacia">&nbsp;</div>
					<div class="infoPropuesta item-concepto locked">
						<span class="etiqueta-rspnsv">Propuesta del cliente:</span>
						<input 
							type="text" 
							name="ppd_conceptoValor[]" 
							value="${aporteAhorros}" 
							class="miles conceptoValor ppd_aportesRecalculado"
							id="aporte_ahorros"
						/>
						<a href="#" class="pin">pin</a>
					</div>
					<div class="infoPropuesta vacia">
						<input type="hidden" name="ppd_escrituraValor[]" value="0" class="escrituraValor"/>
					</div>
					<div class="txt_ppd_subtotal"><strong>$${$.number(aporteAhorros)}</strong></div>
					<input type="hidden" name="ppd_conceptoNombre[]" value="Aporte Ahorros"/>
					<input type="hidden" name="ppd_conceptoFecha[]" class="ppd_conceptoFecha" value="${fechaSeparacionTxt}"/>
					<input type="hidden" name="ppd_conceptoValorGalias[]" value="${aporteAhorros}"/>
					<input type="hidden" name="ppd_escrituraValorGalias[]" value="0"/>
					<input type="hidden" name="ppd_subtotal[]" class="ppd_subtotal" value="${Math.round(aporteAhorros)}"/>
				</div>
			`;
			$('#ppdCuotas').append(html);
		}		

        //Fecha Primera Cuota
		fechaProximoPago = sumaMeses(fechaSeparacionTxt, 1);
		
		//Filas Cuotas Mensuales
		if(mesesCuotaInicial > 0){

			//Defino cantidad de Cuotas iguales
			cantidadMeses = mesesCuotaInicial;
			if(valorCuotaFinal40 == 0){
				cantidadMeses = mesesCuotaInicial - 1;
			}

			//Loop
			for (var i=0; i < cantidadMeses; i++) {

				//Html
				html = `
					<div class="fila">
						<div class="txtConcepto">Cuota ${i+1}</div>
						<div>${fechaProximoPago}</div>
						<div>$${$.number(valorCuotaInicialMes)}</div>
						<div>
							<span class="etiqueta-rspnsv">Escrituración</span>
							$${$.number(valorEscrituraMes)}
						</div>
						<div class="infoPropuesta item-concepto">
							<span class="etiqueta-rspnsv">Propuesta del cliente:</span>
							<input 
								type="text" 
								name="ppd_conceptoValor[]" 
								value="${valorCuotaInicialMes}" 
								class="miles conceptoValor ppd_cuotaInicialRecalculado"
							/>
							<a href="#" class="pin">pin</a>
						</div>												
						<div class="infoPropuesta item-escritura">
							<span class="etiqueta-rspnsv">Prop.Escr. Cliente:</span>
							<input 
								type="text" 
								name="ppd_escrituraValor[]" 
								value="${valorEscrituraMes}" 
								class="miles escrituraValor"
							/>
							<a href="#" class="pin">pin</a>
						</div>						
						<div class="txt_ppd_subtotal"><strong>$${$.number(valorCuotaInicialMes + valorEscrituraMes)}</strong></div>
						<input type="hidden" name="ppd_conceptoNombre[]" value="Cuota ${i+1}"/>
						<input type="hidden" name="ppd_conceptoFecha[]" class="ppd_conceptoFecha" value="${fechaProximoPago}"/>
						<input type="hidden" name="ppd_conceptoValorGalias[]" value="${valorCuotaInicialMes}"/>
						<input type="hidden" name="ppd_escrituraValorGalias[]" value="${valorEscrituraMes}"/>
						<input type="hidden" name="ppd_subtotal[]" class="ppd_subtotal" value="${Math.round(valorCuotaInicialMes + valorEscrituraMes)}"/>
					</div>
				`;
				$('#ppdCuotas').append(html);

				//Actualizo Fecha proximo pago
				fechaProximoPago = sumaMeses(fechaProximoPago, 1);

			}

		}
		
		//Cuota Último Mes (en forma de pago cuotas iguales)
		if(mesesCuotaInicial > 0 && valorCuotaFinal40 == 0){
			html = `
				<div class="fila">
					<div class="txtConcepto">Cuota ${mesesCuotaInicial}</div>
					<div>${fechaProximoPago}</div>
					<div>$${$.number(valorCuotaInicialMes)}</div>
					<div>
						<span class="etiqueta-rspnsv">Escrituración</span>
						$${$.number(valorEscrituraMes)}
					</div>
					<div class="infoPropuesta item-concepto">
						<span class="etiqueta-rspnsv">Propuesta del cliente:</span>						
						<input 
							type="text" 
							name="ppd_conceptoValor[]" 
							value="${valorCuotaInicialMes}" 
							class="miles conceptoValor ppd_cuotaInicialRecalculado"
							id="cuota_ultima"
						/>
						<a href="#" class="pin">pin</a>
					</div>
					<div class="infoPropuesta item-escritura">
						<span class="etiqueta-rspnsv">Prop.Escr. Cliente:</span>
						<input 
							type="text" 
							name="ppd_escrituraValor[]" 
							value="${valorEscrituraMes}" 
							class="miles escrituraValor"
						/>
						<a href="#" class="pin">pin</a>
					</div>
					<div class="txt_ppd_subtotal"><strong>$${$.number(valorCuotaInicialMes + valorEscrituraMes)}</strong></div>
					<input type="hidden" name="ppd_conceptoNombre[]" value="Cuota ${mesesCuotaInicial}"/>
					<input type="hidden" name="ppd_conceptoFecha[]" class="ppd_conceptoFecha" value="${fechaProximoPago}"/>
					<input type="hidden" name="ppd_conceptoValorGalias[]" value="${valorCuotaInicialMes}"/>
					<input type="hidden" name="ppd_escrituraValorGalias[]" value="${valorEscrituraMes}"/>
					<input type="hidden" name="ppd_subtotal[]" class="ppd_subtotal" value="${Math.round(valorCuotaInicialMes + valorEscrituraMes)}"/>
				</div>
			`;
			$('#ppdCuotas').append(html);
		}
		
		//Cuota Último Mes (Contado 60-40)
		if(valorCuotaFinal40 > 0){
			html = `
				<div class="fila">
					<div class="txtConcepto">Cuota ${mesesCuotaInicial + 1}</div>
					<div>${fechaProximoPago}</div>
					<div>$${$.number(valorCuotaFinal40)}</div>
					<div>
						<span class="etiqueta-rspnsv">Escrituración</span>
						$${$.number(valorEscrituraMes)}
					</div>
					<div class="infoPropuesta item-concepto">
						<span class="etiqueta-rspnsv">Propuesta del cliente:</span>						
						<input 
							type="text" 
							name="ppd_conceptoValor[]" 
							value="${valorCuotaFinal40}" 
							class="miles conceptoValor ppd_cuotaInicialRecalculado"
							id="cuota_ultima"
						/>
						<a href="#" class="pin">pin</a>
					</div>
					<div class="infoPropuesta item-escritura">
						<span class="etiqueta-rspnsv">Prop.Escr. Cliente:</span>
						<input 
							type="text" 
							name="ppd_escrituraValor[]" 
							value="${valorEscrituraMes}" 
							class="miles escrituraValor"
						/>
						<a href="#" class="pin">pin</a>
					</div>
					<div class="txt_ppd_subtotal"><strong>$${$.number(valorCuotaFinal40 + valorEscrituraMes)}</strong></div>
					<input type="hidden" name="ppd_conceptoNombre[]" value="Cuota ${mesesCuotaInicial + 1}"/>
					<input type="hidden" name="ppd_conceptoFecha[]" class="ppd_conceptoFecha" value="${fechaProximoPago}"/>
					<input type="hidden" name="ppd_conceptoValorGalias[]" value="${valorCuotaFinal40}"/>
					<input type="hidden" name="ppd_escrituraValorGalias[]" value="${valorEscrituraMes}"/>
					<input type="hidden" name="ppd_subtotal[]" class="ppd_subtotal" value="${Math.round(valorCuotaFinal40 + valorEscrituraMes)}"/>
				</div>
			`;
			$('#ppdCuotas').append(html);
		}

		//Desembolsos (Subsidio / Credito)
		if(valorSubsidio > 0 || valorFinanciado > 0){
			
			//Actualizo Fecha para desembolso Subsidio/Crédito
			fechaProximoPago = sumaMeses(fechaProximoPago, 1);

			//Subsidio
			if(valorSubsidio > 0){
				html = `
					<div class="fila">
						<div class="txtConcepto">Subsidio</div>
                        <div class="vacia">${''/*fechaProximoPago*/}</div>
						<div>$${$.number(valorSubsidio)}</div>
						<div class="vacia">&nbsp;</div>
						<div class="infoPropuesta item-concepto locked">
							<span class="etiqueta-rspnsv">Propuesta del cliente:</span>
							<input 
								type="text" 
								name="ppd_conceptoValor[]" 
								value="${valorSubsidio}" 
								class="miles conceptoValor ppd_subsidioRecalculado"
								id="cuota_subsidio"
							/>
							<a href="#" class="pin">pin</a>
						</div>
						<div class="infoPropuesta vacia">
							<input type="hidden" name="ppd_escrituraValor[]" value="0" class="escrituraValor"/>
						</div>
						<div class="txt_ppd_subtotal"><strong>$${$.number(valorSubsidio)}</strong></div>
						<input type="hidden" name="ppd_conceptoNombre[]" value="Subsidio"/>
						<input type="hidden" name="ppd_conceptoFecha[]" class="ppd_conceptoFecha" value="${''/*fechaProximoPago*/}"/>
						<input type="hidden" name="ppd_conceptoValorGalias[]" value="${valorSubsidio}"/>
						<input type="hidden" name="ppd_escrituraValorGalias[]" value="0"/>
						<input type="hidden" name="ppd_subtotal[]" class="ppd_subtotal" value="${Math.round(valorSubsidio)}"/>
					</div>
				`;
				$('#ppdCuotas').append(html);
			}

			//Crédito
			if(valorFinanciado > 0){
				html = `
					<div class="fila">
						<div class="txtConcepto">Crédito</div>
                        <div class="vacia">${''/*fechaProximoPago*/}</div>
						<div>$${$.number(valorFinanciado)}</div>
						<div class="vacia">&nbsp;</div>
						<div class="infoPropuesta item-concepto locked">
							<span class="etiqueta-rspnsv">Propuesta del cliente:</span>
							<input 
								type="text" 
								name="ppd_conceptoValor[]" 
								value="${valorFinanciado}" 
								class="miles conceptoValor ppd_financiadoRecalculado"
								id="cuota_financiado"
							/>
							<a href="#" class="pin">pin</a>
						</div>
						<div class="infoPropuesta vacia">
							<input type="hidden" name="ppd_escrituraValor[]" value="0" class="escrituraValor"/>
						</div>
						<div class="txt_ppd_subtotal"><strong>$${$.number(valorFinanciado)}</strong></div>
						<input type="hidden" name="ppd_conceptoNombre[]" value="Crédito"/>
						<input type="hidden" name="ppd_conceptoFecha[]" class="ppd_conceptoFecha" value="${''/*fechaProximoPago*/}"/>
						<input type="hidden" name="ppd_conceptoValorGalias[]" value="${valorFinanciado}"/>
						<input type="hidden" name="ppd_escrituraValorGalias[]" value="0"/>
						<input type="hidden" name="ppd_subtotal[]" class="ppd_subtotal" value="${Math.round(valorFinanciado)}"/>
					</div>
				`;
				$('#ppdCuotas').append(html);
			}

		}

		//Fila con Totales
		html = `
			<div class="fila totales">
				<div><strong>TOTAL</strong></div>
				<div class="vacia">&nbsp;</div>
				<div>
					<span class="txt_totales">Forma de pago Las Galias: </span>
					<strong>$${$.number(valorTotal)}</strong>
				</div>
				<div>
					<span class="txt_totales">Escrituración: </span>
					<strong>$${$.number(valorGastosEscrituracion)}</strong>
				</div>
				<div class="infoPropuesta">
					<span class="txt_totales">Propuesta Cliente: </span>
					<strong>$<span id="ppd_conceptosTotal">${$.number(valorTotal)}</span></strong>
				</div>
				<div class="infoPropuesta">
					<span class="txt_totales">Prop. Escr. Cliente: </span>
					<strong>$<span id="ppd_escriturasTotal">${$.number(valorGastosEscrituracion)}</span></strong>
				</div>
				<div>
					<span class="txt_totales">TOTAL: </span>
					<strong>$<span id="ppd_granTotal">${$.number(parseInt(valorTotal) + parseInt(valorGastosEscrituracion))}</span></strong>
				</div>
			</div>
		`;
		$('#ppdCuotas').append(html);

		//Campos ocultos donde guardaré los valores totales Recalculados
		html = `
			<input type="hidden" name="ppd_separacionRecalculado" value="${valorSeparacion}"/>
            <input type="hidden" name="ppd_aportesRecalculado" value="${aporteTotal}"/>
            <input type="hidden" name="ppd_subsidioRecalculado" value="${valorSubsidio}"/>
            <input type="hidden" name="ppd_cuotaInicialRecalculado" value="${valorCuotaInicial}"/>
            <input type="hidden" name="ppd_financiadoRecalculado" value="${valorFinanciado}"/>
		`;
		$('#ppdCuotas').append(html);

		//Re-ejecuto jQuery.number.min
		$('.miles').number(true);		

	}

	/*-------Plan Pago Personalizado: Recálculo */
	function recalculaPlanPagoDetallado(){

		//Set Valor Campos Locked
		valorConceptosLocked = 0;
		valorEscriturasLocked = 0;

		//Sumo valor de Campos Conceptos que están Locked
		$('#ppdCuotas .item-concepto.locked').each(function(){
			ppd_conceptoValor = $(this).find('input').val();
			valorConceptosLocked = parseFloat(valorConceptosLocked) + parseFloat(ppd_conceptoValor);
		});

		//Redistribuyo Valores Conceptos entre los que están Unlocked
		valorConceptosARepartir = parseFloat(valorTotal) - parseFloat(valorConceptosLocked);
		cantConceptosUnlocked = $('#ppdCuotas .item-concepto:not(.locked)').length;
		valorConceptoUnlocked = parseFloat(valorConceptosARepartir) / parseFloat(cantConceptosUnlocked);
		$('#ppdCuotas .item-concepto:not(.locked)').each(function(){
			$(this).find('input').val($.number(valorConceptoUnlocked));
		});

		//Sumo valor de Campos Escrituracion que están Locked
		$('#ppdCuotas .item-escritura.locked').each(function(){
			ppd_escrituraValor = $(this).find('input').val();
			valorEscriturasLocked = parseFloat(valorEscriturasLocked) + parseFloat(ppd_escrituraValor);
		});

		//Redistribuyo Valores Escrituracion entre los que están Unlocked
		valorEscriturasARepartir = parseFloat(valorGastosEscrituracion) - parseFloat(valorEscriturasLocked);
		cantEscriturasUnlocked = $('#ppdCuotas .item-escritura:not(.locked)').length;
		valorEscrituraUnlocked = parseFloat(valorEscriturasARepartir) / parseFloat(cantEscriturasUnlocked);
		$('#ppdCuotas .item-escritura:not(.locked)').each(function(){
			$(this).find('input').val($.number(valorEscrituraUnlocked));
		});

		//Set Totales
		ppd_conceptosTotal = 0;
		ppd_escriturasTotal = 0;
		ppd_granTotal = 0;

		//Actualizo Valor Subtotal de cada fila
		$('#ppdCuotas .fila:not(.totales)').each(function(){

			//Valores
			ppd_conceptoValor = $(this).find('.conceptoValor').val();
			ppd_escrituraValor = $(this).find('.escrituraValor').val();
			ppd_subtotal = parseFloat(ppd_conceptoValor) + parseFloat(ppd_escrituraValor);

			//Actualizo en pantalla y campo oculto
			$(this).find('.txt_ppd_subtotal strong').text('$'+$.number(ppd_subtotal));
			$(this).find('.ppd_subtotal').val(parseInt(ppd_subtotal));

			//Sumo totales
			ppd_conceptosTotal = parseFloat(ppd_conceptosTotal) + parseFloat(ppd_conceptoValor);
			ppd_escriturasTotal = parseFloat(ppd_escriturasTotal) + parseFloat(ppd_escrituraValor);
			ppd_granTotal = parseFloat(ppd_granTotal) + parseFloat(ppd_subtotal);

		});

		//Set Repetir funcion
		let repetirLlenadoSubtotales = false;

		//Ajusto diferencia en ultima cuota Conceptos
		if(ppd_conceptosTotal != valorTotal){
			ppd_diferenciaConceptos = parseFloat(valorTotal) - parseFloat(ppd_conceptosTotal);
			//console.log(ppd_diferenciaConceptos);
			$('.conceptoValor:last').val( parseFloat($('.conceptoValor:last').val()) + parseFloat(ppd_diferenciaConceptos) );
			ppd_conceptosTotal = valorTotal;
			repetirLlenadoSubtotales = true;
		}

		//Ajusto diferencia en ultima cuota Escrituracion
		if(ppd_escriturasTotal != valorGastosEscrituracion){
			ppd_diferenciaEscrituracion = parseFloat(valorGastosEscrituracion) - parseFloat(ppd_escriturasTotal);
			//console.log(ppd_diferenciaEscrituracion);
			$('.escrituraValor.miles:last').val( parseFloat($('.escrituraValor.miles:last').val()) + parseFloat(ppd_diferenciaEscrituracion) );
			ppd_escriturasTotal = valorGastosEscrituracion;
			repetirLlenadoSubtotales = true;
		}

		//Repite funcion para reajustar
		if(repetirLlenadoSubtotales == true){
			$('#ppdCuotas .fila').each(function(){

				//Valores
				ppd_conceptoValor = $(this).find('.conceptoValor').val();
				ppd_escrituraValor = $(this).find('.escrituraValor').val();
				ppd_subtotal = parseFloat(ppd_conceptoValor) + parseFloat(ppd_escrituraValor);

				//Actualizo en pantalla y campo oculto
				$(this).find('.txt_ppd_subtotal strong').text('$'+$.number(ppd_subtotal));
				$(this).find('.ppd_subtotal').val(ppd_subtotal);

			});
		}

		//Evito Total con valor cero
		if(ppd_granTotal == 0){
			ppd_granTotal = parseFloat(ppd_conceptosTotal) + parseFloat(ppd_escriturasTotal);	
		}

		//Muestro En Fila de Totales
		$('#ppd_conceptosTotal').text($.number(ppd_conceptosTotal));
		$('#ppd_escriturasTotal').text($.number(ppd_escriturasTotal));
		$('#ppd_granTotal').text($.number(ppd_granTotal));

        //Set valor recalculados
        ppd_separacionRecalculado = 0;
		ppd_aportesRecalculado = 0;
		ppd_subsidioRecalculado = 0;
		ppd_cuotaInicialRecalculado = 0;
		ppd_financiadoRecalculado = 0;

        //Aportes Recalculados, tras manipulación
        $('#ppdCuotas .ppd_aportesRecalculado').each(function(){            
            ppd_aportesRecalculado = parseFloat(ppd_aportesRecalculado) + parseFloat($(this).val());  
        });

        //Separación Recalculada, tras manipulación
        $('#ppdCuotas .ppd_separacionRecalculado').each(function(){            
            ppd_separacionRecalculado = parseFloat(ppd_separacionRecalculado) + parseFloat($(this).val());  
        });

        //Cuota Inicial Recalculada, tras manipulación
        $('#ppdCuotas .ppd_cuotaInicialRecalculado').each(function(){            
            ppd_cuotaInicialRecalculado = parseFloat(ppd_cuotaInicialRecalculado) + parseFloat($(this).val());  
        });

        //Subsidio Recalculada, tras manipulación
        $('#ppdCuotas .ppd_subsidioRecalculado').each(function(){            
            ppd_subsidioRecalculado = parseFloat(ppd_subsidioRecalculado) + parseFloat($(this).val());  
        });

        //Financiación Recalculada, tras manipulación
        $('#ppdCuotas .ppd_financiadoRecalculado').each(function(){            
            ppd_financiadoRecalculado = parseFloat(ppd_financiadoRecalculado) + parseFloat($(this).val());  
        });

        //Debug
        //console.log(thousands_separators(ppd_aportesRecalculado));
        //console.log(thousands_separators(ppd_separacionRecalculado));
        //console.log(thousands_separators(ppd_cuotaInicialRecalculado));
        //console.log(thousands_separators(ppd_subsidioRecalculado));
        //console.log(thousands_separators(ppd_financiadoRecalculado));

        //Lleno campos ocultos
        $('input[name="ppd_aportesRecalculado"]').val(ppd_aportesRecalculado);
        $('input[name="ppd_separacionRecalculado"]').val(ppd_separacionRecalculado);
        $('input[name="ppd_cuotaInicialRecalculado"]').val(ppd_cuotaInicialRecalculado);
        $('input[name="ppd_subsidioRecalculado"]').val(ppd_subsidioRecalculado);
        $('input[name="ppd_financiadoRecalculado"]').val(ppd_financiadoRecalculado);

        //Muestro Textos Recalculados
        mostrarTextosRecalculadosPlanPagoDetallado();

	}

    /*-------Plan Pago Personalizado: Mostrar Textos Recalculados */
    function mostrarTextosRecalculadosPlanPagoDetallado(){

        $('#txtSubproyectoValorSeparacion').text( '$'+ $.number(ppd_separacionRecalculado) );
        $('#txtAportesAhorrosCesantias').text( '$'+ $.number(ppd_aportesRecalculado) );
        $('#txtValorCuotaInicial').text( '$'+ $.number(ppd_cuotaInicialRecalculado) );
        $('#txtValorSubsidio').text( '$'+ $.number(ppd_subsidioRecalculado) );
        $('#txtValorFinanciado').text( '$'+ $.number(ppd_financiadoRecalculado) );

		//Visibilidad bloque Aportes
		if(ppd_aportesRecalculado == 0){
			$('#bloqueAportes').hide();
		} else {
			$('#bloqueAportes').show();	
		}

		//Visibilidad bloque Subsidios
		if(ppd_subsidioRecalculado == 0){
			$('#bloqueSubsidio').hide();
		} else {
			$('#bloqueSubsidio').show();	
		}

        //Oculto párrafos con datos de cuotas mensuales C.Inicial y Financiacón
        $('#bloqueCuotaInicial p').addClass('invisible');
        $('#bloqueFinanciacion p').addClass('invisible');

        //Visibilidad bloque Financiación
		if(ppd_financiadoRecalculado == 0){
			$('#bloqueFinanciacion').hide();
		} else {
			$('#bloqueFinanciacion').show();	
		}

    }


	/*-------Plan Pago Personalizado: Radios Muestra/Oculta Plan de Pago Detallado*/
	$('#paso_4 input[name="inputPlanPagoDetallado"]').click(function(){
		planPagoDetallado = $(this).val();
		if(planPagoDetallado == 'SI'){		
			$('input[name="planPagoDetallado"]').val('SI');
			seteoPlanPagoDetallado();
			$('#plan_personalizado').show();
		}
		else{				
			deshabilitoPlanPagoDetallado();				
		}
	});

	/*-------Bloquea, Dsbloquea campos editables para propuesta de cliente*/
	$('body').on('click', '.pin', function() {
		$(this).parent().toggleClass('locked');
		return false;
	});

	/*-------Plan Pago Personalizado: Calcula mediante Boton*/
	$('body').on('click', '#btn_ppdCuotas .btn', function() {
		recalculaPlanPagoDetallado();
		return false;
	});	



	/*------------------------------------------------------------------------
	ACCIONES CON COTIZACION
	------------------------------------------------------------------------*/

	/*--------------Desplegable Copias Correo*/
	$('.parent').on('click', function(e) {
		e.preventDefault();
		$(this).next('.desplegable').toggle('fast');
		$(this).toggleClass('activo');
	});

	/*--------------Botones Acciones*/
	$('.accionCotizador').on('click', function(e) {

		e.preventDefault();
		var accion = $(this).attr('rel');
		var formulario = $('#formCotizacion');
		var urlDestino = 'acciones-cotizacion/'+accion;

		//Cuando es Generar PDF, hago submit en _blank
		if(accion == 'pdf'){
			formulario.attr('action', urlDestino);
			formulario.submit();
			return false;
		}

		//Cuando es Emails o Solicitar Opcion, envio por AJAX
		else{

			//Limpia action del orm
			formulario.attr('action', '');

			//Cuando es Enviar Copias -> Valido correos
			if(accion=='copias-email'){
				var copiaEmail1 = $('input[name="copiaEmail1"]').val();
				var copiaEmail2 = $('input[name="copiaEmail2"]').val();
				if(copiaEmail1 == '' && copiaEmail2 == ''){
					swal('Error!', 'Debes ingresar al menos un email para enviar la copia de tu cotización.', 'error');
					return false;	
				}
				if ( copiaEmail1 != '' && (!/(.+)@(.+){2,}\.(.+){2,}/.test(copiaEmail1))) {
					swal('Error', 'Para la copia 1 debes usar un email válido.', 'error');
					return false;
				}
				if ( copiaEmail2 != '' && (!/(.+)@(.+){2,}\.(.+){2,}/.test(copiaEmail2))) {
					swal('Error', 'Para la copia 2 debes usar un email válido.', 'error');
					return false;
				}
			}

			//Envio Form
			$.ajax({
				type: 'POST',
				url: urlDestino,
				data: $(formulario).serialize(),
				success:function(respuesta){
					json = jQuery.parseJSON(respuesta);
					//Obtengo Mensaje
					var asunto = json.mensaje.asunto;
					swal('¡Hecho!', asunto, 'success');
					return false;
				}
			});

		}
		
	});


});
</script>