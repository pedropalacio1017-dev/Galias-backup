<?php
#----Ruta imagenes
$rutaImgsEmail = URL_BASE.'views/images/email-cotizaciones/';
$rutaLogoProyecto = URL_BASE.'datas/';

#----Set variable
$CuerpoMail = '';

#----Empieza Body
$CuerpoMail .= '
    <body>
    <table width="100%" height="100%" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
    <tr>
    <td align="center" valign="middle">
    <table cellpadding="0" cellspacing="0" width="600">
';

#----Spacers
$CuerpoMail .= '
    <tr>
    <td><img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="300" height="1" /></td>
    <td><img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="300" height="1" /></td>
    <td><img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="1" /></td>
    </tr>
';

#----Encabezado Logo
$CuerpoMail .= '
    <tr>
    <td colspan="2"><img style="display:block;" alt="cotizacion_en_linea_las_galias" src="'.$rutaImgsEmail.'cotizacion_en_linea_las_galias.jpg" width="600" height="190"/></td>
    <td><img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="190" /></td>
    </tr>
';

#----Nombre cliente
$CuerpoMail .= '
    <tr>
    <td colspan="2" align="left" valign="middle" bgcolor="#E01A2C" style="padding-left:35px;">
    <p style="margin:0px;color:#FFFFFF;font-family:Arial, Helvetica, sans-serif;font-size:30px;">Hola <strong>'.$jsonCliente['clienteNombres'].'</strong></p>
    </td>
    <td><img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="75" /></td>
    </tr>
';

#----Frase Cumplir tu sueño
$CuerpoMail .= '
    <tr>
    <td colspan="2"><img style="display:block;" alt="cumplir_tu_sueno" src="'.$rutaImgsEmail.'cumplir_tu_sueno.png" width="600" height="99"/></td>
    <td><img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="99" /></td>
    </tr>
';

#----Margen superior info Proyecto
$CuerpoMail .= '
    <tr>
    <td colspan="2" valign="top" bgcolor="#ffffff">
    <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="30" />
    </td>
    <td><img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="1" /></td>
    </tr>
';

#----Inicio Logo / Info proyecto
$CuerpoMail .= '
    <tr>
        <td align="center" valign="middle" bgcolor="#ffffff">
            <img src="'.$rutaLogoProyecto.$proyecto['proyectoLogo'].'" style="max-width:210px;max-height:160px;" alt="'.$proyecto['proyectoNombre'].'">
        </td>
        <td align="left" valign="middel" bgcolor="#ffffff" style="border-left:solid 1px #CCCCCC;padding-left:20px;">
            <p style="margin:0px;color:#333333;font-family:Arial, Helvetica, sans-serif;font-size:15px;">
                <span style="font-size:20px;color:#666666;"><strong>'.$subproyecto['subproyectoNombre'].'</strong></span>         
';

#----Area Construida
if($proyecto['proyectoAreaConstruida'] != ''){
    $CuerpoMail .= '
        <br>
        <br>ÁREA CONSTRUIDA
        <br>'.$proyecto['proyectoAreaConstruida'];
}

#----Area Privada
if($proyecto['proyectoAreaPrivada'] != ''){
    $CuerpoMail .= '
        <br>
        <br>ÁREA PRIVADA
        <br>'.$proyecto['proyectoAreaPrivada'];
} 

#----Fin Logo / Info proyecto
$CuerpoMail .= '    
                <br>
                <br>DESDE
                <br><span style="font-size:28px;color:#DF192C;"><strong>$'.number_format($subproyecto['subproyectoPrecio']).'</strong></span>
                <br>
                <br>ENTREGAS
                <br><span style="font-size:20px;color:#666666;"><strong>'.$subproyecto['subproyectoEntregas'].'</strong></span>
                <br>'.meses_hoy_hasta_fecha($subproyecto['subproyectoFechaMaxPago']).' meses para pago de cuota inicial
            </p>
        </td>
        <td><img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="211" /></td>
    </tr>
';


#----Margen inferior info proyecto
$CuerpoMail .= '
    <tr>
    <td colspan="2" valign="top" bgcolor="#ffffff">
    <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="30" />
    </td>
    <td><img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="1" /></td>
    </tr>
';

#----Fecha Cotizacion
$CuerpoMail .= '
    <tr>
    <td valign="top" bgcolor="#FFFFFF" style="border-top:solid 1px #CCCCCC;padding-left:30px;padding-right:0px;">
    <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
    <p style="margin:0px;font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#333333;">Fecha Cotización</p>
    <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
    </td>
    <td valign="top" bgcolor="#FFFFFF" style="border-top:solid 1px #CCCCCC;padding-left:0px;padding-right:30px;">
    <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
    <p style="margin:0px;font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#333333;">'.$cotizacion['fechaCotizacion'].'</p>
    <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
    </td>
    <td><img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="1" /></td>
    </tr>
';

#----Asesor
if($cotizacion['ID_usuario'] != 0){
    $CuerpoMail .= '
        <tr>
        <td valign="top" bgcolor="#FFFFFF" style="border-top:solid 1px #CCCCCC;padding-left:30px;padding-right:0px;">
            <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
            <p style="margin:0px;font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#333333;">Asesor</p>
            <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
        </td>
        <td valign="top" bgcolor="#FFFFFF" style="border-top:solid 1px #CCCCCC;padding-left:0px;padding-right:30px;">
            <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
            <p style="margin:0px;font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#333333;">'.$cotizacion['usuarioNombre'].'<br>'.$cotizacion['usuarioEmail'].'<br>Cel: '.$cotizacion['usuarioCelular'].'</p>
            <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
        </td>
        <td><img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="1" /></td>
        </tr>
    ';
}

#----Título Presupuesto estándar
$CuerpoMail .= '
    <tr>
        <td colspan="2" style="border-top:solid 1px #CCCCCC;padding-left:25px;">
            <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="25" />
            <p style="margin:0px;font-family:Arial, Helvetica, sans-serif;font-size:23px;color:#DF192C;">
                <strong>Presupuesto estándar:</strong>
            </p>
            <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="20" />
        </td>
        <td><img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="1" /></td>
    </tr>
';

#----Ingresos Familiares
$CuerpoMail .= '
    <tr>
    <td valign="top" bgcolor="#EEEEEE" style="border-top:solid 1px #CCCCCC;padding-left:30px;padding-right:0px;">
    <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
    <p style="margin:0px;font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#333333;">Ingresos Familiares Mensuales</p>
    <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
    </td>
    <td valign="top" bgcolor="#EEEEEE" style="border-top:solid 1px #CCCCCC;padding-left:0px;padding-right:30px;">
    <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
    <p style="margin:0px;font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#333333;">$'.number_format($jsonCotizacion['valorIngresosFamiliares']).'</p>
    <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
    </td>
    <td><img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="1" /></td>
    </tr>
';

#----Forma de Pago
$CuerpoMail .= '
    <tr>
    <td valign="top" bgcolor="#EEEEEE" style="border-top:solid 1px #CCCCCC;padding-left:30px;padding-right:0px;">
    <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
    <p style="margin:0px;font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#333333;">Forma de Pago</p>
    <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
    </td>
    <td valign="top" bgcolor="#EEEEEE" style="border-top:solid 1px #CCCCCC;padding-left:0px;padding-right:30px;">
    <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
    <p style="margin:0px;font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#333333;">'.mejoroTexto($jsonCotizacion['formaDePago']).'</p>
    <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
    </td>
    <td><img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="1" /></td>
    </tr>
';

#----Tipo de Crédito
if($jsonCotizacion['formaDePago'] == 'Credito'){
    $CuerpoMail .= '
        <tr>
        <td valign="top" bgcolor="#EEEEEE" style="border-top:solid 1px #CCCCCC;padding-left:30px;padding-right:0px;">
        <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
        <p style="margin:0px;font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#333333;">Tipo de Crédito</p>
        <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
        </td>
        <td valign="top" bgcolor="#EEEEEE" style="border-top:solid 1px #CCCCCC;padding-left:0px;padding-right:30px;">
        <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
        <p style="margin:0px;font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#333333;">'.mejoroTexto($jsonCotizacion['tipoDeCredito']).'</p>
        <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
        </td>
        <td><img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="1" /></td>
        </tr>
    ';
}

#----Tipo de Contado
if($jsonCotizacion['formaDePago'] == 'Contado'){
    $CuerpoMail .= '
        <tr>
        <td valign="top" bgcolor="#EEEEEE" style="border-top:solid 1px #CCCCCC;padding-left:30px;padding-right:0px;">
        <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
        <p style="margin:0px;font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#333333;">Tipo de Contado</p>
        <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
        </td>
        <td valign="top" bgcolor="#EEEEEE" style="border-top:solid 1px #CCCCCC;padding-left:0px;padding-right:30px;">
        <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
        <p style="margin:0px;font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#333333;">'.mejoroTexto($jsonCotizacion['tipoDeContado']).'</p>
        <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
        </td>
        <td><img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="1" /></td>
        </tr>
    ';
}

#----Valor Total
$CuerpoMail .= '
    <tr>
    <td valign="top" bgcolor="#ffffff" style="border-top:solid 1px #CCCCCC;padding-left:30px;padding-right:0px;">
    <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
    <p style="margin:0px;font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#333333;">Valor Total</p>
    <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
    </td>
    <td valign="top" bgcolor="#ffffff" style="border-top:solid 1px #CCCCCC;padding-left:0px;padding-right:30px;">
    <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
    <p style="margin:0px;font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#333333;"><strong>$'.number_format($jsonCotizacion['subproyectoPrecio']).'</strong></p>
    <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
    </td>
    <td><img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="1" /></td>
    </tr>
';

#-----Valor Separacion Sobreescrito en ppDetallado
if(
    isset($jsonPlanDePagoDetallado) && $jsonPlanDePagoDetallado != NULL
    && isset($jsonPlanDePagoDetallado['ppd_separacionRecalculado'])
    && $jsonPlanDePagoDetallado['ppd_separacionRecalculado'] != '0'
){
    $CuerpoMail .= '
        <tr>
        <td valign="top" bgcolor="#ffffff" style="border-top:solid 1px #CCCCCC;padding-left:30px;padding-right:0px;">
        <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
        <p style="margin:0px;font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#333333;">Valor Separación</p>
        <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
        </td>
        <td valign="top" bgcolor="#ffffff" style="border-top:solid 1px #CCCCCC;padding-left:0px;padding-right:30px;">
        <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
        <p style="margin:0px;font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#333333;"><strong>$'.number_format($jsonPlanDePagoDetallado['ppd_separacionRecalculado']).'</strong></p>
        <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
        </td>
        <td><img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="1" /></td>
        </tr>
    ';    
}

#----Valor Separación estándar
else{
    $CuerpoMail .= '
        <tr>
        <td valign="top" bgcolor="#ffffff" style="border-top:solid 1px #CCCCCC;padding-left:30px;padding-right:0px;">
        <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
        <p style="margin:0px;font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#333333;">Valor Separación</p>
        <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
        </td>
        <td valign="top" bgcolor="#ffffff" style="border-top:solid 1px #CCCCCC;padding-left:0px;padding-right:30px;">
        <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
        <p style="margin:0px;font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#333333;"><strong>$'.number_format($jsonCotizacion['subproyectoValorSeparacion']).'</strong></p>
        <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
        </td>
        <td><img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="1" /></td>
        </tr>
    ';
}


#-----Valor Aportes (Ahorros, Cesantías y/o Primas) Sobreescrito en ppDetallado
if(
    isset($jsonPlanDePagoDetallado) && $jsonPlanDePagoDetallado != NULL
    && isset($jsonPlanDePagoDetallado['ppd_aportesRecalculado'])
    && $jsonPlanDePagoDetallado['ppd_aportesRecalculado'] != '0'
){
    $CuerpoMail .= '
        <tr>
        <td valign="top" bgcolor="#ffffff" style="border-top:solid 1px #CCCCCC;padding-left:30px;padding-right:0px;">
        <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
        <p style="margin:0px;font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#333333;">Aportes Ahorros, Cesantías y/o Primas</p>
        <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
        </td>
        <td valign="top" bgcolor="#ffffff" style="border-top:solid 1px #CCCCCC;padding-left:0px;padding-right:30px;">
        <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
        <p style="margin:0px;font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#333333;"><strong>$'.number_format($jsonPlanDePagoDetallado['ppd_aportesRecalculado']).'</strong></p>
        <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
        </td>
        <td><img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="1" /></td>
        </tr>
    ';    
}

#----Aportes (Ahorros, Cesantías y/o Primas) estándar
else{
    if($jsonCotizacion['aporteTotal'] != '0'){
        $CuerpoMail .= '
            <tr>
            <td valign="top" bgcolor="#ffffff" style="border-top:solid 1px #CCCCCC;padding-left:30px;padding-right:0px;">
            <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
            <p style="margin:0px;font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#333333;">Aportes Ahorros, Cesantías y/o Primas</p>
            <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
            </td>
            <td valign="top" bgcolor="#ffffff" style="border-top:solid 1px #CCCCCC;padding-left:0px;padding-right:30px;">
            <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
            <p style="margin:0px;font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#333333;"><strong>$'.number_format($jsonCotizacion['aporteTotal']).'</strong></p>
            <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
            </td>
            <td><img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="1" /></td>
            </tr>
        ';
    }
}
    

#-----Subsidio VIS Sobreescrito en ppDetallado
if(
    isset($jsonPlanDePagoDetallado) && $jsonPlanDePagoDetallado != NULL
    && isset($jsonPlanDePagoDetallado['ppd_subsidioRecalculado'])
    && $jsonPlanDePagoDetallado['ppd_subsidioRecalculado'] != '0'
){
    $CuerpoMail .= '
        <tr>
        <td valign="top" bgcolor="#ffffff" style="border-top:solid 1px #CCCCCC;padding-left:30px;padding-right:0px;">
        <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
        <p style="margin:0px;font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#333333;">Subsidio VIS</p>
        <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
        </td>
        <td valign="top" bgcolor="#ffffff" style="border-top:solid 1px #CCCCCC;padding-left:0px;padding-right:30px;">
        <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
        <p style="margin:0px;font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#333333;"><strong>$'.number_format($jsonPlanDePagoDetallado['ppd_subsidioRecalculado']).'</strong></p>
        <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
        </td>
        <td><img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="1" /></td>
        </tr>
    ';   
}

#----Subsidio estándar
else{
    if($jsonCotizacion['valorSubsidio'] != '0'){
        $CuerpoMail .= '
            <tr>
            <td valign="top" bgcolor="#ffffff" style="border-top:solid 1px #CCCCCC;padding-left:30px;padding-right:0px;">
            <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
            <p style="margin:0px;font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#333333;">Subsidio VIS</p>
            <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
            </td>
            <td valign="top" bgcolor="#ffffff" style="border-top:solid 1px #CCCCCC;padding-left:0px;padding-right:30px;">
            <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
            <p style="margin:0px;font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#333333;"><strong>$'.number_format($jsonCotizacion['valorSubsidio']).'</strong></p>
            <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
            </td>
            <td><img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="1" /></td>
            </tr>
        ';
    }

}


#-----CUOTA INICIAL Sobreescrito en ppDetallado
if(
    isset($jsonPlanDePagoDetallado) && $jsonPlanDePagoDetallado != NULL
    && isset($jsonPlanDePagoDetallado['ppd_cuotaInicialRecalculado'])
    && $jsonPlanDePagoDetallado['ppd_cuotaInicialRecalculado'] != '0'
){
    $CuerpoMail .= '
        <tr>
            <td valign="top" bgcolor="#ffffff" style="border-top:solid 1px #CCCCCC;padding-left:30px;padding-right:0px;">
                <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
                <p style="margin:0px;font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#333333;">Cuota Inicial</p>
                <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
            </td>
            <td valign="top" bgcolor="#ffffff" style="border-top:solid 1px #CCCCCC;padding-left:0px;padding-right:30px;">
                <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
                <p style="margin:0px;font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#333333;">
                <strong>$'.number_format($jsonPlanDePagoDetallado['ppd_cuotaInicialRecalculado']).'</strong>
                </p>
                <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
            </td>
            <td><img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="1" /></td>
        </tr>
    ';
}

#----Cuota Inicial estándar
else{
    $CuerpoMail .= '
        <tr>
        <td valign="top" bgcolor="#ffffff" style="border-top:solid 1px #CCCCCC;padding-left:30px;padding-right:0px;">
        <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
        <p style="margin:0px;font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#333333;">Cuota Inicial</p>
        <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
        </td>
        <td valign="top" bgcolor="#ffffff" style="border-top:solid 1px #CCCCCC;padding-left:0px;padding-right:30px;">
        <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
        <p style="margin:0px;font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#333333;">
        <strong>$'.number_format($jsonCotizacion['valorCuotaInicial']).'</strong>
        <br>
        <span style="font-size:14px;color:#666666;">'.$jsonCotizacion['mesesCuotaInicial'].' meses
        <br>Cuota mensual: $'.number_format($jsonCotizacion['valorCuotaInicialMes']);
    if($jsonCotizacion['valorCuotaFinal40'] != '0'){
        $CuerpoMail .= '<br>Cuota final 40%: $'.number_format($jsonCotizacion['valorCuotaFinal40']);
    }
    $CuerpoMail .= '</span>
        </p>
        <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
        </td>
        <td><img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="1" /></td>
        </tr>
    ';
}


#-----FINANCIACION Sobreescrito en ppDetallado
if(
    isset($jsonPlanDePagoDetallado) && $jsonPlanDePagoDetallado != NULL
    && isset($jsonPlanDePagoDetallado['ppd_financiadoRecalculado'])
    && $jsonPlanDePagoDetallado['ppd_financiadoRecalculado'] != '0'
){
    $CuerpoMail .= '
        <tr>
            <td valign="top" bgcolor="#ffffff" style="border-top:solid 1px #CCCCCC;padding-left:30px;padding-right:0px;">
                <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
                <p style="margin:0px;font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#333333;">Financiado</p>
                <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
            </td>
            <td valign="top" bgcolor="#ffffff" style="border-top:solid 1px #CCCCCC;padding-left:0px;padding-right:30px;">
                <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
                <p style="margin:0px;font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#333333;">
                <strong>$'.number_format($jsonPlanDePagoDetallado['ppd_financiadoRecalculado']).'</strong>
                </p>
                <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
            </td>
            <td><img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="1" /></td>
        </tr>
    ';    
}


#----Financiado estándar
else{
    if($jsonCotizacion['valorFinanciado'] != '0'){
        $CuerpoMail .= '
            <tr>
            <td valign="top" bgcolor="#ffffff" style="border-top:solid 1px #CCCCCC;padding-left:30px;padding-right:0px;">
            <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
            <p style="margin:0px;font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#333333;">Financiado</p>
            <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
            </td>
            <td valign="top" bgcolor="#ffffff" style="border-top:solid 1px #CCCCCC;padding-left:0px;padding-right:30px;">
            <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
            <p style="margin:0px;font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#333333;">
            <strong>$'.number_format($jsonCotizacion['valorFinanciado']).'</strong>
            <br>
            <span style="font-size:14px;color:#666666;">Crédito UVR, 30 años
            <br>Cuota mensual aprox: $'.number_format($jsonCotizacion['valorCuotaFinanciadoMes']).'</span>
            </p>
            <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
            </td>
            <td><img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="1" /></td>
            </tr>
        ';
    }
}



#----Gastos escrituración
if(isset($jsonCotizacion['valorGastosEscrituracion']) && $jsonCotizacion['valorGastosEscrituracion'] != '0'){
    $CuerpoMail .= '
        <tr>
            <td valign="top" bgcolor="#EEEEEE" style="border-top:solid 1px #CCCCCC;padding-left:30px;padding-right:0px;">
                <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
                <p style="margin:0px;font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#333333;">Gastos Escrituración</p>
                <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
            </td>
            <td valign="top" bgcolor="#EEEEEE" style="border-top:solid 1px #CCCCCC;padding-left:0px;padding-right:30px;">
                <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
                <p style="margin:0px;font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#333333;"><strong>$'.number_format($jsonCotizacion['valorGastosEscrituracion']).'</strong></p>
                <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="12" />
            </td>
            <td><img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="1" /></td>
        </tr>
    ';
}

#-----Observaciones
if(isset($jsonCotizacion['observaciones']) && $jsonCotizacion['observaciones'] != ''){
    $CuerpoMail .= '
        <tr>
            <td colspan="2" valign="top" bgcolor="#FFFFFF" style="padding-left:30px;padding-right:30px;">            
                <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="20" />            
                <p style="margin:0px;font-family:Arial, Helvetica, sans-serif;font-size:14px;color:#333333;">
                    <strong>OBSERVACIONES:</strong>
                    <br>'.$jsonCotizacion['observaciones'].'
                </p>            
                <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="30" />
            </td>
            <td><img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="1" /></td>
        </tr>       
    ';
}

#----Plan de Pagos Detallado
if(isset($jsonPlanDePagoDetallado) && $jsonPlanDePagoDetallado != NULL){

    #----Titulo Plan de pagos detallado
    if($jsonPlanDePagoDetallado != NULL){
        $CuerpoMail .= '
            <tr>
                <td colspan="2" style="border-top:solid 1px #CCCCCC;padding-left:25px;">
                    <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="25" />
                    <p style="margin:0px;font-family:Arial, Helvetica, sans-serif;font-size:23px;color:#DF192C;">
                        <strong>Propuesta Plan de Pagos:</strong>
                    </p>
                    <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="20" />
                </td>
                <td><img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="1" /></td>
            </tr>
        ';
    }

    #----Plan de Pago Detallado: inicia tabla
    if($jsonPlanDePagoDetallado != NULL){
        $CuerpoMail .= '
            <tr>
            <td colspan="2" style="font-family:Arial, Helvetica, sans-serif;font-size:16px;text-align:left;font-size:14px;background-color:#EFEFEF;">
            <table width="100%" cellspacing="0" cellpadding="0" align="left">
        ';
    }

    #-----V.1. (antes 2023-06) - Sin Gastos Escrituracion
    if(isset($jsonPlanDePagoDetallado['pppValorTotalGalias'])){

        #----Plan de Pago Detallado: Encabezado
        if($jsonPlanDePagoDetallado != NULL){
            $CuerpoMail .= '
                <tr>
                    <th width="25%" style="border-top:solid 1px #CCCCCC;padding:10px 10px 10px 25px;">Concepto</th>
                    <th width="25%" style="border-top:solid 1px #CCCCCC;padding:10px 10px 10px 25px;">Fecha</th>
                    <th width="25%" style="border-top:solid 1px #CCCCCC;padding:10px 10px 10px 25px;">Valor Galias</th>
                    <th width="25%" style="border-top:solid 1px #CCCCCC;padding:10px 10px 10px 25px;">Propuesta Cliente</th>
                </tr>
            ';
        }

        #----Plan de Pago Detallado: Loop filas
        if($jsonPlanDePagoDetallado != NULL){
            foreach($jsonPlanDePagoDetallado['conceptos'] as $concepto){
                $CuerpoMail .= '
                    <tr>
                        <td width="25%" style="border-top:solid 1px #CCCCCC;padding:10px 10px 10px 25px;">'.$concepto['ppd_conceptoNombre'].'</td>
                        <td width="25%" style="border-top:solid 1px #CCCCCC;padding:10px 10px 10px 25px;">'.$concepto['ppd_conceptoFecha'].'</td>
                        <td width="25%" style="border-top:solid 1px #CCCCCC;padding:10px 10px 10px 25px;">$'.number_format($concepto['ppd_conceptoValorGalias']).'</td>
                        <td width="25%" style="border-top:solid 1px #CCCCCC;padding:10px 10px 10px 25px;">$'.number_format($concepto['ppd_conceptoValor']).'</td>
                    </tr>
                ';
            }
        }

        #----Plan de Pago Detallado: Totales
        if($jsonPlanDePagoDetallado != NULL){    
            $CuerpoMail .= '
                <tr>
                    <td width="25%" style="border-top:solid 1px #CCCCCC;padding:10px 10px 10px 25px;"><strong>TOTAL</strong></td>
                    <td width="25%" style="border-top:solid 1px #CCCCCC;padding:10px 10px 10px 25px;"></td>
                    <td width="25%" style="border-top:solid 1px #CCCCCC;padding:10px 10px 10px 25px;"><strong>$'.number_format($jsonPlanDePagoDetallado['pppValorTotalGalias']).'</strong></td>
                    <td width="25%" style="border-top:solid 1px #CCCCCC;padding:10px 10px 10px 25px;"><strong>$'.number_format($jsonPlanDePagoDetallado['ppd_valorTotalCliente']).'</strong></td>
                </tr>
            ';   
        }

        #----Plan de Pago Detallado: Diferencia
        if($jsonPlanDePagoDetallado != NULL){    
            $CuerpoMail .= '
                <tr>
                    <td width="25%" style="border-top:solid 1px #CCCCCC;padding:10px 10px 10px 25px;">Diferencia</td>
                    <td width="25%" style="border-top:solid 1px #CCCCCC;padding:10px 10px 10px 25px;"></td>
                    <td width="25%" style="border-top:solid 1px #CCCCCC;padding:10px 10px 10px 25px;"></td>
                    <td width="25%" style="border-top:solid 1px #CCCCCC;padding:10px 10px 10px 25px;">$'.number_format($jsonPlanDePagoDetallado['ppd_diferenciaVsGalias']).'</td>
                </tr>
            ';   
        }

    }

    #-----V.2. (a partir de 2023-06) - Con Gastos Escrituración
    if(!isset($jsonPlanDePagoDetallado['pppValorTotalGalias'])){

        #-----Encabezado
        $CuerpoMail .= '
            <tr>
                <th width="20%" style="border-top:solid 1px #CCCCCC;padding:10px 10px 10px 25px;">Concepto</th>
                <th width="20%" style="border-top:solid 1px #CCCCCC;padding:10px 10px 10px 25px;">Fecha</th>
                <th width="20%" style="border-top:solid 1px #CCCCCC;padding:10px 10px 10px 25px;">Valor</th>
                <th width="20%" style="border-top:solid 1px #CCCCCC;padding:10px 10px 10px 25px;">Escrituración</th>
                <th width="20%" style="border-top:solid 1px #CCCCCC;padding:10px 10px 10px 25px;">Subtotal</th>
            </tr>
        ';

        #-----Loop Conceptos
        foreach($jsonPlanDePagoDetallado['conceptos'] as $concepto){
            $CuerpoMail .= '
                <tr>
                    <td width="20%" style="border-top:solid 1px #CCCCCC;padding:10px 10px 10px 25px;font-size:14px;">'.$concepto['ppd_conceptoNombre'].'</td>
                    <td width="20%" style="border-top:solid 1px #CCCCCC;padding:10px 10px 10px 25px;font-size:14px;">'.$concepto['ppd_conceptoFecha'].'</td>
                    <td width="20%" style="border-top:solid 1px #CCCCCC;padding:10px 10px 10px 25px;font-size:14px;">$'.number_format($concepto['ppd_conceptoValor']).'</td>
                    <td width="20%" style="border-top:solid 1px #CCCCCC;padding:10px 10px 10px 25px;font-size:14px;">$'.number_format($concepto['ppd_escrituraValor']).'</td>
                    <td width="20%" style="border-top:solid 1px #CCCCCC;padding:10px 10px 10px 25px;font-size:14px;">$'.number_format($concepto['ppd_subtotal']).'</td>
                </tr>
            ';
        }

        #-----Total
        $CuerpoMail .= '
            <tr class="ppp_total">
                <td width="20%" style="border-top:solid 1px #CCCCCC;padding:10px 10px 10px 25px;font-size:14px;"><strong>TOTAL</strong></td>
                <td width="20%" style="border-top:solid 1px #CCCCCC;padding:10px 10px 10px 25px;font-size:14px;">&nbsp;</td>
                <td width="20%" style="border-top:solid 1px #CCCCCC;padding:10px 10px 10px 25px;font-size:14px;"><strong>$'.number_format($jsonCotizacion['subproyectoPrecio']).'</strong></td>
                <td width="20%" style="border-top:solid 1px #CCCCCC;padding:10px 10px 10px 25px;font-size:14px;"><strong>$'.number_format($jsonCotizacion['valorGastosEscrituracion']).'</strong></td>
                <td width="20%" style="border-top:solid 1px #CCCCCC;padding:10px 10px 10px 25px;font-size:14px;"><strong>$'.number_format($jsonCotizacion['subproyectoPrecio'] + $jsonCotizacion['valorGastosEscrituracion']).'</strong></td>
            </tr>
        ';

    }


    #----Plan de Pago Detallado: termina tabla
    if($jsonPlanDePagoDetallado != NULL){
        $CuerpoMail .= '
            </table>
            <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="5" />
            </td>
            <td><img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="1" /></td>
            </tr>
        ';
    }

}

#----Imagen Recuerda...
$CuerpoMail .= '
    <tr>
        <td colspan="2" style="border-top:solid 1px #CCCCCC;">
            <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="10" />
            <img style="display:block;" alt="recuerda" src="'.$rutaImgsEmail.'recuerda.png" width="600" height="201"/>
        </td>
        <td><img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="1" /></td>
    </tr>
';

#----Inicia Tabla Botones Links
if($proyecto['proyectoLinkWeb'] != '' || $proyecto['proyectoLinkBrochure'] != '' || $proyecto['proyectoLinkWaze'] != '' || $proyecto['proyectoLinkDocumentosFirma'] != ''){
    $CuerpoMail .= '
        <tr>
            <td colspan="2">
                <table align="center">
    ';
}

#----Inicia Fila Link Web; Link Brochure
if($proyecto['proyectoLinkWeb'] != '' || $proyecto['proyectoLinkBrochure'] != ''){
    $CuerpoMail .= '
        <tr>    
    ';
}

#----Link Web
if($proyecto['proyectoLinkWeb'] != ''){
    $CuerpoMail .= '        
        <td width="300">
            <a href="'.$proyecto['proyectoLinkWeb'].'" target="_blank" style="border:none;">
                <img style="display:block;border:none;" alt="Visitar sitio web" src="'.$rutaImgsEmail.'boton-visitar-sitio-web.png" width="300" height="272"/>
            </a>
        </td>             
    ';
}

#----Link Brochure
if($proyecto['proyectoLinkBrochure'] != ''){
    $CuerpoMail .= '        
        <td width="300">
            <a href="'.$proyecto['proyectoLinkBrochure'].'" target="_blank" style="border:none;">
                <img style="display:block;border:none;" alt="Descargar Brochure" src="'.$rutaImgsEmail.'boton-descargar-brochure.png" width="300" height="272"/>
            </a>
        </td>     
    ';
}

#----Termina Fila Link Web; Link Brochure
if($proyecto['proyectoLinkWeb'] != '' || $proyecto['proyectoLinkBrochure'] != ''){
    $CuerpoMail .= '
        </tr>      
    ';
}

#----inicia Link Waze; Link Documento
if($proyecto['proyectoLinkWaze'] != '' || $proyecto['proyectoLinkDocumentosFirma'] != ''){
    $CuerpoMail .= '      
        <tr>          
    ';
}

#----Link Waze
if($proyecto['proyectoLinkWaze'] != ''){
    $CuerpoMail .= '      
        <td width="300">
            <a href="'.$proyecto['proyectoLinkWaze'].'" target="_blank" style="border:none;">
                <img style="display:block;border:none;" alt="Llegar con Waze" src="'.$rutaImgsEmail.'boton-llegar-con-waze.png" width="300" height="272"/>
            </a>
        </td>           
    ';
}

#----Link Documento
if($proyecto['proyectoLinkDocumentosFirma'] != ''){
    $CuerpoMail .= '      
        <td width="300">
            <a href="'.$proyecto['proyectoLinkDocumentosFirma'].'" target="_blank" style="border:none;">
                <img style="display:block;border:none;" alt="Documentos para firma de escritura" src="'.$rutaImgsEmail.'boton-documentos-firma.png" width="300" height="272"/>
            </a>
        </td>             
    ';
}

#----Termina Link Waze; Link Documento
if($proyecto['proyectoLinkWaze'] != '' || $proyecto['proyectoLinkDocumentosFirma'] != ''){
    $CuerpoMail .= '      
        </tr>                
    ';
}

#----Termina Tabla Botones Links
if($proyecto['proyectoLinkWeb'] != '' || $proyecto['proyectoLinkBrochure'] != '' || $proyecto['proyectoLinkWaze'] != '' || $proyecto['proyectoLinkDocumentosFirma'] != ''){
    $CuerpoMail .= '
                </table>                
            </td>
            <td><img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="1" /></td>
        </tr>
    ';
}

#----Imagen Mapa Ubicación
if($proyecto['proyectoImgMapa'] != ''){
    $CuerpoMail .= '
        <tr>
            <td colspan="2">
                <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="40" />
                <img style="display:block;" alt="Mapa Ubicacion" src="'.$rutaLogoProyecto.$proyecto['proyectoImgMapa'].'" width="600"/>
                <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="20" />
            </td>
            <td><img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="1" /></td>
        </tr>
    ';
}

#----Imagen Más del proyecto
if($proyecto['proyectoImgFortalezas'] != ''){
    $CuerpoMail .= '
        <tr>
            <td colspan="2">
                <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="20" />
                <img style="display:block;" alt="Más del proyecto" src="'.$rutaLogoProyecto.$proyecto['proyectoImgFortalezas'].'" width="600"/>
                <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="20" />
            </td>
            <td><img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="1" /></td>
        </tr>
    ';
}

#----Notas Legales
$CuerpoMail .= '
    <tr>
    <td colspan="2" valign="top" bgcolor="#ffffff">
    <p style="margin:0px;font-family:Arial, Helvetica, sans-serif;font-size:13px;color:#666666;text-align:justify;line-height:1.2em;">'.nl2br($proyecto['notaLegal']).'</p>
    <img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="25" />
    </td>
    <td><img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="1" /></td>
    </tr>
';

#----Pie
$CuerpoMail .= '
    <tr>
    <td colspan="2"><img style="display:block;" alt="en_las_galias_te_asesoramos" src="'.$rutaImgsEmail.'en_las_galias_te_asesoramos.png" width="600" height="145"/></td>
    <td><img style="display:block;" src="'.$rutaImgsEmail.'spacer.gif" width="1" height="145" /></td>
    </tr>
';

#----Termina Body
$CuerpoMail .= '
    </table>
    </td>
    </tr>
    </table>
    </body>
';

?>       