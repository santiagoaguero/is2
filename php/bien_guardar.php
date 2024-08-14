<?php
include_once("main.php");

//almacenando datos
$nombre = $_POST["nombre"];
$cantidad = $_POST["cantidad"];
$marca = $_POST["marca"];
$modelo = $_POST["modelo"];
$fecha_adquisicion = $_POST["fecha_adquisicion"];
$proveedor = $_POST["proveedor"];
$ubicacion = $_POST["ubicacion"];

//verifica campos obligatorios
if($cantidad == "" || $cantidad == 0 || $nombre == ""){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No has llenado todos los campos que son obligatorios
    </div>';
    exit();
}

//verifica unico
$check_nombre=con();
//query: inserta la consulta directo a la bd
$check_nombre = $check_nombre->query("SELECT bien_nombre FROM bienes WHERE bien_nombre = '$nombre'");//checks if exists
if($check_nombre->rowCount()>0){//found
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        Este nombre ya está registrado en la base de datos.
    </div>';
    exit();
}
$check_nombre=null;//close db connection

//guardando datos
$guardar_datos = con();
//prepare: prepara la consulta antes de insertar directo a la bd. variables sin comillas ni $
$guardar_datos = $guardar_datos->prepare("INSERT INTO bienes(bien_nombre, bien_cantidad, bien_marca, bien_modelo, bien_fecha_adquisicion, prov_id, bien_ubicacion) VALUES(:bien_nombre, :bien_cantidad, :bien_marca, :bien_modelo, :bien_fecha_adquisicion, :prov_id, :bien_ubicacion)");

$marcadores=[
    ":bien_nombre" => $nombre,
    ":bien_cantidad" => $cantidad,
    ":bien_marca" => $marca,
    ":bien_modelo" => $modelo,
    ":bien_fecha_adquisicion" => $fecha_adquisicion,
    ":prov_id" => $proveedor,
    ":bien_ubicacion" => $ubicacion
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