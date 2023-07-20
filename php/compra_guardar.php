<?php

// Obtener los datos del proveedor y la factura
$rucProveedor = $_POST['provee_ruc'];
$numeroFactura = $_POST['compra_factura'];

// Obtener los detalles de los productos
$idProductos = $_POST['id_producto'];
$cantidades = $_POST['cantidad_producto'];
$precios = $_POST['precio_producto'];

$compra_total = 0;

// Guardar los detalles de los productos en
for ($i = 0; $i < count($idProductos); $i++) {
    $idProducto = $idProductos[$i];
    $cantidad = $cantidades[$i];
    $precio = $precios[$i];
    $precioTotal = $cantidad * $precio;
    $compra_total += $precioTotal;

    // Crea tu consulta SQL para insertar los datos en la tabla detalle y ejecútala
    $queryDetalle = "INSERT INTO tabla_detalle (id_cabecera, id_producto, cantidad, precio_unitario, precio_total) VALUES ('$idCabecera', '$idProducto', '$cantidad', '$precio', '$precioTotal')";
    // Ejecuta la consulta
}


echo $compra_total;