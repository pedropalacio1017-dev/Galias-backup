<?php
#------Seo URL
function seo_url($cadena){
	$cadena= utf8_decode($cadena);
    $cadena = str_replace(' ', '-', $cadena);
	$cadena = str_replace('?', '', $cadena);
	$cadena = str_replace('+', '', $cadena);
	$cadena = str_replace('(', '-', $cadena);
	$cadena = str_replace(')', '-', $cadena);
	$cadena = str_replace(':', '', $cadena);
	$cadena = str_replace('??', '', $cadena);
	$cadena = str_replace('`', '', $cadena);
	$cadena = str_replace('!', '', $cadena);
	$cadena = str_replace('¿', '', $cadena);
	$cadena = str_replace(',', '-', $cadena);
	$originales = 	'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýýþÿ#¿?&®/\"';
    $modificadas = 	'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuuyyby-------';
	$cadena = strtr($cadena, utf8_decode($originales), $modificadas);
	$cadena = strtolower($cadena);
   	return $cadena;
}

#------Sanitizar variables GET y POST
function sanitize_xss($value) {
	return htmlspecialchars(strip_tags($value));
}

#------Sanitizar URLS
function sanitize_xss_url($value) {
	return strip_tags($value);
}

#------Meses desde hoy hasta fecha
function meses_hoy_hasta_fecha($value){
	$hoy = new DateTime(date('Y-m-d'));
	$fecha = new DateTime(date('Y-m-d', strtotime($value)));
	if($hoy < $fecha){
		$diff = $hoy->diff($fecha);
		$meses = ($diff->y * 12) + $diff->m;
		if($meses < 1){
			$meses = 1;
		}
	} else {
		$meses = 1;	
	}
	return $meses;
}

#------Encriptar Contraseñas
function crypt_pass($value) {
	return crypt($value, '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
}

#------Booleano (0,1) a SI-NO
function booleano_a_si_no($value){
	$value_txt = 'NO';
	if($value == '1'){
		$value_txt = 'SI';
	}
	return $value_txt;	
}

#------Formato fecha AAAA-MM-DD
function fecha_AAAAMMDD($value){
	if($value != ''){
		$value = str_replace('/', '-', $value);
		$value = new DateTime(date($value));
		$value = $value->format('Y-m-d');
	}
	return $value;
}

#------Quito separadores de miles
function sin_separadores_miles($value){
	if($value != ''){
		$value = str_replace(' ', '', $value);
		$value = str_replace(',', '', $value);
		$value = str_replace('.', '', $value);
	}
	return $value;
}

#------Mejoro textos en cotizaciones e impresiones
function mejoroTexto($value) {
	$cambiar = array(
		'Credito' => 'Con crédito',
		'Contado' => 'De contado',
		'60_40' => '60% crédito + 40% cuota inicial',
		'70_30' => '70% crédito + 30% cuota inicial',
		'80_20' => '80% crédito + 20% cuota inicial',
		'Cuotas_Iguales' => 'Sin financiación, cuotas iguales',
		'Una_Cuota' => 'Contado 1 cuota',
		'40_al_final' => '60% cuotas iguales + 40% en cuota final'
	);
	$value = $cambiar[$value];
	return $value;
}

#------Array equivalencia de meses
function mesNumero_a_mesTexto($value) {
	$mes_texto = array(
		'1' => 'Enero',
		'2' => 'Febrero',
		'3' => 'Marzo',
		'4' => 'Abril',
		'5' => 'Mayo',
		'6' => 'Junio',
		'7' => 'Julio',
		'8' => 'Agosto',
		'9' => 'Septiembre',
		'10' => 'Octubre',
		'11' => 'Noviembre',
		'12' => 'Diciembre'
	);
	$value = $mes_texto[$value];
	return $value;
}

#------Nombre de Administrador que fue eliminado
function sin_nombre_eliminado($value){
	if($value == ''){
		$value = '[Empleado ya no figura en base de datos]';
	}
	return $value;
}





?>