<?php
require_once("main.php");

$id = $_POST["banco_id"];//input hidden
$nombre = $_POST["nombre"];
$ruc = $_POST["ruc"];
$direccion = $_POST["direccion"];
$telefono = $_POST["telefono"];
$mail = $_POST["mail"];

//verificar en bd
$check_exist = con();
$check_exist = $check_exist->query("SELECT * FROM bancos WHERE banco_id = '$id'");

if($check_exist->rowCount()<=0){//no existe id
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No se encontró la entidad bancaria.
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
if($nombre != $datos["ban_nombre"]){
    $check_dup=con();
    $check_dup = $check_dup->query("SELECT ban_nombre FROM bancos WHERE ban_nombre = '$nombre'");//checks if exists
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
$actualizar_status = $actualizar_status->prepare("UPDATE bancos SET ban_nombre = :ban_nombre, ban_ruc = :ban_ruc, ban_direccion = :ban_direccion, ban_telefono = :ban_telefono, ban_mail = :ban_mail WHERE banco_id = :id");

$marcadores = [
    ":ban_nombre" => $nombre,
    ":ban_ruc" => $ruc,
    ":ban_direccion" => $direccion,
    ":ban_telefono" => $telefono,
    ":ban_mail" => $mail,
    "id" => $id
];

if($actualizar_status->execute($marcadores)){
    echo '
    <div class="notification is-success is-light">
        <strong>Entidad Bancaria actualizada!</strong><br>
        Los datos del banco se actualizaron exitosamente.
    </div>';
} else {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No se pudo actualizar el banco, inténtelo nuevamente.
    </div>';   
}

$actualizar_status=null;