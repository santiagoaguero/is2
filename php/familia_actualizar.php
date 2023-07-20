<?php
require_once("main.php");

$id = limpiar_cadena($_POST["familia_id"]);//input hidden

//verificar en bd
$check_familia = con();
$check_familia = $check_familia->query("SELECT * FROM familia WHERE familia_id = '$id'");

if($check_familia->rowCount()<=0){//no existe id
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No se encontró la familia.
    </div>';
    exit();
} else {
    $datos = $check_familia->fetch();
}
$check_familia=null;

//almacenando datos
$nombre=limpiar_cadena($_POST["familia_nombre"]);

//verifica campos obligatorios
if($nombre == ""){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No has llenado todos los campos que son obligatorios
    </div>';
    exit();
}

//verifica integridad de los datos
if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{4,50}",$nombre)){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        El NOMBRE no coincide con el formato esperado.
    </div>';
    exit();
}

    //verifica nombre familia exista
if($nombre != $datos["familia_nombre"]){
    $check_nombre=con();
    $check_nombre = $check_nombre->query("SELECT familia_nombre FROM familia 
    WHERE familia_nombre = '$nombre'");//checks if familia exists
    if($check_nombre->rowCount()>0){//familia found
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            Esta familia ya está registrada en la base de datos, por favor elija otra familia.
        </div>';
        exit();
    }
    $check_nombre=null;//close db connection
}

//Actualizando datos
$actualizar_familia = con();
$actualizar_familia = $actualizar_familia->prepare("UPDATE familia SET 
familia_nombre = :nombre WHERE familia_id = :id");

$marcadores = [
"nombre" => $nombre,
"id" => $id];

if($actualizar_familia->execute($marcadores)){
    echo '
    <div class="notification is-success is-light">
        <strong>Familia actualizada!</strong><br>
        La familia se actualizó exitosamente.
    </div>';
} else {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No se pudo actualizar la familia, inténtelo nuevamente.
    </div>';   
}

$actualizar_familia=null;