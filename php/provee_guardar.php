<?php
require_once("main.php");

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

//verifica email
// if($email != ""){
//     if(filter_var($email, FILTER_VALIDATE_EMAIL)){
//         $check_email=con();
//         $check_email=$check_email->query("SELECT cliente_email FROM clientes 
//         WHERE cliente_email = '$email'");//checks if email exists
//         if($check_email->rowCount()>0){//email found and emails gotta be unique
//             echo '
//             <div class="notification is-danger is-light">
//                 <strong>¡Ocurrió un error inesperado!</strong><br>
//                 El email ya está registrado en la base de datos, por favor elija otro email.
//             </div>';
//             exit();
//         }
//         $check_email=null;//close db connection
//     } else {
//         echo '
//         <div class="notification is-danger is-light">
//             <strong>¡Ocurrió un error inesperado!</strong><br>
//             El email no coincide con el formato esperado.
//         </div>';
//         exit();
//     }
// }

//verifica ruc unico
$check_ruc=con();
//query: inserta la consulta directo a la bd
$check_ruc=$check_ruc->query("SELECT prov_ruc FROM proveedor 
WHERE prov_ruc = '$ruc'");//checks if proveedor exists
if($check_ruc->rowCount()>0){//prov found
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        El RUC ya está registrado en la base de datos, por favor elija otro RUC.
    </div>';
    exit();
}
$check_ruc=null;//close db connection

//guardando datos
$guardar_prov = con();
//prepare: prepara la consulta antes de insertar directo a la bd. variables sin comillas ni $
$guardar_prov = $guardar_prov->prepare("INSERT INTO
    proveedor(prov_nombre, prov_ruc, prov_telefono, prov_direccion)
    VALUES(:nombre, :ruc, :telefono, :direccion)");

//evitando inyecciones sql xss
$marcadores=[":nombre"=>$nombre, ":ruc"=>$ruc, ":telefono"=>$telefono, ":direccion"=>$direccion];
$guardar_prov->execute($marcadores);

if($guardar_prov->rowCount()==1){// 1 prov nuevo insertado
    echo '
    <div class="notification is-success is-light">
        <strong>Cliente registrado!</strong><br>
        El proveedor se registró exitosamente.
    </div>';
} else {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No se pudo registrar el proveedor, inténtelo nuevamente.
    </div>';
}
$guardar_prov=null; //cerrar conexion;