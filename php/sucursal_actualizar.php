<?php
require_once("main.php");

$id = $_POST["sucursal_id"];//input hidden
$nombre = $_POST["nombre"];
$direccion = $_POST["direccion"];
$estado = $_POST["estado"];

//verificar en bd
$check_exist = con();
$check_exist = $check_exist->query("SELECT * FROM sucursales WHERE sucursal_id = '$id'");

if($check_exist->rowCount()<=0){//no existe id
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No se encontró la sucursal.
    </div>';
    exit();
} else {
    $datos = $check_exist->fetch();
}
$check_exist=null;

//verifica campos obligatorios
if($nombre == ""){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No has llenado todos los campos que son obligatorios
    </div>';
    exit();
}

    //verifica name exists
if($nombre != $datos["suc_nombre"]){
    $check_dup=con();
    $check_dup = $check_dup->query("SELECT suc_nombre FROM sucursales WHERE suc_nombre = '$nombre'");//checks if exists
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
$actualizar_status = $actualizar_status->prepare("UPDATE sucursales SET suc_nombre = :suc_nombre, suc_direccion = :suc_direccion, suc_activo = :suc_activo WHERE sucursal_id = :id");

$marcadores = [
    ":suc_nombre" => $nombre,
    ":suc_direccion" => $direccion,
    ":suc_activo" => $estado,
    "id" => $id
];

if($actualizar_status->execute($marcadores)){
    echo '
    <div class="notification is-success is-light">
        <strong>Sucursal actualizada!</strong><br>
        La sucursal se actualizó exitosamente.
    </div>';
} else {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No se pudo actualizar la sucursal, inténtelo nuevamente.
    </div>';   
}

$actualizar_status=null;