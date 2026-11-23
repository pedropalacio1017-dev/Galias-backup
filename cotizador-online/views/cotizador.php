<!DOCTYPE html>
<html lang="es">
<head>
<?php
$page_metatitle = PAGE_META_TITLE;
$page_metadescription = '';
include('views/includes/head.php');
?>
</head>

<body>

	<?php #----------Header ?>
	<?php include('views/includes/header.php');?>


	<div class="fila formulario" id="formulario">
		<div class="cols" id="formulario_ancla">
			<div class="col col_1">

				<?php #----------Titulo ?>
				<?php include('views/includes/titulo.php');?>

				<form id="formCotizacion" action="" method="POST" target="_blank">

					<?php #----------Inputs Usuario ?>
					<input type="hidden" name="ID_usuario" value="<?php echo $ID_usuario;?>" />

					<?php #----------Inputs Regional, Proyecto ?>
					<input type="hidden" name="ID_regional" value="<?php echo $ID_regional;?>" />
					<input type="hidden" name="ID_proyecto" value="<?php echo $ID_proyecto;?>" />
					<input type="hidden" name="proyectoNombre" value="<?php echo $proyecto['proyectoNombre'];?>" />
					<input type="hidden" name="proyectoCodigoCrm" value="<?php echo $proyecto['proyectoCodigoCrm'];?>" />

					<?php #----------Inputs Cliente ?>
					<input type="hidden" name="clienteNombres" value="" />
					<input type="hidden" name="clienteApellidos" value="" />
					<input type="hidden" name="clienteEmail" value="" />
					<input type="hidden" name="clienteCelular" value="" />
					<input type="hidden" name="clienteDocumento" value="" />
					<input type="hidden" name="clienteAceptaPolitica" value="" />
					<input type="hidden" name="clienteIp" value="<?php echo $clienteIp;?>" />

					<?php #----------Inputs Subproyecto ?>
					<input type="hidden" name="ID_subproyecto" value="" />
					<input type="hidden" name="subproyectoNombre" value="" />
					<input type="hidden" name="subproyectoPrecio" value="" />
					<input type="hidden" name="subproyectoValorSeparacion" value="" />
					<input type="hidden" name="subproyectoEntregas" value="" />
					<input type="hidden" name="subproyectoMesesPago" value="" />
					<input type="hidden" name="subproyectoCodigoCrm" value="" />

					<?php #----------Inputs Valores Base Cotizacion ?>
					<input type="hidden" name="smlv" value="<?php echo $varsCotizador['smlv'];?>" />
					<input type="hidden" name="subsidioVis_0_2" value="<?php echo $varsCotizador['subsidioVis_0_2'];?>" />
					<input type="hidden" name="subsidioVis_2_4" value="<?php echo $varsCotizador['subsidioVis_2_4'];?>" />
					<input type="hidden" name="subsidioConcurrente" value="<?php echo $varsCotizador['subsidioConcurrente'];?>" />
					<input type="hidden" name="factorMillonUvr30" value="<?php echo $varsCotizador['factorMillonUvr30'];?>" />
					
					<?php #----------Inputs Cotizacion ?>
					<input type="hidden" name="ID_cotizacion" value="" />
					<input type="hidden" name="valorIngresosFamiliares" value="0" />
					<input type="hidden" name="formaDePago" value="Credito" />
					<input type="hidden" name="tipoDeCredito" value="70_30" />
					<input type="hidden" name="tipoDeContado" value="" />
					<input type="hidden" name="aporteAhorros" value="0" />
					<input type="hidden" name="aporteCesantias" value="0" />
					<input type="hidden" name="aportePrimas" value="0" />
					<input type="hidden" name="aporteTotal" value="0" />
					<input type="hidden" name="valorSubsidio" value="0" />
					<input type="hidden" name="valorCuotaInicial" value="0" />
					<input type="hidden" name="mesesCuotaInicial" value="0" />
					<input type="hidden" name="valorCuotaInicialMes" value="0" />
					<input type="hidden" name="valorCuotaFinal40" value="0" />
					<input type="hidden" name="valorFinanciado" value="0" />
					<input type="hidden" name="valorCuotaFinanciadoMes" value="0" />

					<?php #----------Input Gastos Escrituración ?>
					<input type="hidden" name="valorGastosEscrituracion" value="0" />

					<?php #----------Inputs Plan de Pagos Detallado ?>
					<input name="planPagoDetallado" type="hidden" value="NO" />

					
                    <?php #----------PASO 2: Ingresa datos personales ?>
					<div class="fila paso paso_2" id="paso_2">
						<div class="cols minimargen">
							<div class="col col_1_4">
								<?php include('views/includes/logo-proyecto.php');?>
							</div>
							<div class="col col_3_4" id="paso_2_ancla">
								<h3 class="num_text">
									<span class="num">1</span>
									<span class="text">Necesitamos conocer <strong>tus datos</strong></span>
								</h3>
								<p class="margen">Ten en cuenta que el email que ingreses es al que enviaremos tu
									cotización</p>
								<div class="cols">
									<div class="col col_2">
										<input type="text" id="clienteNombres" placeholder="Nombres *" value="<?php echo $clienteNombres;?>">
									</div>
									<div class="col col_2">
										<input type="text" id="clienteApellidos" placeholder="Apellidos *" value="<?php echo $clienteApellidos;?>">
									</div>
								</div>
								<div class="cols">
									<div class="col col_2">
										<input type="text" id="clienteEmail" placeholder="Email *" value="<?php echo $clienteEmail;?>">
									</div>
									<div class="col col_2">
										<input type="text" id="clienteEmailRepite" placeholder="Confirmar email *" value="<?php echo $clienteEmail;?>">
									</div>
								</div>
								<div class="cols minimargen">
									<div class="col col_2">
										<input type="text" id="clienteCelular" placeholder="Celular *" value="<?php echo $clienteCelular;?>">
									</div>
									<div class="col col_2">
										<input type="text" id="clienteDocumento" placeholder="Documento *" value="<?php echo $clienteDocumento;?>">
									</div>
								</div>
								<hr class="full" />
								<div class="aviso_privacidad minimargen">
									<div>
										<input id="clienteAceptaPolitica" type="checkbox" value="1" checked="checked">
										<p class="etiqueta">Conozco y acepto la <a target="_blank" href="https://galias.com.co/politica-privacidad/">Política de manejo de datos personales de LAS GALIAS</a></p>
									</div>
								</div>
								<div class="fila"><a href="#" class="boton" id="continuaAPaso3">CONTINUAR</a></div>
							</div>
						</div>
					</div>

					
                    <?php #----------PASO 3: Selecciona Etapa ?>
					<div class="fila paso paso_3" id="paso_3">
						<div class="cols minimargen">
							<div class="col col_1_4">
								<?php include('views/includes/logo-proyecto.php');?>
							</div>
							<div class="col col_3_4" id="paso_3_ancla">
								<h3 class="num_text">
									<span class="num">2</span>
									<span class="text"><span class="txtClienteNombre"></span>, ahora selecciona la <strong>etapa</strong> en la que cotizarás.</span>
								</h3>
								<p class="margen">Ten en cuenta el momento de entrega; de él depende el número de meses para pagar la cuota inicial (a más meses, más económica cada cuota).</p>
								<div class="fila minimargen">
									<?php foreach($subproyectos as $subproyecto){?>
									<div class="etapa">
										<div>
											<h3><?php echo $subproyecto['subproyectoNombre'];?></h3>
										</div>
										<div>
											<p class="minimargen"><?php echo $subproyecto['subproyectoEntregas'];?><br />
												<strong><?php echo $subproyecto['subproyectoMesesPago'];?> meses para pagar la cuota inicial</strong>
											</p>
											<p>Desde $<?php echo number_format($subproyecto['subproyectoPrecio']);?></p>
										</div>
										<div>
											<a
												href="#"
												class="boton seleccionaSubproyecto"
												data-subproyecto-id="<?php echo $subproyecto['ID_subproyecto'];?>"
												data-subproyecto-nombre="<?php echo $subproyecto['subproyectoNombre'];?>"
												data-subproyecto-entregas="<?php echo $subproyecto['subproyectoEntregas'];?>"
												data-subproyecto-meses-pago="<?php echo $subproyecto['subproyectoMesesPago'];?>"
												data-subproyecto-precio="<?php echo $subproyecto['subproyectoPrecio'];?>"
												data-subproyecto-valor-separacion="<?php echo $subproyecto['subproyectoValorSeparacion'];?>"
												data-subproyecto-codigo-crm="<?php echo $subproyecto['subproyectoCodigoCrm'];?>"
											>Cotizar</a>
										</div>
									</div>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>

					
                    <?php #----------PASO 4: Cotizador ?>
					<div class="fila paso paso_4" id="paso_4">
						<div class="cols minimargen">
							<div class="col col_1_4">
								<?php include('views/includes/logo-proyecto.php');?>
								<?php include('views/includes/info-general-subproyecto.php');?>
							</div>
							<div class="col col_3_4" id="paso_4_ancla">
								<a href="#" class="boton volver" id="btn_cambiarEtapa"><span>Cambiar Etapa</span></a>
								<h3 class="num_text">
									<span class="num">3</span>
									<span class="text"><span class="txtClienteNombre"></span>, ahora indícanos por favor <strong>los ingresos de tu núcleo familiar</strong></span>
								</h3>
								<p class="margen"><strong>IMPORTANTE:</strong> Sólo con este dato podremos recomendarte los subsidios a los que puedes aplicar y estimar el valor máximo que podrás financiar.</p>

								<?php #----------Ingreso Familiar ?>
								<div class="cols">
									<div class="col col_2">
										<fieldset>
											<label>Ingreso familiar (*)</label>
											<input type="text" id="valorIngresosFamiliares" class="miles" value="0">
										</fieldset>
									</div>
									<div class="col col_2 vacia">
									</div>
								</div>

								<?php #----------Forma de Pago ?>
								<hr class="full" />
								<h3 class="num_text">
									<span class="num">4</span>
									<span class="text">¿Cómo prefieres pagar?</span>
								</h3>
								<div class="cols">
									<div class="col col_2 mediamargen">
										<label>Forma de Pago (*)</label>
										<select id="formaDePago">
											<option value="Credito">Crédito hipotecario</option>
											<option value="Contado">Contado</option>
										</select>
									</div>
									<div class="col col_2" id="opcionesCredito">
										<label>Tipo de crédito (*)</label>
										<select id="tipoDeCredito">
											<?php #<option value="60_40">60% crédito + 40% cuota inicial</option>?>
											<option value="70_30">70% crédito + 30% cuota inicial</option>
											<option value="80_20">80% crédito + 20% cuota inicial</option>
										</select>
									</div>
									<div class="col col_2" id="opcionesContado">
										<label>Tipo de contado (*)</label>
										<select id="tipoDeContado">
											<option value="Cuotas_Iguales">Sin financiación, cuotas iguales</option>
											<option value="Una_Cuota">Contado 1 cuota</option>
											<option value="40_al_final">60% cuotas iguales + 40% en cuota final</option>
										</select>
									</div>
								</div>

								<?php #----------Ahorros, Cesantías, Primas ?>
								<hr class="full" />
								<h3 class="num_text">
									<span class="num">5</span>
									<span class="text">¿Cuentas con <strong>ahorros, cesantías o primas</strong>?</span>
								</h3>
								<p class="margen">Lo que puedas aportar ayudará a bajar el valor de tus cuotas mensuales.</p>
								<div class="cols left">
									<div class="col col_2">
										<label>¿Cuéntas con Ahorros?</label>
										<input type="text" id="aporteAhorros" class="miles" value="0">
									</div>
									<div class="col col_2">
										<label>¿Cuéntas con Cesantías presentes o futuras?</label>
										<input type="text" id="aporteCesantias" class="miles" value="0">
									</div>
								</div>
								<div class="cols left <?php if($esAsesor == true){echo 'invisible';}?>">
									<div class="col col_2">
										<label>¿Cuéntas con Primas?</label>
										<input type="text" id="aportePrimas" class="miles" value="0">
									</div>
									<div class="col col_2 vacia"></div>
								</div>

								<?php #----------Esta es tu cotizacion ?>
								<hr class="full" />
								<h3 class="num_text">
									<span class="num">6</span>
									<span class="text"><span class="txtClienteNombre"></span> <strong>esta es tu cotización Las Galias:</strong></span>
								</h3>
								<div class="fila mediamargen">
									
									<?php #----------Valor Separacion ?>
									<div class="cols etapa">
										<div class="col col_2">
											<h3>Valor separación</h3>
										</div>
										<div class="col col_2">
											<h4 id="txtSubproyectoValorSeparacion"></h4>
										</div>
									</div>
									
									<?php #----------Aportes ahorros y cesantías ?>
									<div class="cols etapa" id="bloqueAportes">
										<div class="col col_2">
											<h3>Aportes</h3>
										</div>
										<div class="col col_2">
											<h4 id="txtAportesAhorrosCesantias"></h4>
											<?php if($esAsesor == false){ ?>
											<p>Ahorros, Cesantías y/o Primas</p>
											<?php } ?>
											<?php if($esAsesor == true){ ?>
											<p>Ahorros y/o Cesantías</p>
											<?php } ?>
										</div>
									</div>

									<?php #----------Subsidio ?>
									<div class="cols etapa" id="bloqueSubsidio">
										<div class="col col_2">
											<h3>Subsidios</h3>
										</div>
										<div class="col col_2">
											<h4 id="txtValorSubsidio"></h4>
											<p>Sujeto a aprobación de Caja de Compensación y/o Mi Casa Ya.</p>
										</div>
									</div>
									
									<?php #----------Cuota Inicial ?>
									<div class="cols etapa" id="bloqueCuotaInicial">
										<div class="col col_2">
											<h3>Cuota Inicial</h3>
										</div>
										<div class="col col_2">
											<h4 id="txtValorCuotaInicial"></h4>
											<p><span id="txtMesesCuotaInicial"></span> meses<br />Cuota mensual: <span id="txtValorCuotaInicialMes"></span></p>
											<p id="bloqueCuotaFinal_40">Cuota Final 40%: <span id="txtValorCuotaFinal_40"></span></p>
										</div>
									</div>

									<?php #----------Financiacion ?>
									<div class="cols etapa" id="bloqueFinanciacion">
										<div class="col col_2">
											<h3>Financiado</h3>
										</div>
										<div class="col col_2">
											<h4 id="txtValorFinanciado"></h4>
											<p>Crédito en UVR, 30 años<br>Cuota mensual aprox: <span id="txtValorCuotaFinanciadoMes"></span><br>Sujeto a condiciones de endeudamiento, ingresos y políticas de las diferentes entidades financieras.</p>
										</div>
									</div>

									<?php #----------Gastos Escrituración ?>
									<?php if($esAsesor == true){?>
										<div class="cols etapa">
											<div class="col col_2">
												<h3>Gastos Escrituración</h3>
											</div>
											<div class="col col_2">
												<input type="text" id="valorGastosEscrituracion" class="miles" value="0">
											</div>
										</div>
									<?php } ?>
									
								</div>

								<?php #----------Observaciones ?>
								<?php if($esAsesor == true){?>
									<div class="fila margen">
										<div class="cols">
											<div class="col col_1">
												<label><strong>Observaciones:</strong></label>
												<textarea name="observaciones"></textarea>
											</div>
										</div>
									</div>	
								<?php } ?>

								<?php #----------Plan de Pagos Detallado / propuesta del Cliente (v.2023) - Visible sólo para Asesores ?>
								<?php if($esAsesor == true){?>									                       
									<div id="bloque_ppd" class="margen">
										<h3 class="num_text">
											<span class="text"><strong>¿Ver el plan de pagos detallado?</strong></span>
										</h3>
										<div id="ppd-si-no">
											<div>
												<input type="radio" name="inputPlanPagoDetallado" value="NO" checked />
												<label>NO</label>
											</div>
											<div>
												<input type="radio" name="inputPlanPagoDetallado" value="SI" />
												<label>SI</label>
											</div>
										</div>
										<div id="plan_personalizado">

											<div class="fila encabezado">
												<div><strong>Concepto</strong></div>
												<div><strong>Fecha</strong></div>
												<div><strong>Valor<br>Base</strong></div>
												<div><strong>Escriturac.</strong></div>
												<div class="infoPropuesta"><strong>Propuesta<br>Cliente</strong></div>
												<div class="infoPropuesta"><strong>Escr.<br>Cliente</strong></div>
												<div><strong>Subtotal</strong></div>
											</div>
											
											<?php #<input type="hidden" id="saldoDeAnterior" value="0" /> ?>

											<div id="ppdCuotas"></div>
											
											<div id="btn_ppdCuotas" class="no-print">
												<a href="#" class="btn">Aplicar</a>
											</div>
											
											<p class="aclaracion invisible"><strong>Importante:</strong> Nota legal...</p>
											
										</div>

									</div>
								<?php } ?>
								
								<?php #----------Ponte en contacto ?>
								<div class="fila margen">
									<?php include('views/includes/bloque-ponte-en-contacto.php');?>
								</div>

								<?php #----------Me Gusta ?>
								<hr class="full margen">
								<h6><span class="txtClienteNombre"></span>, para guardar, imprimir o enviar a tu email<br />
									<span>haz clic en el siguiente botón</span>
								</h6>
								<div class="fila margen"><a href="#" class="boton me_gusta" id="btn_guardaCotizacion"><span>Me gusta</span></a></div>

								<?php #----------Notas legales ?>
								<p class="notas_legales"><?php echo nl2br($proyecto['notaLegal']);?></p>


							</div>
						</div>
					</div>

					
                    <?php #----------PASO 5: Acciones ?>
					<div class="fila paso paso_5" id="paso_5">
						<div class="cols minimargen">
							<div class="col col_1_4">
								<?php include('views/includes/logo-proyecto.php');?>
								<?php include('views/includes/info-general-subproyecto.php');?>
							</div>
							<div class="col col_3_4" id="paso_5_ancla">
								<h3 class="num_text">
									<span class="num">7</span>
									<span class="text"><strong class="black"><span class="txtClienteNombre"></span></strong>, ¿qué quieres hacer con tu cotización?</span>
								</h3>

								<?php #----------Acciones ?>								
								<div class="fila margen">
									
									<?php #----------Correo ?>
									<div class="etapa padless">
										<a class="link accionCotizador" href="#" rel="email">
											<img src="views/images/ico_mail.svg">
											<h3><strong>Enviar</strong> a tu correo electrónico</h3>
										</a>
									</div>
									
									<?php #----------Generar PDF ?>
									<div class="etapa padless">
										<a class="link accionCotizador" href="#" rel="pdf">
											<img src="views/images/ico_pdf.svg">
											<h3><strong>Generar PDF</strong> para guardar o imprimir</h3>
										</a>
									</div>
									
									<?php #----------Correo Copias ?>
									<div class="etapa padless">
										<a class="link parent" href="#">
											<img src="views/images/ico_compartir.svg" rel="copias-email">
											<h3><strong>Compartir</strong> a otros emails</h3>
										</a>
										<div class="fila desplegable">
											<div class="cols minimargen">
												<div class="col col_2">
													<input type="text" name="copiaEmail1" value="" placeholder="Email 1">
												</div>
												<div class="col col_2">
													<input type="text" name="copiaEmail2" value="" placeholder="Email 2">
												</div>
											</div>
											<div class="fila">
												<a href="#" class="boton trans accionCotizador" rel="copias-email">Enviar</a>
											</div>
										</div>
									</div>
									
									<?php #----------Solicitar Separacion ?>
									<div class="etapa padless">
										<a class="link accionCotizador" href="#" rel="solicitar-separacion">
											<img src="views/images/ico_instrucciones.svg">
											<h3><strong>Solicitar instrucciones de separación</strong> a la sala de ventas</h3>
										</a>
									</div>
									
								</div>
								
								<?php #----------Ponte en contacto ?>
								<div class="fila margen">
									<?php include('views/includes/bloque-ponte-en-contacto.php');?>
								</div>

							</div>
						</div>
					</div>

					
				</form>
				
			</div>
		</div>
	</div>


	<?php #----------Footer ?>
	<?php include('views/includes/footer.php');?>
	
</body>
</html>