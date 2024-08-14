<?php
require_once("main.php");

$id = $_POST["movimiento_banco_id"];//input hidden
$comprobante = $_POST["comprobante"];
$origen = $_POST["origen"];
$destino = $_POST["destino"];
$fecha = $_POST["fecha"];
$total = $_POST["total"];

//verificar en bd
$check_exist = con();
$check_exist = $check_exist->query("SELECT * FROM movimientos_bancos WHERE movimiento_banco_id = '$id'");

if($check_exist->rowCount()<=0){//no existe id
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No se encontró el movimiento.
    </div>';
    exit();
} else {
    $datos = $check_exist->fetch();
}
$check_exist=null;

//verifica campos obligatorios
if($origen == $destino){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No puede utilizar el mismo banco como origen y destino
    </div>';
    exit();
}

    //verifica name exists
if($comprobante != $datos["movban_comprobante_nro"]){
    $check_dup=con();
    $check_dup = $check_dup->query("SELECT movban_comprobante_nro FROM movimientos_bancos WHERE movban_comprobante_nro = '$comprobante'");//checks if exists
    if($check_dup->rowCount()>0){//found
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            Este registro ya existe en la base de datos.
        </div>';
        exit();
    }
    $check_dup=null;//close db connection
}

//Actualizando datos
$actualizar_status = con();
$actualizar_status = $actualizar_status->prepare("UPDATE movimientos_bancos SET banco_id = :banco_id, movban_fecha = :movban_fecha, movban_monto = :movban_monto, movban_destino = :movban_destino, movban_comprobante_nro = :movban_comprobante_nro WHERE movimiento_banco_id = :id");

$marcadores = [
    ":banco_id" => $origen,
    ":movban_fecha" => $fecha,
    ":movban_monto" => $total,
    ":movban_destino" => $destino,
    ":movban_comprobante_nro" => $comprobante,
    "id" => $id
];

if($actualizar_status->execute($marcadores)){
    echo '
    <div class="notification is-success is-light">
        <strong>Timbrado actualizado!</strong><br>
        El registro se actualizó exitosamente.
    </div>';
} else {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No se pudo actualizar el registro, inténtelo nuevamente.
    </div>';   
}

$actualizar_status=null;