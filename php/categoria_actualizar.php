<?php
require_once("main.php");

$id = limpiar_cadena($_POST["categoria_id"]);//input hidden

//verificar en bd
$check_categoria = con();
$check_categoria = $check_categoria->query("SELECT * FROM categoria WHERE categoria_id = '$id'");

if($check_categoria->rowCount()<=0){//no existe id
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No se encontró la categoría.
    </div>';
    exit();
} else {
    $datos = $check_categoria->fetch();
}
$check_categoria=null;

//almacenando datos
$nombre=limpiar_cadena($_POST["categoria_nombre"]);
$ubicacion=limpiar_cadena($_POST["categoria_ubicacion"]);

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

if($ubicacion != ""){//al no ser obligatorio puede venir vacio
    if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{5,150}",$ubicacion)){
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            La ubicación no coincide con el formato esperado.
        </div>';
        exit();
    }
}

    //verifica nombre categoria exista
if($nombre != $datos["categoria_nombre"]){
    $check_nombre=con();
    $check_nombre = $check_nombre->query("SELECT categoria_nombre FROM categoria 
    WHERE categoria_nombre = '$nombre'");//checks if usuario exists
    if($check_nombre->rowCount()>0){//categoria found
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            Esta categoría ya está registrada en la base de datos, por favor elija otra categoría.
        </div>';
        exit();
    }
    $check_nombre=null;//close db connection
}

//Actualizando datos
$actualizar_categoria = con();
$actualizar_categoria = $actualizar_categoria->prepare("UPDATE categoria SET 
categoria_nombre = :nombre, categoria_ubicacion = :ubicacion WHERE categoria_id = :id");

$marcadores = [
"nombre" => $nombre,
"ubicacion" => $ubicacion,
"id" => $id];

if($actualizar_categoria->execute($marcadores)){
    echo '
    <div class="notification is-success is-light">
        <strong>Categoría actualizada!</strong><br>
        La categoría se actualizó exitosamente.
    </div>';
} else {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No se pudo actualizar la categoría, inténtelo nuevamente.
    </div>';   
}

$actualizar_categoria=null;