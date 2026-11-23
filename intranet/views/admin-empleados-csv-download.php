<?php
header('Content-Encoding: UTF-8');
header('Content-Type: text/csv; charset=utf-8');  
header('Content-Disposition: attachment; filename='.$fileName); 

#---Crea archivo servidor
$output = fopen('php://output', 'w'); 

#---Encabezado
fputcsv($output, array(
    'importante_dejar_vacia',
    'empleadoDocumento',
    'empleadoNombre',
    'empleadoEmail'
));

#---Loop
foreach($empleados as $empleado){
    fputcsv($output, array(
        '',
        $empleado['empleadoDocumento'],
        $empleado['empleadoNombre'],
        $empleado['empleadoEmail']
    ));
}

#---Descarga
fclose($output);
exit();
?>