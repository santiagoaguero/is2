<?php
require_once("../inc/session_start.php");
require_once("main.php");

//almacenando datos
$codigo=limpiar_cadena($_POST["producto_codigo"]);
$nombre=limpiar_cadena($_POST["producto_nombre"]);
$precio=limpiar_cadena($_POST["producto_precio"]);
$precio_compra=limpiar_cadena($_POST["producto_precio_compra"]);
$iva=limpiar_cadena($_POST["producto_iva"]);
$stock=limpiar_cadena($_POST["producto_stock"]);
$stock_min=limpiar_cadena($_POST["producto_stock_min"]);
$categoria=limpiar_cadena($_POST["producto_categoria"]);
$proveedor=limpiar_cadena($_POST["producto_provee"]);
$familia=limpiar_cadena($_POST["producto_familia"]);

//verifica campos obligatorios
if($codigo == "" || $nombre == "" || $precio == "" || $stock == "" || $categoria == "" || $proveedor == ""){
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

//verifica proveedor exista
$check_provee=con();
$check_provee=$check_provee->query("SELECT prov_id FROM proveedor 
WHERE prov_id = '$proveedor'");//checks if proveedor exists
if($check_provee->rowCount()<=0){//proveedor found
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        El proveedor seleccionado no existe.
    </div>';
    exit();
}
$check_provee=null;//close db connection

//directorio imagenes productos
$img_dir = "../img/productos/";

//comprueba si se selecciono una img
if($_FILES["producto_foto"]["name"] != "" && $_FILES["producto_foto"]["size"]>0){
    //crea dir
    if(!file_exists($img_dir)){
        if(!mkdir($img_dir, 0777)){//crea carpeta con permisos de lectura y escritura
            echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                No se pudo crear el directorio.
            </div>';
            exit();
        }
    }
    //verifica formato imgs
    if(mime_content_type($_FILES["producto_foto"]["tmp_name"]) != "image/jpeg" && mime_content_type($_FILES["producto_foto"]["tmp_name"]) != "image/png"){//verifica formato de archivo
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            Formato de archivo no permitido.
        </div>';
        exit();
    }
    //verifica tamaño de img
    if(($_FILES["producto_foto"]["size"] / 1024) > 3072){//mb to kb
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            Tamaño de archivo supera el permitido.
        </div>';
        exit();
    }
    //verifica extension img
    switch(mime_content_type($_FILES["producto_foto"]["tmp_name"])){
        case "image/jpeg":
            $img_ext = ".jpg";
            break;
        case "image/png":
            $img_ext = ".png";
            break;
    }

    chmod($img_dir, 0777);//permisos de escritura y lectura
    $img_nombre = renombrar_fotos($nombre);
    $foto = $img_nombre.$img_ext;

    //mueve la img del form al directorio
    if(!move_uploaded_file($_FILES["producto_foto"]["tmp_name"], $img_dir.$foto)){
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            No se pudo guardar la imagen, inténtelo nuevamente
        </div>';
        exit();
    }
} else {
    $foto = "";
}

//guardando datos
$guardar_producto = con();
//prepare: prepara la consulta antes de insertar directo a la bd. variables sin comillas ni $
$guardar_producto = $guardar_producto->prepare("INSERT INTO
    producto (producto_codigo, producto_nombre, producto_precio, producto_precio_compra, producto_iva, producto_stock, producto_stock_min, producto_foto, categoria_id, usuario_id, prov_id, familia_id)
    VALUES(:codigo, :nombre, :precio, :precio_compra, :iva, :stock, :stock_min, :foto, :categoria, :usuario, :proveedor, :familia)");

$marcadores=[
    "codigo"=>$codigo,
    "nombre"=>$nombre,
    "precio"=>$precio,
    "precio_compra"=>$precio_compra,
    "iva"=>$iva,
    "stock"=>$stock,
    "stock_min"=>$stock_min,
    "foto"=>$foto,
    "categoria"=>$categoria,
    "usuario"=>$_SESSION["id"],
    "proveedor"=>$proveedor,
    "familia"=>$familia
];

$guardar_producto->execute($marcadores);

if($guardar_producto->rowCount()==1){// 1 producto nuevo insertado
    echo '
    <div class="notification is-success is-light">
        <strong>Producto registrado!</strong><br>
        El producto se registró exitosamente.
    </div>';
} else {
    if(is_file($img_dir.$foto)){
        chmod($img_dir.$foto, 0777);//permisos de escritura y lectura
        unlink($img_dir.$foto);//borrar img antes de borrar producto
    }
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No se pudo registrar el producto, intentelo nuevamente.
    </div>';
}
$guardar_producto=null; //cerrar conexion;