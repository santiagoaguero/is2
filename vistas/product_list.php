<div class="container is-fluid mb-0">
    <h1 class="title">Productos</h1>
    <h2 class="subtitle">Lista de productos</h2>

    <div class="cartel-stock">
        <p style="color: limegreen; font-weight:600">Stock alto: verde</p>
        <p style="color: orange; font-weight:600">Stock medio: amarillo</p>
        <p style="color: red; font-weight:600">Stock bajo: rojo </p>
    </div>
</div>

<div class="container pb-6 pt-6">


<?php 
    require_once("./php/main.php");

    //ELIMINAR PRODUCTOS
    if(isset($_GET["product_id_del"])){
        require_once("./php/producto_eliminar.php");
    }
    
    if(!isset($_GET["page"])){
        $pagina = 1;
    } else {
        $pagina = (int)$_GET["page"];
        if($pagina<=1){
            $pagina = 1;//controlar que siempre sea 1
        }
    }

    $categoria_id = (isset($_GET["category_id"])) ? $_GET["category_id"] : 0;
    $pagina = limpiar_cadena($pagina);
    $url= "index.php?vista=product_list&page=";
    $registros=10;//cantidad de registros por pagina
    $busqueda="";//de categorias
    require_once("./php/producto_lista.php");

?>


</div>