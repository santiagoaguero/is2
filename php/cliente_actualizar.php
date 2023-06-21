<?php
require_once("main.php");

$id = limpiar_cadena($_POST["cliente_id"]);//input hidden

//verificar en bd
$check_provee = con();
$check_provee = $check_provee->query("SELECT * FROM clientes WHERE cliente_id = '$id'");

if($check_provee->rowCount()<=0){//no existe id
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No se encontró el cliente.
    </div>';
    exit();
} else {
    $datos = $check_provee->fetch();
}
$check_provee=null;

//almacenando datos
$nombre=limpiar_cadena($_POST["cliente_nombre"]);
$apellido=limpiar_cadena($_POST["cliente_apellido"]);
$ruc=limpiar_cadena($_POST["cliente_ruc"]);
$email=limpiar_cadena($_POST["cliente_email"]);
$telefono=limpiar_cadena($_POST["cliente_telefono"]);
$direccion=limpiar_cadena($_POST["cliente_direccion"]);

//verifica campos obligatorios
if($nombre == "" || $ruc == ""){
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

//Apellido no obligatorio porque algunos piden a nombre de Empresas

if(verificar_datos("[a-zA-Z0-9.-]{4,12}",$ruc)){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        El RUC no coincide con el formato esperado.
    </div>';
    exit();
}

//verifica email
if($email != ""){
    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
        $check_email=con();
        $check_email=$check_email->query("SELECT cliente_email FROM clientes 
        WHERE cliente_email = '$email'");//checks if email exists
        if($check_email->rowCount()>0){//email found and emails gotta be unique
            echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                El email ya está registrado en la base de datos, por favor elija otro email.
            </div>';
            exit();
        }
        $check_email=null;//close db connection
    } else {
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            El email no coincide con el formato esperado.
        </div>';
        exit();
    }
}

//verifica ruc unico
$check_ruc=con();
//query: inserta la consulta directo a la bd
$check_ruc=$check_ruc->query("SELECT cliente_ruc FROM clientes 
WHERE cliente_ruc = '$ruc'");//checks if usuario exists
if($check_ruc->rowCount()>0){//usuarios found
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        El RUC ya está registrado en la base de datos, por favor elija otro RUC.
    </div>';
    exit();
}
$check_ruc=null;//close db connection

//Actualizando datos
$actualizar_cliente = con();
$actualizar_cliente = $actualizar_cliente->prepare("UPDATE clientes SET 
cliente_nombre = :nombre, cliente_apellido = :apellido, cliente_ruc = :ruc, cliente_email = :email, cliente_telefono = :telefono, cliente_direccion = :direccion WHERE cliente_id = :id");

$marcadores = [
"nombre" => $nombre,
"apellido" => $apellido,
"ruc" => $ruc,
"email" => $email,
"telefono" => $telefono,
"direccion" => $direccion,
"id" => $id];

if($actualizar_cliente->execute($marcadores)){
    echo '
    <div class="notification is-success is-light">
        <strong>Cliente actualizado!</strong><br>
        El cliente se actualizó exitosamente.
    </div>';
} else {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No se pudo actualizar el cliente, inténtelo nuevamente.
    </div>';   
}

$actualizar_categoria=null;