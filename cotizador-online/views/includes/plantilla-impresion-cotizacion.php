<?php
#-----RUTAS ABSOLUTAS IMAGENES
$rutaImgsEmail = URL_BASE.'views/images/email-cotizaciones/';
$rutaImagenes = URL_BASE.RUTA_UPLOADS;

#-----Html
$htmlImprime .= '<!DOCTYPE html><html lang="es">';

#-----Head
$htmlImprime .= '
    <head> 
    <meta charset="utf-8"/> 
    <style>
        *{
            margin: 0pt;
            padding: 0pt;
            line-height: 1em;
            border-collapse: collapse;
            font-family: Arial, Helvetica, sans-serif;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }
        .container{
            width: 612pt; 
            height: 792pt; 
            margin:auto; 
            background-color:#FFFFFF;
        }
        .page_break {
            page-break-before: always;
        }
        .page_break_avoid {
            page-break-before: avoid;
        }
        body{
            background-color:#EEEEEE;
        }
        table,
        tr{
            width:100%;
        }
        h1,
        p{
            display:inline-block; 
            color:#333333;
        }
        h1{
            font-size: 15pt;
        }
        p{
            font-size: 11pt;
        }
        table.valores_cotizacion td{
            font-size: 10pt;
        }
        table.plan_pagos{
            page-break-inside:auto;
            padding-bottom: 10pt;
            padding-top: 10pt;
        }
        table.plan_pagos tr:nth-child(even) td {
            border-top: solid 1px #dddddd;
            background-color: #eeeeee;
        }
        table.plan_pagos tr.ppp_total td {
            color: #ffffff;
            background-color: #333333;
        }
        table.plan_pagos td {
            font-size: 10pt;
            padding: 5px;
        }
    </style>
    </head>
';

#-----Body
$htmlImprime .= '<body>';

#-----------------------------------
# Hoja 1 (Resumen Cotizacion)
#-----------------------------------

#-----Inicia Container
$htmlImprime .= '<div class="container">';

#----- # Cotizacion y fecha
$htmlImprime .= '
    <table>
        <tr>
            <td width="100%" align="left" valign="middle" style="padding:20pt 20pt 15pt 20pt;border-bottom:solid 1px #CCCCCC;">
                <p><strong>Cotización #'.$cotizacion['ID_cotizacion'].'</strong> / Fecha: '.$cotizacion['fechaCotizacion'].'</p>
            </td>
        </tr>
    </table>
';

#-----Inicia Table Logo Galias, info Proyecto, logo proyecto
$htmlImprime .= '<table><tr>';

#-----Logo Galias
$htmlImprime .= '    
    <td width="35%" align="left" valign="middle" style="padding:30pt 40pt 30pt 20pt;">
        <img src="'.$rutaImagenes.'logo-las-galias.png" width="210pt"/>
    </td>            
';

#-----Inicia Info Proyecto: Nombre Torre
$htmlImprime .= '
    <td width="40%" align="left" valign="middle" style="padding:30pt 20pt;">
        <h1><strong>'.$subproyecto['subproyectoNombre'].'</strong></h1>
        <br><p style="font-size:9pt;">
';

#-----info proyecto: Area Construida
if($proyecto['proyectoAreaConstruida'] != ''){
    $htmlImprime .= '
        Área Construida: '.$proyecto['proyectoAreaConstruida'].'
        <br>
    ';
}

#-----info proyecto: Area Privada
if($proyecto['proyectoAreaPrivada'] != ''){
    $htmlImprime .= '
        Área Privada: '.$proyecto['proyectoAreaPrivada'].'
        <br>
    ';
}

#-----Termina Info Proyecto: Entregas
$htmlImprime .= '
        <br>Entregas: '.$subproyecto['subproyectoEntregas'].'
        <br>'.meses_hoy_hasta_fecha($subproyecto['subproyectoFechaMaxPago']).' meses para pago de cuota inicial
        </p>
    </td>
';

#-----Logo proyecto
$htmlImprime .= '
    <td width="25%" align="center" valign="middle" style="padding:30pt 40pt 30pt 20pt;">
        <img src="'.$rutaImagenes.$proyecto['proyectoLogo'].'" style="max-width:130pt;max-height:65pt;" />
    </td>      
';

#-----Termina Table Logo Galias, info Proyecto, logo proyecto
$htmlImprime .= '</tr></table>';

#-----Precio Total
$htmlImprime .= '
    <table>
        <tr>
            <td width="100%" align="left" valign="middle" bgcolor="#E01A2C" style="padding:8pt 20pt;">
                <h1 style="color:#FFFFFF;"><strong>Precio total $'.number_format($jsonCotizacion['subproyectoPrecio']).'</strong></h1>
            </td>
        </tr>
    </table>
';

#-----Inicia Bloque cotizacion
$htmlImprime .= '<table> <tr>';

#-----Inicia TD Datos Cliente, Datos Asesor, Imagen Recuerda
$htmlImprime .= '        
    <td width="50%" align="left" valign="top" style="padding:30pt 20pt 50pt 20pt;">
    <table>
';

#-----Datos Cliente
$htmlImprime .= '   
    <tr>
        <td width="100%" align="left" valign="top">
            <h1>Cliente:</h1><br>                    
            <p style="line-height:1.2em;">'.$jsonCliente['clienteNombres'].' '.$jsonCliente['clienteApellidos'].'
            <br>Doc: '.$jsonCliente['clienteDocumento'].' / Cel: '.$jsonCliente['clienteCelular'].'
            <br>'.$jsonCliente['clienteEmail'].'
            </p> 
        </td>
    </tr>
';

#-----Datos Asesor
if($cotizacion['ID_usuario'] != 0){
    $htmlImprime .= '   
        <tr>
            <td width="100%" align="left" valign="top" style="padding-top:10pt;">
                <h1>Asesor:</h1><br>                    
                <p style="line-height:1.2em;">'.$cotizacion['usuarioNombre'].'
                <br>'.$cotizacion['usuarioEmail'].'
                <br>Cel: '.$cotizacion['usuarioCelular'].'
                </p> 
            </td>
        </tr>
    ';
}

#-----Imagen Recuerda
$htmlImprime .= '        
    <tr>
        <td width="100%" align="left" valign="top" style="padding-top:35pt;">
            <img src="'.$rutaImagenes.'recuerda.png" width="210pt"/>    
        </td>
    </tr>        
';

#-----Termina TD Datos Cliente, Datos Asesor, Imagen Recuerda
$htmlImprime .= '        
    </table>
    </td>
';

#-----Abre TD Valores Cotizacion, título "Presupuesto estándar"
$htmlImprime .= '
    <td width="50%" align="left" valign="top" style="padding:30pt 20pt 15pt 20pt;">
    <h1 style="margin-bottom:5pt;">Presupuesto estándar:</h1><br>
    <table style="border:solid 1px #CCC;border-top:none;" class="valores_cotizacion">
';

#-----Ingresos Familiares
$htmlImprime .= '
    <tr>
        <td width="50%" align="left" valign="middle" style="padding:7pt;border-top:solid 1px #CCC;" bgcolor="#EEEEEE">
        Ingresos Familiares Mensuales
        </td>
        <td width="50%" align="left" valign="middle" style="padding:7pt;border-top:solid 1px #CCC;" bgcolor="#EEEEEE">
        $'.number_format($jsonCotizacion['valorIngresosFamiliares']).'
        </td>
    </tr>
';

#-----Forma de Pago
$htmlImprime .= '
    <tr>
        <td width="50%" align="left" valign="middle" style="padding:7pt;border-top:solid 1px #CCC;" bgcolor="#EEEEEE">
        Forma de Pago
        </td>
        <td width="50%" align="left" valign="middle" style="padding:7pt;border-top:solid 1px #CCC;" bgcolor="#EEEEEE">
        '.mejoroTexto($jsonCotizacion['formaDePago']).'
        </td>
    </tr>
';

#----Tipo de Crédito
if($jsonCotizacion['formaDePago'] == 'Credito'){
    $htmlImprime .= '
        <tr>
            <td width="50%" align="left" valign="middle" style="padding:7pt;border-top:solid 1px #CCC;" bgcolor="#EEEEEE">
            Tipo de Crédito
            </td>
            <td width="50%" align="left" valign="middle" style="padding:7pt;border-top:solid 1px #CCC;" bgcolor="#EEEEEE">
            '.mejoroTexto($jsonCotizacion['tipoDeCredito']).'
            </td>
        </tr>
    ';
}

#----Tipo de Contado
if($jsonCotizacion['formaDePago'] == 'Contado'){
    $htmlImprime .= '
        <tr>
            <td width="50%" align="left" valign="middle" style="padding:7pt;border-top:solid 1px #CCC;" bgcolor="#EEEEEE">
            Tipo de Contado
            </td>
            <td width="50%" align="left" valign="middle" style="padding:7pt;border-top:solid 1px #CCC;" bgcolor="#EEEEEE">
            '.mejoroTexto($jsonCotizacion['tipoDeContado']).'
            </td>
        </tr>
    ';
}

#-----Valor Separacion Sobreescrito en ppDetallado
if(
    isset($jsonPlanDePagoDetallado) && $jsonPlanDePagoDetallado != NULL
    && isset($jsonPlanDePagoDetallado['ppd_separacionRecalculado'])
    && $jsonPlanDePagoDetallado['ppd_separacionRecalculado'] != '0'
){
    $htmlImprime .= '
        <tr>
            <td width="50%" align="left" valign="middle" style="padding:7pt;border-top:solid 1px #CCC;" bgcolor="#FFFFFF">
            <strong>Separación</strong>
            </td>
            <td width="50%" align="left" valign="middle" style="padding:7pt;border-top:solid 1px #CCC;" bgcolor="#FFFFFF">
            $'.number_format($jsonPlanDePagoDetallado['ppd_separacionRecalculado']).'
            </td>
        </tr>
    ';    
}

#-----Valor Separacion estándar
else{
    $htmlImprime .= '
        <tr>
            <td width="50%" align="left" valign="middle" style="padding:7pt;border-top:solid 1px #CCC;" bgcolor="#FFFFFF">
            <strong>Separación</strong>
            </td>
            <td width="50%" align="left" valign="middle" style="padding:7pt;border-top:solid 1px #CCC;" bgcolor="#FFFFFF">
            $'.number_format($jsonCotizacion['subproyectoValorSeparacion']).'
            </td>
        </tr>
    ';
}

#-----Valor Aportes (Ahorros, Cesantías y/o Primas) Sobreescrito en ppDetallado
if(
    isset($jsonPlanDePagoDetallado) && $jsonPlanDePagoDetallado != NULL
    && isset($jsonPlanDePagoDetallado['ppd_aportesRecalculado'])
    && $jsonPlanDePagoDetallado['ppd_aportesRecalculado'] != '0'
){
    $htmlImprime .= '
        <tr>
            <td width="50%" align="left" valign="middle" style="padding:7pt;border-top:solid 1px #CCC;" bgcolor="#FFFFFF">
            <strong>Aportes Ahorros, Cesantías y/o Primas</strong>
            </td>
            <td width="50%" align="left" valign="middle" style="padding:7pt;border-top:solid 1px #CCC;" bgcolor="#FFFFFF">
            $'.number_format($jsonPlanDePagoDetallado['ppd_aportesRecalculado']).'
            </td>
        </tr>
    ';    
}

#-----Aportes (Ahorros, Cesantías y/o Primas) estándar
else{
    if($jsonCotizacion['aporteTotal'] != '0'){
        $htmlImprime .= '
            <tr>
                <td width="50%" align="left" valign="middle" style="padding:7pt;border-top:solid 1px #CCC;" bgcolor="#FFFFFF">
                <strong>Aportes Ahorros, Cesantías y/o Primas</strong>
                </td>
                <td width="50%" align="left" valign="middle" style="padding:7pt;border-top:solid 1px #CCC;" bgcolor="#FFFFFF">
                $'.number_format($jsonCotizacion['aporteTotal']).'
                </td>
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
    $htmlImprime .= '
        <tr>
            <td width="50%" align="left" valign="middle" style="padding:7pt;border-top:solid 1px #CCC;" bgcolor="#FFFFFF">
            <strong>Subsidio VIS</strong>
            </td>
            <td width="50%" align="left" valign="middle" style="padding:7pt;border-top:solid 1px #CCC;" bgcolor="#FFFFFF">
            $'.number_format($jsonPlanDePagoDetallado['ppd_subsidioRecalculado']).'
            </td>
        </tr>
    ';
}

#-----Subsidio VIS estándar
else{
    if($jsonCotizacion['valorSubsidio'] != '0'){
        $htmlImprime .= '
            <tr>
                <td width="50%" align="left" valign="middle" style="padding:7pt;border-top:solid 1px #CCC;" bgcolor="#FFFFFF">
                <strong>Subsidio VIS</strong>
                </td>
                <td width="50%" align="left" valign="middle" style="padding:7pt;border-top:solid 1px #CCC;" bgcolor="#FFFFFF">
                $'.number_format($jsonCotizacion['valorSubsidio']).'
                </td>
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
    $htmlImprime .= '
        <tr>
            <td width="50%" align="left" valign="middle" style="padding:7pt;border-top:solid 1px #CCC;" bgcolor="#FFFFFF">
            <strong>Cuota Inicial</strong>
            </td>
            <td width="50%" align="left" valign="middle" style="padding:7pt;border-top:solid 1px #CCC;" bgcolor="#FFFFFF">
            $'.number_format($jsonPlanDePagoDetallado['ppd_cuotaInicialRecalculado']).'
            </td>
        </tr>
    ';
}


#-----CUOTA INICIAL estándar
else{

    #-----Valor Cuota Inicial
    $htmlImprime .= '
        <tr>
            <td width="50%" align="left" valign="middle" style="padding:7pt;border-top:solid 1px #CCC;" bgcolor="#FFFFFF">
            <strong>Cuota Inicial</strong>
            </td>
            <td width="50%" align="left" valign="middle" style="padding:7pt;border-top:solid 1px #CCC;" bgcolor="#FFFFFF">
            $'.number_format($jsonCotizacion['valorCuotaInicial']).'
            </td>
        </tr>
    ';

    #-----Meses Cuota Inicial
    $htmlImprime .= '
        <tr>
            <td width="50%" align="left" valign="middle" style="padding:2pt 7pt 2pt 30pt;font-size:8pt;" bgcolor="#FFFFFF">
            # Meses
            </td>
            <td width="50%" align="left" valign="middle" style="padding:2pt 7pt 2pt 7pt;font-size:8pt;" bgcolor="#FFFFFF">
            '.$jsonCotizacion['mesesCuotaInicial'].'
            </td>
        </tr>
    ';

    #-----Valor mensual Cuota Inicial
    $htmlImprime .= '
        <tr>
            <td width="50%" align="left" valign="middle" style="padding:2pt 7pt 10pt 30pt;font-size:8pt;" bgcolor="#FFFFFF">
            Cuota Mensual
            </td>
            <td width="50%" align="left" valign="middle" style="padding:2pt 7pt 10pt 7pt;font-size:8pt;" bgcolor="#FFFFFF">
            $'.number_format($jsonCotizacion['valorCuotaInicialMes']).'
            </td>
        </tr>
    ';

    #-----Cuota final 40%
    if($jsonCotizacion['valorCuotaFinal40'] != '0'){
        $htmlImprime .= '
            <tr>
                <td width="50%" align="left" valign="middle" style="padding:0pt 7pt 10pt 30pt;font-size:8pt;" bgcolor="#FFFFFF">
                Cuota final 40%
                </td>
                <td width="50%" align="left" valign="middle" style="padding:0pt 7pt 10pt 7pt;font-size:8pt;" bgcolor="#FFFFFF">
                $'.number_format($jsonCotizacion['valorCuotaFinal40']).'
                </td>
            </tr>
        ';
    }

}


#-----FINANCIACION Sobreescrito en ppDetallado
if(
    isset($jsonPlanDePagoDetallado) && $jsonPlanDePagoDetallado != NULL
    && isset($jsonPlanDePagoDetallado['ppd_financiadoRecalculado'])
    && $jsonPlanDePagoDetallado['ppd_financiadoRecalculado'] != '0'
){
    $htmlImprime .= '
        <tr>
            <td width="50%" align="left" valign="top" style="padding:7pt 7pt 4pt 7pt;border-top:solid 1px #CCC;" bgcolor="#FFFFFF">
            <strong>Financiado</strong>
            </td>
            <td width="50%" align="left" valign="top" style="padding:7pt 7pt 4pt 7pt;border-top:solid 1px #CCC;" bgcolor="#FFFFFF">
            $'.number_format($jsonPlanDePagoDetallado['ppd_financiadoRecalculado']).'
            </td>
        </tr>
    ';
}


#-----FINANCIACION estándar
else{
    if($jsonCotizacion['valorFinanciado'] != '0'){

        #-----Valor Financiado
        $htmlImprime .= '
            <tr>
                <td width="50%" align="left" valign="top" style="padding:7pt 7pt 4pt 7pt;border-top:solid 1px #CCC;" bgcolor="#FFFFFF">
                <strong>Financiado</strong>
                </td>
                <td width="50%" align="left" valign="top" style="padding:7pt 7pt 4pt 7pt;border-top:solid 1px #CCC;" bgcolor="#FFFFFF">
                $'.number_format($jsonCotizacion['valorFinanciado']).'
                </td>
            </tr>
        ';

        #-----UVR 30 años
        $htmlImprime .= '
            <tr>
                <td width="50%" align="left" valign="middle" style="padding:2pt 7pt 2pt 30pt;font-size:8pt;" bgcolor="#FFFFFF">
                UVR
                </td>
                <td width="50%" align="left" valign="middle" style="padding:2pt 7pt 2pt 7pt;font-size:8pt;" bgcolor="#FFFFFF">
                30 años
                </td>
            </tr>
        ';

        #-----Valor mensual Aprox
        $htmlImprime .= '
            <tr>
                <td width="50%" align="left" valign="middle" style="padding:2pt 7pt 10pt 30pt;font-size:8pt;" bgcolor="#FFFFFF">
                Cuota mensual aprox:
                </td>
                <td width="50%" align="left" valign="middle" style="padding:2pt 7pt 10pt 7pt;font-size:8pt;" bgcolor="#FFFFFF">
                $'.number_format($jsonCotizacion['valorCuotaFinanciadoMes']).'
                </td>
            </tr>
        ';

    }

}


#-----Gastos escrituración
if(isset($jsonCotizacion['valorGastosEscrituracion']) && $jsonCotizacion['valorGastosEscrituracion'] != '0'){
    $htmlImprime .= '
        <tr>
            <td width="50%" align="left" valign="middle" style="padding:7pt;border-top:solid 1px #CCC;" bgcolor="#EEEEEE">
            <strong>Gastos Escrituración</strong>
            </td>
            <td width="50%" align="left" valign="middle" style="padding:7pt;border-top:solid 1px #CCC;" bgcolor="#EEEEEE">
            $'.number_format($jsonCotizacion['valorGastosEscrituracion']).'
            </td>
        </tr>
    ';
}

#-----Cierra tabla Valores Cotizacion
$htmlImprime .= '</table>';

#-----Observaciones
if(isset($jsonCotizacion['observaciones']) && $jsonCotizacion['observaciones'] != ''){
    $htmlImprime .= '
        <br>    
        <p style="font-size:8pt;text-align:justify;line-height:1.2em;">
            <strong>OBSERVACIONES:</strong>
            <br>'. $jsonCotizacion['observaciones'].'
        </p>        
    ';
}

#-----Termina Bloque cotizacion
$htmlImprime .= '</td> </tr> </table>';

#-----Aviso Legal
/*
$htmlImprime .= '
    <table> 
        <tr> 
            <td width="100%" align="justify" valign="middle" style="padding:5pt 20pt 10pt 20pt;">
                <p style="font-size:7pt;line-height:1.2em;">'.nl2br($proyecto['notaLegal']).'</p>
            </td>
        </tr>
    </table>
';
*/

#-----Container Resumen
$htmlImprime .= '</div>';

#-----------------------------------
# Hoja 2 - Plan de Pagos Detallado
#-----------------------------------

if(isset($jsonPlanDePagoDetallado) && $jsonPlanDePagoDetallado != NULL){

    #-----Container Hoja 2
    $htmlImprime .= '<div class="container page_break">';

    #-----#Cotizacion y fecha
    $htmlImprime .= '
        <table>
            <tr>
                <td width="100%" align="left" valign="middle" style="padding:20pt 20pt 15pt 20pt;border-bottom:solid 1px #CCCCCC;">
                    <p><strong>Cotización #'.$cotizacion['ID_cotizacion'].'</strong> / Fecha: '.$cotizacion['fechaCotizacion'].'</p>
                </td>
            </tr>
        </table>
    ';

    #-----Titulo PPD
    $htmlImprime .= '
        <table class="page_break_avoid" style="margin-bottom:10pt;">
            <tr>
                <td width="100%" align="left" valign="middle" bgcolor="#E01A2C" style="padding:8pt 20pt;">
                    <h1 style="color:#FFFFFF;"><strong>Propuesta Plan de Pagos</strong></h1>
                </td>
            </tr>
        </table>
    ';

    #-----Abre Fila y Tabla
    $htmlImprime .= '
        <table class="page_break_avoid">
        <tr>
        <td style="padding-left:20pt;padding-right:20pt;">
        <table style="border:solid 1px #CCC;" class="plan_pagos">
    ';

    #-----V.1. (antes 2023-06) - Sin Gastos Escrituracion
    if(isset($jsonPlanDePagoDetallado['pppValorTotalGalias'])){

        #-----Encabezado
        $htmlImprime .= '
            <tr>
                <td width="25%"><strong>Concepto</strong></td>
                <td width="25%"><strong>Fecha</strong></td>
                <td width="25%"><strong>Valores Galias</strong></td>
                <td width="25%"><strong>Propuesta Cliente</strong></td>
            </tr>
        ';

        #-----Loop Conceptos
        foreach($jsonPlanDePagoDetallado['conceptos'] as $concepto){
            $htmlImprime .= '
            <tr>
                <td width="25%">'.$concepto['ppd_conceptoNombre'].'</td>
                <td width="25%">'.$concepto['ppd_conceptoFecha'].'</td>
                <td width="25%">$'.number_format($concepto['ppd_conceptoValorGalias']).'</td>
                <td width="25%">$'.number_format($concepto['ppd_conceptoValor']).'</td>
            </tr>
            ';
        }

        #-----Total
        $htmlImprime .= '
            <tr class="ppp_total">
                <td width="25%"><strong>TOTAL</strong></td>
                <td width="25%">&nbsp;</td>
                <td width="25%">$'.number_format($jsonPlanDePagoDetallado['pppValorTotalGalias']).'</td>
                <td width="25%"><strong>$'.number_format($jsonPlanDePagoDetallado['ppd_valorTotalCliente']).'</strong></td>
            </tr>
        ';

        #-----Diferencia
        $htmlImprime .= '
            <tr>
                <td width="25%">&nbsp;</td>
                <td width="25%">&nbsp;</td>
                <td width="25%">Diferencia</td>
                <td width="25%">$'.number_format($jsonPlanDePagoDetallado['ppd_diferenciaVsGalias']).'</td>
            </tr>
        ';

    }

    #-----V.2. (a partir de 2023-06) - Con Gastos Escrituración
    if(!isset($jsonPlanDePagoDetallado['pppValorTotalGalias'])){

        #-----Encabezado
        $htmlImprime .= '
            <tr>
                <td width="20%"><strong>Concepto</strong></td>
                <td width="20%"><strong>Fecha</strong></td>
                <td width="20%"><strong>Valor</strong></td>
                <td width="20%"><strong>Escrituración</strong></td>
                <td width="20%"><strong>Subtotal</strong></td>
            </tr>
        ';

        #-----Loop Conceptos
        foreach($jsonPlanDePagoDetallado['conceptos'] as $concepto){
            $htmlImprime .= '
            <tr>
                <td width="20%">'.$concepto['ppd_conceptoNombre'].'</td>
                <td width="20%">'.$concepto['ppd_conceptoFecha'].'</td>
                <td width="20%">$'.number_format($concepto['ppd_conceptoValor']).'</td>
                <td width="20%">$'.number_format($concepto['ppd_escrituraValor']).'</td>
                <td width="20%">$'.number_format($concepto['ppd_subtotal']).'</td>
            </tr>
            ';
        }

        #-----Total
        $htmlImprime .= '
            <tr class="ppp_total">
                <td width="20%"><strong>TOTAL</strong></td>
                <td width="20%">&nbsp;</td>
                <td width="20%"><strong>$'.number_format($jsonCotizacion['subproyectoPrecio']).'</strong></td>
                <td width="20%"><strong>$'.number_format($jsonCotizacion['valorGastosEscrituracion']).'</strong></td>
                <td width="20%"><strong>$'.number_format($jsonCotizacion['subproyectoPrecio'] + $jsonCotizacion['valorGastosEscrituracion']).'</strong></td>
            </tr>
        ';

    }

    #-----Cierra Fila y Tabla
    $htmlImprime .= '</table> </td></tr></table>';

    #-----Container Hoja 2
    $htmlImprime .= '</div>';

}


#-----------------------------------
# Hoja 3 - Links e Imagenes complementarias
#-----------------------------------

if(
    (isset($proyecto['proyectoLinkWeb']) && $proyecto['proyectoLinkWeb'] != '')
    || (isset($proyecto['proyectoLinkBrochure']) && $proyecto['proyectoLinkBrochure'] != '')
    || (isset($proyecto['proyectoLinkWaze']) && $proyecto['proyectoLinkWaze'] != '')
    || (isset($proyecto['proyectoLinkDocumentosFirma']) && $proyecto['proyectoLinkDocumentosFirma'] != '')
    || (isset($proyecto['proyectoImgMapa']) && $proyecto['proyectoImgMapa'] != '')
    || (isset($proyecto['proyectoImgFortalezas']) && $proyecto['proyectoImgFortalezas'] != '')
){

    #-----BOF Container Hoja 3
    $htmlImprime .= '<div class="container page_break">';

    #-----Imagen Fortalezas proyecto
    if(isset($proyecto['proyectoImgFortalezas']) && $proyecto['proyectoImgFortalezas'] != ''){
        $htmlImprime .= '
            <table>
                <tr>
                    <td width="100%" align="center" valign="middle" style="padding:20pt 20pt 20pt 20pt;">
                        <img src="'.$rutaImagenes.$proyecto['proyectoImgFortalezas'].'" width="700pt"/>                        
                    </td>
                </tr>
            </table>
        ';       
    }


    #-----Inicia tabla Links
    if(
        (isset($proyecto['proyectoLinkWeb']) && $proyecto['proyectoLinkWeb'] != '')
        || (isset($proyecto['proyectoLinkBrochure']) && $proyecto['proyectoLinkBrochure'] != '')
        || (isset($proyecto['proyectoLinkWaze']) && $proyecto['proyectoLinkWaze'] != '')
        || (isset($proyecto['proyectoLinkDocumentosFirma']) && $proyecto['proyectoLinkDocumentosFirma'] != '')
    ){
        $htmlImprime .= '
            <table>
            <tr>
            <td style="padding:30pt;">
            <table>
            <tr>
        '; 
    }

    #-----Link Web
    if(isset($proyecto['proyectoLinkWeb']) && $proyecto['proyectoLinkWeb'] != ''){
        $htmlImprime .= '            
            <td width="25%">
                <a href="'.$proyecto['proyectoLinkWeb'].'" target="_blank" style="border:none;">
                    <img src="'.$rutaImgsEmail.'boton-visitar-sitio-web.png" style="width:70%;height:auto;"/>
                </a>
            </td>               
        ';       
    }

    #-----Link Brochure
    if(isset($proyecto['proyectoLinkBrochure']) && $proyecto['proyectoLinkBrochure'] != ''){
        $htmlImprime .= '            
            <td width="25%">
                <a href="'.$proyecto['proyectoLinkBrochure'].'" target="_blank" style="border:none;">
                    <img src="'.$rutaImgsEmail.'boton-descargar-brochure.png" style="width:70%;height:auto;"/>
                </a>
            </td>               
        ';       
    }

    #-----Link Waze
    if(isset($proyecto['proyectoLinkWaze']) && $proyecto['proyectoLinkWaze'] != ''){
        $htmlImprime .= '            
            <td width="25%">
                <a href="'.$proyecto['proyectoLinkWaze'].'" target="_blank" style="border:none;">
                    <img src="'.$rutaImgsEmail.'boton-llegar-con-waze.png" style="width:70%;height:auto;"/>
                </a>
            </td>               
        ';       
    }


    #-----Link Documentos Firma
    if(isset($proyecto['proyectoLinkDocumentosFirma']) && $proyecto['proyectoLinkDocumentosFirma'] != ''){
        $htmlImprime .= '            
            <td width="25%">
                <a href="'.$proyecto['proyectoLinkDocumentosFirma'].'" target="_blank" style="border:none;">
                    <img src="'.$rutaImgsEmail.'boton-documentos-firma.png" style="width:70%;height:auto;"/>
                </a>
            </td>               
        ';       
    }


    #-----Termina tabla Links
    if(
        (isset($proyecto['proyectoLinkWeb']) && $proyecto['proyectoLinkWeb'] != '')
        || (isset($proyecto['proyectoLinkBrochure']) && $proyecto['proyectoLinkBrochure'] != '')
        || (isset($proyecto['proyectoLinkWaze']) && $proyecto['proyectoLinkWaze'] != '')
        || (isset($proyecto['proyectoLinkDocumentosFirma']) && $proyecto['proyectoLinkDocumentosFirma'] != '')
    ){
        $htmlImprime .= '
            </tr>
            </table>
            </td>
            </tr>
            </table>
        '; 
    }


    #-----Imagen Mapa
    if(isset($proyecto['proyectoImgMapa']) && $proyecto['proyectoImgMapa'] != ''){
        $htmlImprime .= '
            <table>
                <tr>
                    <td width="100%" align="center" valign="middle" style="padding:20pt 20pt 0 20pt;">
                        <img src="'.$rutaImagenes.$proyecto['proyectoImgMapa'].'" width="700pt"/>                        
                    </td>
                </tr>
            </table>
        ';       
    }

    #-----EOF Container Hoja 3
    $htmlImprime .= '</div>';

}


#-----------------------------------
# Hoja 4 - Notas legales
#-----------------------------------

if($proyecto['notaLegal'] != ''){

    #-----Container Hoja 4
    $htmlImprime .= '<div class="container page_break">';

    #-----#Cotizacion y fecha
    $htmlImprime .= '
        <table>
            <tr>
                <td width="100%" align="left" valign="middle" style="padding:20pt 20pt 15pt 20pt;border-bottom:solid 1px #CCCCCC;">
                    <p><strong>Cotización #'.$cotizacion['ID_cotizacion'].'</strong> / Fecha: '.$cotizacion['fechaCotizacion'].'</p>
                </td>
            </tr>
        </table>
    ';

    #-----Titulo Observaciones legales
    $htmlImprime .= '
        <table class="page_break_avoid" style="margin-bottom:10pt;">
            <tr>
                <td width="100%" align="left" valign="middle" bgcolor="#E01A2C" style="padding:8pt 20pt;">
                    <h1 style="color:#FFFFFF;"><strong>Observaciones</strong></h1>
                </td>
            </tr>
        </table>
    ';

    #-----Aviso Legal
    $htmlImprime .= '
        <table> 
            <tr> 
                <td width="100%" align="justify" valign="middle" style="padding:5pt 20pt 10pt 20pt;">
                    <p style="font-size:7pt;line-height:1.2em;">'.nl2br($proyecto['notaLegal']).'</p>
                </td>
            </tr>
        </table>
    ';

    #-----Container Hoja 4
    $htmlImprime .= '</div>';

}


#-----------------------------------
# Termina Body HTML
#-----------------------------------

#-----Termina Body
$htmlImprime .= '</body>';

#-----Termina Html
$htmlImprime .= '</html>';

?>