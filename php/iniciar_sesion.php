<?php

//almacenando datos
$usuario=limpiar_cadena($_POST["login_usuario"]);
$clave=limpiar_cadena($_POST["login_clave"]);

//verifica campos obligatorios
if($usuario == "" || $clave == "" ){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        No has llenado todos los campos que son obligatorios
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

if(verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave)){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        La CLAVE no coincide con el formato esperado.
    </div>';
    exit();
}

//verificar en la bd
$check_user=con();
$check_user=$check_user->query("SELECT * from usuario WHERE
 usuario_usuario='$usuario'");

 if($check_user->rowCount()==1){//user found
    $check_user=$check_user->fetch();//fetch only one user. fetchAll fetchs all users
    if($check_user["usuario_usuario"] == $usuario && password_verify($clave, $check_user["usuario_clave"])){//hashea y compara con las claves guardadas

        //variables de sesion
            $_SESSION["id"]=$check_user["usuario_id"];
            $_SESSION["nombre"]=$check_user["usuario_nombre"];
            $_SESSION["apellido"]=$check_user["usuario_apellido"];
            $_SESSION["usuario"]=$check_user["usuario_usuario"];

            if(headers_sent()){//si ya se enviaron headers se redirecciona con js porque con php da errores.  
                echo '
                <script>
                    window.location.href="index.php?vista=home"
                </script>
                ';

            } else {
                header("Location: index.php?vista=home");
            }

    } else {
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            Usuario o clave incorrecto.
        </div>';
    }

 } else {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        Usuario o clave incorrecto.
    </div>';
 }

 $check_user=null;