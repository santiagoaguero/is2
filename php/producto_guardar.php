<?php
require_once("./inc/session_start.php");
require_once("main.php");

//almacenando datos
$codigo=limpiar_cadena($_POST["producto_codigo"]);
$nombre=limpiar_cadena($_POST["producto_nombre"]);
$precio=limpiar_cadena($_POST["producto_precio"]);
$stock=limpiar_cadena($_POST["producto_stock"]);
$categoria=limpiar_cadena($_POST["producto_categoria"]);

//verifica campos obligatorios
if($codigo == "" || $nombre == "" || $precio == "" || $stock == "" || $categoria == ""){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No has llenado todos los campos que son obligatorios
    </div>';
    exit();
}

//verifica integridad de los datos
if(verificar_datos("[a-zA-Z0-9- ]{1,70}",$codigo)){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        El código de barras no coincide con el formato esperado.
    </div>';
    exit();
}

if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}",$nombre)){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        El nombre no coincide con el formato esperado.
    </div>';
    exit();
}

if(verificar_datos("[0-9.]{1,25}",$precio)){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        El precio no coincide con el formato esperado.
    </div>';
    exit();
}

if(verificar_datos("[0-9]{1,25}",$stock)){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        El stock no coincide con el formato esperado.
    </div>';
    exit();
}

//verifica codigo de barras unico
$check_codigo=con();
$check_codigo=$check_codigo->query("SELECT producto_codigo FROM producto 
WHERE producto_codigo = '$codigo'");//checks if codigo exists
if($check_codigo->rowCount()>0){//codigos found
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        El código de barras ya está registrado en la base de datos, por favor elija otro código.
    </div>';
    exit();
}
$check_codigo=null;//close db connection

//verifica producto unico
$check_nombre=con();
$check_nombre=$check_nombre->query("SELECT producto_nombre FROM producto 
WHERE producto_nombre = '$nombre'");//checks if producto exists
if($check_nombre->rowCount()>0){//usuarios found
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        El producto ya está registrado en la base de datos, por favor elija otro nombre.
    </div>';
    exit();
}
$check_nombre=null;//close db connection

//verifica categoria exista
$check_categoria=con();
$check_categoria=$check_categoria->query("SELECT categoria_id FROM categoria 
WHERE categoria_id = '$categoria'");//checks if categoria exists
if($check_categoria->rowCount()<=0){//categorias found
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        La categoría seleccionada no existe.
    </div>';
    exit();
}
$check_categoria=null;//close db connection