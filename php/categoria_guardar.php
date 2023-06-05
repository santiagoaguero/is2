<?php
include_once("main.php");

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

//verifica nombre categoria unico
$check_nombre=con();
//query: inserta la consulta directo a la bd
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

//guardando datos
$guardar_categoria = con();
//prepare: prepara la consulta antes de insertar directo a la bd. variables sin comillas ni $
$guardar_categoria = $guardar_categoria->prepare("INSERT INTO
    categoria(categoria_nombre, categoria_ubicacion) VALUES(:nombre, :ubicacion)");

$marcadores=[
    ":nombre"=>$nombre, ":ubicacion"=>$ubicacion];

$guardar_categoria->execute($marcadores);
if($guardar_categoria->rowCount()==1){//1 fila afectada
    echo '
    <div class="notification is-success is-light">
        <strong>Categoría registrada!</strong><br>
        La categoría se registró exitosamente.
    </div>';
} else {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No se pudo registrar la categoría, inténtelo nuevamente.
    </div>';
}
$guardar_categoria=null;