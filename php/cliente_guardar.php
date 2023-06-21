<!-- cliente_guardar -->
<?php
require_once("main.php");

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
// if(verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ ]{3,40}",$apellido)){
//     echo '
//     <div class="notification is-danger is-light">
//         <strong>¡Ocurrió un error inesperado!</strong><br>
//         El APELLIDO no coincide con el formato esperado.
//     </div>';
//     exit();
// }

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

//guardando datos
$guardar_cliente = con();
//prepare: prepara la consulta antes de insertar directo a la bd. variables sin comillas ni $
$guardar_cliente = $guardar_cliente->prepare("INSERT INTO
    clientes(cliente_nombre, cliente_apellido, cliente_ruc, cliente_email, cliente_telefono, cliente_direccion)
    VALUES(:nombre, :apellido, :ruc, :email, :telefono, :direccion)");

//evitando inyecciones sql xss
$marcadores=[
    ":nombre"=>$nombre, ":apellido"=>$apellido, ":ruc"=>$ruc, ":email"=>$email, ":telefono"=>$telefono, ":direccion"=>$direccion];

$guardar_cliente->execute($marcadores);

if($guardar_cliente->rowCount()==1){// 1 usuario nuevo insertado
    echo '
    <div class="notification is-success is-light">
        <strong>Cliente registrado!</strong><br>
        El cliente se registró exitosamente.
    </div>';
} else {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No se pudo registrar el cliente, inténtelo nuevamente.
    </div>';
}
$guardar_cliente=null; //cerrar conexion;