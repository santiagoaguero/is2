<?php
require_once("main.php");

$id = $_POST["forma_pago_id"];//input hidden
$descripcion = $_POST["forma_pago_descripcion"];

//verificar en bd
$check_exist = con();
$check_exist = $check_exist->query("SELECT * FROM formas_pago WHERE forma_pago_id = '$id'");

if($check_exist->rowCount()<=0){//no existe id
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No se encontró la forma de pago.
    </div>';
    exit();
} else {
    $datos = $check_exist->fetch();
}
$check_exist=null;

//verifica campos obligatorios
if($descripcion == ""){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No has llenado todos los campos que son obligatorios
    </div>';
    exit();
}

    //verifica name exists
if($descripcion != $datos["descripcion"]){
    $check_dup=con();
    $check_dup = $check_dup->query("SELECT descripcion FROM formas_pago WHERE descripcion = '$descripcion'");//checks if exists
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
$actualizar_status = $actualizar_status->prepare("UPDATE formas_pago SET descripcion = :descripcion WHERE forma_pago_id = :id");

$marcadores = [
    ":descripcion" => $descripcion,
    "id" => $id
];

if($actualizar_status->execute($marcadores)){
    echo '
    <div class="notification is-success is-light">
        <strong>Forma de Pago actualizada!</strong><br>
        El trimbrado se actualizó exitosamente.
    </div>';
} else {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No se pudo actualizar la forma de pago, inténtelo nuevamente.
    </div>';   
}

$actualizar_status=null;