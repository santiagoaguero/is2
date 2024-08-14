<?php
require_once("main.php");

$id = $_POST["bien_id"];//input hidden
$nombre = $_POST["nombre"];
$cantidad = $_POST["cantidad"];
$marca = $_POST["marca"];
$modelo = $_POST["modelo"];
$fecha_adquisicion = $_POST["fecha_adquisicion"];
$proveedor = $_POST["proveedor"];
$ubicacion = $_POST["ubicacion"];

//verificar en bd
$check_exist = con();
$check_exist = $check_exist->query("SELECT * FROM bienes WHERE bien_id = '$id'");

if($check_exist->rowCount()<=0){//no existe id
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No se encontró el bien/activo.
    </div>';
    exit();
} else {
    $datos = $check_exist->fetch();
}
$check_exist=null;

//verifica campos obligatorios
if($cantidad == "" || $cantidad == 0 || $nombre == ""){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No has llenado todos los campos que son obligatorios
    </div>';
    exit();
}

    //verifica name exists
//if($nombre != $datos["bien_nombre"]){
//    $check_dup=con();
//    $check_dup = $check_dup->query("SELECT bien_nombre FROM bienes WHERE bien_nombre = '$nombre'");//checks if exists
//    if($check_dup->rowCount()>0){//found
//        echo '
//        <div class="notification is-danger is-light">
//            <strong>¡Ocurrió un error inesperado!</strong><br>
//            Este registro ya existe en la base de datos.
//        </div>';
//        exit();
//    }
//    $check_dup=null;//close db connection
//}

//Actualizando datos
$actualizar_status = con();
$actualizar_status = $actualizar_status->prepare("UPDATE bienes SET bien_nombre = :bien_nombre, bien_cantidad = :bien_cantidad, bien_marca = :bien_marca, bien_modelo = :bien_modelo, bien_fecha_adquisicion = :bien_fecha_adquisicion, prov_id = :prov_id, bien_ubicacion = :bien_ubicacion WHERE bien_id = :id");

$marcadores = [
    ":bien_nombre" => $nombre,
    ":bien_cantidad" => $cantidad,
    ":bien_marca" => $marca,
    ":bien_modelo" => $modelo,
    ":bien_fecha_adquisicion" => $fecha_adquisicion,
    ":prov_id" => $proveedor,
    ":bien_ubicacion" => $ubicacion,
    "id" => $id
];

if($actualizar_status->execute($marcadores)){
    echo '
    <div class="notification is-success is-light">
        <strong>Bien/Activo actualizado!</strong><br>
        El bien/activo se actualizó exitosamente.
    </div>';
} else {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No se pudo actualizar el bien/activo, inténtelo nuevamente.
    </div>';
}

$actualizar_status=null;