<?php
require_once("main.php");

$id = $_POST["punto_impresion_id"];//input hidden
$nombre = $_POST["nombre"];
$proximo_nro = $_POST["proximo_nro"];
$timbrado = $_POST["timbrado"];
$sucursal = $_POST["sucursal"];
$estado = $_POST["estado"];

//verificar en bd
$check_exist = con();
$check_exist = $check_exist->query("SELECT * FROM puntos_impresion WHERE punto_impresion_id = '$id'");

if($check_exist->rowCount()<=0){//no existe id
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No se encontró el punto de impresión.
    </div>';
    exit();
} else {
    $datos = $check_exist->fetch();
}
$check_exist=null;

//verifica campos obligatorios
if($nombre == "" || $proximo_nro == "" || $timbrado == ""){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No has llenado todos los campos que son obligatorios
    </div>';
    exit();
}

    //verifica name exists
if($nombre != $datos["punimp_nombre"]){
    $check_dup=con();
    $check_dup = $check_dup->query("SELECT punimp_nombre FROM puntos_impresion WHERE punimp_nombre = '$nombre'");//checks if exists
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
$actualizar_status = $actualizar_status->prepare("UPDATE puntos_impresion SET punimp_nombre = :punimp_nombre, punimp_prox_nro_fac = :punimp_prox_nro_fac, timbrado_id = :timbrado_id, sucursal_id = :sucursal_id, punimp_estado = :punimp_estado WHERE punto_impresion_id = :id");

$marcadores = [
    ":punimp_nombre" => $nombre,
    ":punimp_prox_nro_fac" => $proximo_nro,
    ":timbrado_id" => $timbrado,
    ":sucursal_id" => $sucursal,
    ":punimp_estado" => $estado,
    "id" => $id
];

if($actualizar_status->execute($marcadores)){
    echo '
    <div class="notification is-success is-light">
        <strong>Punto de Impresión actualizado!</strong><br>
        El punto de impresión se actualizó exitosamente.
    </div>';
} else {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No se pudo actualizar el punto de impresión, inténtelo nuevamente.
    </div>';   
}

$actualizar_status=null;