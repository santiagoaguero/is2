<?php
include_once("main.php");

//almacenando datos
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
$conn = con();
$stock_actual = "SELECT producto_stock FROM producto WHERE producto_id = $producto";
$result = $conn->query($stock_actual);
$nuevo_stock = (int)$result->fetch()['producto_stock'] - $cantidad;

$actualizar_stock = $conn->prepare("UPDATE producto SET producto_stock = :nuevo_stock WHERE producto_id = :producto");
$actualizar_stock->execute([
    ":nuevo_stock" => $nuevo_stock,
    ":producto" => $producto
]);

//guardando datos
$guardar_datos = con();
//prepare: prepara la consulta antes de insertar directo a la bd. variables sin comillas ni $
$guardar_datos = $guardar_datos->prepare("INSERT INTO devoluciones(factura_id, producto_id, dev_cantidad, dev_motivo, dev_fecha) VALUES(:factura_id, :producto_id, :dev_cantidad, :dev_motivo, :dev_fecha)");

$marcadores=[
    ":factura_id" => $factura,
    ":producto_id" => $producto,
    ":dev_cantidad" => $cantidad,
    ":dev_motivo" => $motivo,
    ":dev_fecha" => $fecha
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