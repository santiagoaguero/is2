<?php
require_once("main.php");

$id = $_POST["deposito_id"];//input hidden
$nombre = $_POST["nombre"];

//verificar en bd
$check_exist = con();
$check_exist = $check_exist->query("SELECT * FROM depositos WHERE deposito_id = '$id'");

if($check_exist->rowCount()<=0){//no existe id
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No se encontró el registro.
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
if($nombre != $datos["dep_nombre"]){
    $check_dup=con();
    $check_dup = $check_dup->query("SELECT dep_nombre FROM depositos WHERE dep_nombre = '$nombre'");//checks if exists
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
$actualizar_status = $actualizar_status->prepare("UPDATE depositos SET dep_nombre = :dep_nombre WHERE deposito_id = :id");

$marcadores = [
    ":dep_nombre" => $nombre,
    "id" => $id
];

if($actualizar_status->execute($marcadores)){
    echo '
    <div class="notification is-success is-light">
        <strong>Depósito actualizado!</strong><br>
        Los datos del depósito se actualizaron exitosamente.
    </div>';
} else {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No se pudo actualizar el depósito, inténtelo nuevamente.
    </div>';   
}

$actualizar_status=null;