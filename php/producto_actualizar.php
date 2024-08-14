<?php
require_once("main.php");

$id = limpiar_cadena($_POST["producto_id"]);//input hidden

//verificar en bd
$check_producto = con();
$check_producto = $check_producto->query("SELECT * FROM producto WHERE producto_id = '$id'");

if($check_producto->rowCount()<=0){//no existe id
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No se encontró el producto.
    </div>';
    exit();
} else {
    $datos = $check_producto->fetch();
}
$check_producto=null;

//almacenando datos
$codigo=limpiar_cadena($_POST["producto_codigo"]);
$nombre=limpiar_cadena($_POST["producto_nombre"]);
$precio=limpiar_cadena($_POST["producto_precio"]);
$precio_compra=limpiar_cadena($_POST["producto_precio_compra"]);
$iva=limpiar_cadena($_POST["producto_iva"]);
$stock=limpiar_cadena($_POST["producto_stock"]);
$stock_min=limpiar_cadena($_POST["producto_stock_min"]);
$categoria=limpiar_cadena($_POST["producto_categoria"]);
$familia=limpiar_cadena($_POST["producto_familia"]);
$proveedor=limpiar_cadena($_POST["producto_provee"]);
$deposito=limpiar_cadena($_POST["producto_deposito"]);

//verifica campos obligatorios
if($codigo == "" || $nombre == "" || $precio == "" || $stock == "" || $categoria == "" || $proveedor == "" || $familia == "" || $deposito == ""){
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

if ($precio_compra == "") {
    $precio_compra = 0;
}

if(verificar_datos("[0-9]{1,25}",$stock)){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        El stock no coincide con el formato esperado.
    </div>';
    exit();
}

if(verificar_datos("[0-9]{1,25}",$stock_min)){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        El stock no coincide con el formato esperado.
    </div>';
    exit();
}

if($iva != 0 && $iva != 5 && $iva != 10){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        Tipo de IVA no admitido.'.var_dump($iva).'
    </div>';
    exit();
}

//verifica codigo de barras sea unico con este producto o con otros 
if($codigo != $datos["producto_codigo"]){
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
}

//verifica nombre sea unico con este producto o con otros 
if($nombre != $datos["producto_nombre"]){
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
}

//verifica categoria sea unico con este producto o con otros 
if($categoria != $datos["categoria_id"]){
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
}

//verifica familia exista 
if($familia != $datos["familia_id"]){
    $check_familia=con();
    $check_familia=$check_familia->query("SELECT familia_id FROM familia 
    WHERE familia_id = '$familia'");//checks if familia exists
    if($check_familia->rowCount()<=0){//categorias found
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            La familia seleccionada no existe.
        </div>';
        exit();
    }
    $check_familia=null;//close db connection
}

//verifica proveedor exita 
if($proveedor != $datos["prov_id"]){
    $check_provee=con();
    $check_provee=$check_provee->query("SELECT prov_id FROM proveedor 
    WHERE prov_id = '$proveedor'");//checks if categoria exists
    if($check_provee->rowCount()<=0){//categorias found
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            El proveedor seleccionado no existe.
        </div>';
        exit();
    }
    $check_provee=null;//close db connection
}

//Actualizando datos
$actualizar_producto = con();
$actualizar_producto = $actualizar_producto->prepare("UPDATE producto SET 
producto_codigo = :codigo, producto_nombre = :nombre, producto_precio = :precio, producto_precio_compra = :precio_compra, producto_iva = :iva, producto_stock = :stock, producto_stock_min = :stock_min, categoria_id = :categoria, prov_id = :proveedor, familia_id = :familia, deposito_id = :deposito WHERE producto_id = :id");

$marcadores=[
    "codigo"=>$codigo,
    "nombre"=>$nombre,
    "precio"=>$precio,
    "precio_compra"=>$precio_compra,
    "iva"=>$iva,
    "stock"=>$stock,
    "stock_min"=>$stock_min,
    "categoria"=>$categoria,
    "id"=>$id,
    "proveedor"=>$proveedor,
    "familia"=> $familia,
    "deposito"=> $deposito
];

if($actualizar_producto->execute($marcadores)){
    echo '
    <div class="notification is-success is-light">
        <strong>¡Producto actualizado!</strong><br>
        El producto se actualizó exitosamente.
    </div>';
} else {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No se pudo actualizar el producto, inténtelo nuevamente.
    </div>';   
}

$actualizar_producto=null;