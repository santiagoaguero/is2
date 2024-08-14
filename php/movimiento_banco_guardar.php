<?php
include_once("main.php");

//almacenando datos
$comprobante = $_POST["comprobante"];
$origen = $_POST["origen"];
$destino = $_POST["destino"];
$fecha = $_POST["fecha"];
$total = $_POST["total"];

//verifica campos obligatorios
if($origen == $destino){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No puede utilizar el mismo banco como origen y destino
    </div>';
    exit();
}

//verifica unico
$check_nombre=con();
//query: inserta la consulta directo a la bd
$check_nombre = $check_nombre->query("SELECT movban_comprobante_nro FROM movimientos_bancos WHERE movban_comprobante_nro = '$comprobante'");//checks if exists
if($check_nombre->rowCount()>0){//found
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        Este comprobante ya está registrado en la base de datos.
    </div>';
    exit();
}
$check_nombre=null;//close db connection

//guardando datos
$guardar_datos = con();
//prepare: prepara la consulta antes de insertar directo a la bd. variables sin comillas ni $
$guardar_datos = $guardar_datos->prepare("INSERT INTO movimientos_bancos(banco_id, movban_fecha, movban_monto, movban_destino, movban_comprobante_nro) VALUES(:banco_id, :movban_fecha, :movban_monto, :movban_destino, :movban_comprobante_nro)");

$marcadores=[
    ":banco_id" => $origen,
    ":movban_fecha" => $fecha,
    ":movban_monto" => $total,
    ":movban_destino" => $destino,
    ":movban_comprobante_nro" => $comprobante
];

$guardar_datos->execute($marcadores);
if($guardar_datos->rowCount()==1){//1 fila afectada
    echo '
    <div class="notification is-success is-light">
        <strong>Guardado correctamente!</strong><br>
        El registro se guardó exitosamente.
    </div>';
} else {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No se pudo procesar la solicitud, inténtelo nuevamente.
    </div>';
}
$guardar_datos=null;