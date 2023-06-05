<?php 
require_once("../inc/session_start.php");
require_once("main.php");

$id = limpiar_cadena($_POST["usuario_id"]);//input hidden

//verificar en bd
$check_usuario = con();
$check_usuario = $check_usuario->query("SELECT * FROM usuario WHERE usuario_id = '$id'");

if($check_usuario->rowCount()==0){//no existe id
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No se encontró el usuario.
    </div>';
    exit();
} else {
    $datos = $check_usuario->fetch();
}
$check_usuario=null;

//datos de formulario de confirmacion para actualizar usuario
$admin_usuario = limpiar_cadena($_POST["administrador_usuario"]);
$admin_clave = limpiar_cadena($_POST["administrador_clave"]);

//verifica campos obligatorios
if($admin_usuario == "" || $admin_clave == ""){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No has llenado todos los campos que son obligatorios
    </div>';
    exit();
}

//verifica integridad de los datos
if(verificar_datos("[a-zA-Z0-9]{4,20}",$admin_usuario)){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        El USUARIO no coincide con el formato esperado.
    </div>';
    exit();
}

if(verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$admin_clave) ){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        LA CLAVE no coincide con el formato esperado.
    </div>';
    exit();
}


//verifica usuario que actualiza sea el mismo que inició sesion
$check_admin =con ();
$check_admin = $check_admin->query("SELECT usuario_usuario, usuario_clave FROM usuario WHERE
usuario_usuario = '$admin_usuario' AND usuario_id = '".$_SESSION["id"]."'");

if($check_admin->rowCount()==1){//existe usuario asociado a la sesion
    $check_admin=$check_admin->fetch();

    if($check_admin['usuario_usuario'] != $admin_usuario || !password_verify($admin_clave,$check_admin['usuario_clave'])){
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            Usuario o Clave de administrador incorrecto.
        </div>';
    }
} else {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        Usuario o Clave de administrador incorrecto.
    </div>';
    exit();
}
$check_admin=null;

//almacenando datos
$nombre=limpiar_cadena($_POST["usuario_nombre"]);
$apellido=limpiar_cadena($_POST["usuario_apellido"]);
$usuario=limpiar_cadena($_POST["usuario_usuario"]);
$email=limpiar_cadena($_POST["usuario_email"]);
$clave_1=limpiar_cadena($_POST["usuario_clave_1"]);
$clave_2=limpiar_cadena($_POST["usuario_clave_2"]);

//verifica campos obligatorios
//las claves NO porque en este formulario actualizar no son obligatorios
if($nombre == "" || $apellido == "" || $usuario == ""){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        No has llenado todos los campos que son obligatorios
    </div>';
    exit();
}

//verifica integridad de los datos
if(verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ ]{3,40}",$nombre)){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        El NOMBRE no coincide con el formato esperado.
    </div>';
    exit();
}

if(verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ ]{3,40}",$apellido)){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        El APELLIDO no coincide con el formato esperado.
    </div>';
    exit();
}

if(verificar_datos("[a-zA-Z0-9]{4,20}",$usuario)){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        El USUARIO no coincide con el formato esperado.
    </div>';
    exit();
}


//verifica email
if($email != "" && $email != $datos["usuario_email"]){
    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
        $check_email=con();
        $check_email=$check_email->query("SELECT usuario_email FROM usuario 
        WHERE usuario_email = '$email'");//checks if email exists
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

//verifica usuario unico
if($usuario != $datos["usuario_usuario"]){

    $check_usuario=con();
    //query: inserta la consulta directo a la bd
    $check_usuario=$check_usuario->query("SELECT usuario_usuario FROM usuario 
    WHERE usuario_usuario = '$usuario'");//checks if usuario exists
    if($check_usuario->rowCount()>0){//usuarios found
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            El usuario ya está registrado en la base de datos, por favor elija otro usuario.
        </div>';
        exit();
    }
    $check_usuario=null;//close db connection
}

//claves coincidan
if($clave_1 != "" || $clave_2 != ""){
    if(verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave_1) || verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave_2) ){
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            LAS CLAVES no coincide con el formato esperado.
        </div>';
        exit();
    } else {
        if($clave_1 != $clave_2){
            echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                Las claves no coinciden.
            </div>';
            exit();
        } else {
            $clave = password_hash($clave_1, PASSWORD_BCRYPT, ["cost"=>10]);
        }
    }
} else {
    $clave = $datos["usuario_clave"];//si no actualiza mantiene su misma clave
}

//Actualizando datos
$actualizar_usuario = con();
$actualizar_usuario = $actualizar_usuario->prepare("UPDATE usuario SET 
usuario_nombre = :nombre,usuario_apellido = :apellido, usuario_usuario = :usuario, usuario_clave = :clave, usuario_email = :email WHERE usuario_id = :id");

//evitando inyecciones sql xss
$marcadores=[
    ":nombre"=>$nombre, ":apellido"=>$apellido, ":usuario"=>$usuario, ":email"=>$email, ":clave"=>$clave, ":id"=>$id];

;

if($actualizar_usuario->execute($marcadores)){
    echo '
    <div class="notification is-success is-light">
        <strong>¡Usuario actualizado!</strong><br>
        El usuario se actualizó exitosamente.
    </div>';
} else {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No se pudo actualizar el usuario, inténtelo nuevamente.
    </div>';   
}

$actualizar_usuario=null;