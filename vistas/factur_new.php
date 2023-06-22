<div class="container is-fluid mb-6">
    <h1 class="title">Facturas</h1>
    <h2 class="subtitle">Nueva Factura</h2>
</div>

<div class="container pb-6 pt-6">
<?php 
    require_once("./php/main.php");
?>
    <div class="columns">

        <div class="column is-one-third">
            <p class="subtitle has-text-centered">Agregar productos</p>
                <?php

				include("product_factur_search.php");
                    // $productos = con();
                    // $productos = $productos->query("SELECT * FROM producto");
                    // if($productos->rowCount()>0){
                    //     $productos = $productos->fetchAll();
                    //     foreach($productos as $prod){
                    //         echo '
					// 		<p>'.$prod["producto_nombre"].' '.$prod["producto_precio"].'</p>
					// 		<a href="index.php?vista=factur_new&product_id='.$prod["producto_id"].'" class="button is-link is-inverted is-fullwidth">Agregar</a>';
                    //     }
                    // } else {
                    //     echo '<p class="has-text-centered" >No hay productos registradas</p>';
                    // }
                    // $productos=null;
                    ?>
        </div>

        <div class="box column">
            <?php 
            $producto_id = (isset($_GET["product_id"])) ? $_GET["product_id"] : 0;

            $producto = con();
            $producto = $producto->query("SELECT * FROM producto WHERE producto_id = '$producto_id'");
            if($producto->rowCount()>0){

                $producto = $producto->fetch();

                echo '
                <h2 class="title has-text-centered">'.$producto["producto_nombre"].'</h2>
                <p class="has-text-centered pb-6" >'.$producto["producto_precio"].'</p>
                ';

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

                $pagina = limpiar_cadena($pagina);
                $url= "index.php?vista=factur_new&product_id=$producto_id&page=";
                $registros=15;//cantidad de registros por pagina
                $busqueda="";//de categorias
                
                require_once("./php/producto_factura.php");

            } else {
                echo '<h2 class="has-text-centered title" >Seleccione una producto</h2>';
            }

            $producto=null;
            ?>


        </div>

    </div>
</div>
