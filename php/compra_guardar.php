<?php
require_once("main.php");
require_once("../inc/session_start.php");
// Obtener los datos del proveedor y la factura

$fecha = $_POST["compra_fecha"];
$prov_id = $_POST['prov_id'];
$numeroFactura = $_POST['compra_factura'];
$condicion = $_POST['compra_condicion'];

// Obtener los detalles de los productos
$idProductos = $_POST['id_producto'];
$cantidades = $_POST['cantidad_producto'];
$precios = $_POST['precio_producto'];
$compra_total = 0;
$usuario_id = $_SESSION["id"];

for($i=0; $i < count($cantidades); $i++){
    $total = $cantidades[$i] * $precios[$i];
    $compra_total += $total;
}

// Consulta preparada para insertar la nueva compra en la base de datos
$consultaInsertar = "INSERT INTO compras (compra_fecha, prov_id, usuario_id, compra_total, compra_factura, compra_condicion) VALUES (:fecha, :prov, :user, :total, :numero, :condicion)";

$conexion=con();
$hayError = false;

// Preparar la consulta
$insertar = $conexion->prepare($consultaInsertar);

// Asignar los valores a los parámetros de la consulta preparada
$insertar->bindValue(':fecha', $fecha);
$insertar->bindValue(':prov', $prov_id);
$insertar->bindValue(':user', $usuario_id);
$insertar->bindValue(':total', $compra_total);
$insertar->bindValue(':numero', $numeroFactura);
$insertar->bindValue(':condicion', $condicion);

// Ejecutar la consulta preparada
if (!$insertar->execute()) {
    // Hubo un error al insertar la cabecera de la factura
    $hayError = true;
    echo 'Error al guardar la compra: ' . $insertar->errorInfo()[2];
}
// Cerrar el statement
$insertar = null;


if ($hayError){
    
    $conexion=null;

} else {
    // Obtener el ID de la última factura insertada
    $compra_id = $conexion->lastInsertId();

    // Guardar los detalles de los productos en
    for ($i = 0; $i < count($idProductos); $i++) {
        $idProducto = $idProductos[$i];
        $cantidad = $cantidades[$i];
        $precio = $precios[$i];
    
        // Crea tu consulta SQL para insertar los datos en la tabla detalle y ejecútala
        $queryDetalle = "INSERT INTO compras_detalle (producto_id, cantidad, precio_compra, compra_id) VALUES (:prod, :cantidad, :precio, :id)";
        
        $conexion=con();
        $hayError = false;

        // Preparar la consulta
        $insertar = $conexion->prepare($queryDetalle);

        // Asignar los valores a los parámetros de la consulta preparada
        $insertar->bindValue(':prod', $idProducto);
        $insertar->bindValue(':cantidad', $cantidad);
        $insertar->bindValue(':precio', $precio);
        $insertar->bindValue(':id', $compra_id);

        // Ejecutar la consulta preparada
        if (!$insertar->execute()) {
            // Hubo un error al insertar detall de la compra
            $hayError = true;
            echo 'Error al guardar la compra: ' . $insertar->errorInfo()[2];
        }
        // Cerrar el statement
        $insertar = null;
    }
    
}







