<?php
require_once("main.php");

$id = $_POST["devolucion_id"];//input hidden
$factura = $_POST["factura"];
$producto = $_POST["producto"];
$cantidad = $_POST["cantidad"];
$motivo = $_POST["motivo"];
$fecha = $_POST["fecha"];

//verifica campos obligatorios
if($producto == "" || $factura == "" || $cantidad == ""){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No has llenado todos los campos que son obligatorios
    </div>';
    exit();
}

//actualizar stock
$check_stock = con();
$check_stock = $check_stock->query("SELECT * FROM devoluciones WHERE devolucion_id = '$id'");
$datos = $check_stock->fetch();

if ($datos['dev_cantidad'] !== $cantidad) {
    $conn = con();
    $stock_actual = "SELECT producto_stock FROM producto WHERE producto_id = $producto";
    $result = $conn->query($stock_actual);

    if ($datos['dev_cantidad'] > $cantidad) {
        $nueva_cantidad = $datos['dev_cantidad'] - $cantidad;
        $nuevo_stock = (int)$result->fetch()['producto_stock'] + $nueva_cantidad;
    } else {
        $nueva_cantidad = $cantidad - $datos['dev_cantidad'];
        $nuevo_stock = (int)$result->fetch()['producto_stock'] - $nueva_cantidad;
    }
    
    $actualizar_stock = $conn->prepare("UPDATE producto SET producto_stock = :nuevo_stock WHERE producto_id = :producto");
    $actualizar_stock->execute([
        ":nuevo_stock" => $nuevo_stock,
        ":producto" => $producto
    ]);
}

//Actualizando datos
$actualizar_status = con();
$actualizar_status = $actualizar_status->prepare("UPDATE devoluciones SET factura_id = :factura_id, producto_id = :producto_id, dev_cantidad = :dev_cantidad, dev_motivo = :dev_motivo, dev_fecha = :dev_fecha WHERE devolucion_id = :id");

$marcadores = [
    ":factura_id" => $factura,
    ":producto_id" => $producto,
    ":dev_cantidad" => $cantidad,
    ":dev_motivo" => $motivo,
    ":dev_fecha" => $fecha,
    "id" => $id
];

if($actualizar_status->execute($marcadores)){
    echo '
    <div class="notification is-success is-light">
        <strong>Datos actualizados!</strong><br>
        La devolución se actualizó exitosamente.
    </div>';
} else {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No se pudo actualizar el registro, inténtelo nuevamente.
    </div>';
}

$actualizar_status=null;