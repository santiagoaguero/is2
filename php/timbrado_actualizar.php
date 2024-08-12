<?php
require_once("main.php");

$id = $_POST["timbrado_id"];//input hidden
$numero = $_POST["timbrado_numero"];
$fecha_emision = $_POST["timbrado_fecha_emision"];
$fecha_vencimiento = $_POST["timbrado_fecha_vencimiento"];
$estado = $_POST["timbrado_estado"];
$descripcion = $_POST["timbrado_descripcion"];

//verificar en bd
$check_exist = con();
$check_exist = $check_exist->query("SELECT * FROM timbrados WHERE timbrado_id = '$id'");

if($check_exist->rowCount()<=0){//no existe id
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No se encontró el timbrado.
    </div>';
    exit();
} else {
    $datos = $check_exist->fetch();
}
$check_exist=null;

//verifica campos obligatorios
if($numero == "" || $fecha_emision == "" || $fecha_vencimiento == ""){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No has llenado todos los campos que son obligatorios
    </div>';
    exit();
}

    //verifica name exists
if($numero != $datos["numero"]){
    $check_dup=con();
    $check_dup = $check_dup->query("SELECT numero FROM timbrados WHERE numero = '$numero'");//checks if exists
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
$actualizar_status = $actualizar_status->prepare("UPDATE timbrados SET numero = :numero, fecha_emision = :fecha_emision, fecha_vencimiento = :fecha_vencimiento, estado = :estado, descripcion = :descripcion WHERE timbrado_id = :id");

$marcadores = [
    ":numero" => $numero,
    ":fecha_emision" => $fecha_emision,
    ":fecha_vencimiento" => $fecha_vencimiento,
    ":estado" => $estado,
    ":descripcion" => $descripcion,
    "id" => $id
];

if($actualizar_status->execute($marcadores)){
    echo '
    <div class="notification is-success is-light">
        <strong>Timbrado actualizado!</strong><br>
        El trimbrado se actualizó exitosamente.
    </div>';
} else {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No se pudo actualizar el timbrado, inténtelo nuevamente.
    </div>';   
}

$actualizar_status=null;