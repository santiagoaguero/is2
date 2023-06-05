<?php
require_once("main.php");

//almacenando datos
$nombre=limpiar_cadena($_POST["usuario_nombre"]);
$apellido=limpiar_cadena($_POST["usuario_apellido"]);
$usuario=limpiar_cadena($_POST["usuario_usuario"]);
$email=limpiar_cadena($_POST["usuario_email"]);
$clave_1=limpiar_cadena($_POST["usuario_clave_1"]);
$clave_2=limpiar_cadena($_POST["usuario_clave_2"]);

//verifica campos obligatorios
if($nombre == "" || $apellido == "" || $usuario == "" || $clave_1 == "" || $clave_2 == ""){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No has llenado todos los campos que son obligatorios
    </div>';
    exit();
}

//verifica integridad de los datos
if(verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ ]{3,40}",$nombre)){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        El NOMBRE no coincide con el formato esperado.
    </div>';
    exit();
}

if(verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ ]{3,40}",$apellido)){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        El APELLIDO no coincide con el formato esperado.
    </div>';
    exit();
}

if(verificar_datos("[a-zA-Z0-9]{4,20}",$usuario)){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        El USUARIO no coincide con el formato esperado.
    </div>';
    exit();
}

if(verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave_1) || verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave_2) ){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        LAS CLAVES no coincide con el formato esperado.
    </div>';
    exit();
}


//verifica email
if($email != ""){
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


//claves coinciden
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


//guardando datos
$guardar_usuario = con();
//prepare: prepara la consulta antes de insertar directo a la bd. variables sin comillas ni $
$guardar_usuario = $guardar_usuario->prepare("INSERT INTO
    usuario(usuario_nombre, usuario_apellido, usuario_usuario, usuario_email, usuario_clave)
    VALUES(:nombre, :apellido, :usuario, :email, :clave)");

//evitando inyecciones sql xss
$marcadores=[
    ":nombre"=>$nombre, ":apellido"=>$apellido, ":usuario"=>$usuario, ":email"=>$email, ":clave"=>$clave];

$guardar_usuario->execute($marcadores);

if($guardar_usuario->rowCount()==1){// 1 usuario nuevo insertado
    echo '
    <div class="notification is-success is-light">
        <strong>¡Usuario registrado!</strong><br>
        El usuario se registró exitosamente.
    </div>';
} else {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No se pudo registrar el usuario, intentelo nuevamente.
    </div>';
}
$guardar_usuario=null; //cerrar conexion;