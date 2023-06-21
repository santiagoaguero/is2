<?php
require_once("main.php");

$id = limpiar_cadena($_POST["provee_id"]);//input hidden

//verificar en bd
$check_provee = con();
$check_provee = $check_provee->query("SELECT * FROM proveedor WHERE prov_id = '$id'");

if($check_provee->rowCount()<=0){//no existe id
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No se encontró el proveedor.
    </div>';
    exit();
} else {
    $datos = $check_provee->fetch();
}
$check_provee=null;

//almacenando datos
$nombre=limpiar_cadena($_POST["provee_nombre"]);
$ruc=limpiar_cadena($_POST["provee_ruc"]);
$telefono=limpiar_cadena($_POST["provee_telefono"]);
$direccion=limpiar_cadena($_POST["provee_direccion"]);

//verifica campos obligatorios
if($nombre == "" || $ruc == "" || $telefono == ""){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No has llenado todos los campos que son obligatorios
    </div>';
    exit();
}

//verifica integridad de los datos
if(verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ. ]{3,40}",$nombre)){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        El NOMBRE no coincide con el formato esperado.
    </div>';
    exit();
}

if(verificar_datos("[0-9- ]{6,30}",$telefono)){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        El teléfono no coincide con el formato esperado.
    </div>';
    exit();
}

if(verificar_datos("[a-zA-Z0-9.-]{4,12}",$ruc)){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        El RUC no coincide con el formato esperado.
    </div>';
    exit();
}

    //verifica ruc proveedor exista
if($ruc != $datos["prov_ruc"]){
    $check_ruc=con();
    $check_ruc = $check_ruc->query("SELECT prov_ruc FROM proveedor 
    WHERE prov_ruc = '$ruc'");//checks if ruc exists
    if($check_ruc->rowCount()>0){//ruc found
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            Este RUC ya está registrado en la base de datos, por favor elija otro RUC.
        </div>';
        exit();
    }
    $check_ruc=null;//close db connection
}

//Actualizando datos
$actualizar_provee = con();
$actualizar_provee = $actualizar_provee->prepare("UPDATE proveedor SET 
prov_nombre = :nombre, prov_ruc = :ruc, prov_telefono = :telefono, prov_direccion = :direccion WHERE prov_id = :id");

$marcadores = [
"nombre" => $nombre,
"ruc" => $ruc,
"telefono"=>$telefono,
"direccion"=>$direccion,
"id" => $id];

if($actualizar_provee->execute($marcadores)){
    echo '
    <div class="notification is-success is-light">
        <strong>Proveedor actualizad!</strong><br>
        El proveedor se actualizó exitosamente.
    </div>';
} else {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No se pudo actualizar el proveedor, inténtelo nuevamente.
    </div>';   
}

$actualizar_provee=null;