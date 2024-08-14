<?php // Verificar los permisos del usuario para esta página
	include("./inc/check_rol.php");
	if (isset($_SESSION['rol']) && isset($_GET['vista'])) {
		$vistaSolicitada = $_GET['vista'];
		$rolUsuario = $_SESSION['rol'];
	
		check_rol($vistaSolicitada, $rolUsuario);
		
	} else {
        header("Location: login.php");
        exit();
    }
?>

<div class="container pb-6 pt-6">
    <div class="is-fluid mb-2">
        <h1 class="title">Productos</h1>
        <h2 class="subtitle">Lista de productos por categoría</h2>
    </div>
<?php 
    require_once("./php/main.php");
?>
    <div class="columns">

        <div class="column is-one-third">
            <h2 class="title has-text-centered">Categorías</h2>
                <?php
                    $categorias = con();
                    $categorias = $categorias->query("SELECT * FROM categoria");
                    if($categorias->rowCount()>0){
                        $categorias = $categorias->fetchAll();
                        foreach($categorias as $cat){
                            echo '<a href="index.php?vista=product_category&category_id='.$cat["categoria_id"].'" class="button is-link is-inverted is-fullwidth">'.$cat["categoria_nombre"].'</a>';
                        }
                    } else {
                        echo '<p class="has-text-centered" >No hay categorías registradas</p>';
                    }
                    $categorias=null;
                    ?>
        </div>

        <div class="column">
            <?php 
            $categoria_id = (isset($_GET["category_id"])) ? $_GET["category_id"] : 0;

            $categoria = con();
            $categoria = $categoria->query("SELECT * FROM categoria WHERE categoria_id = '$categoria_id'");
            if($categoria->rowCount()>0){

                $categoria = $categoria->fetch();

                echo '
                <h2 class="title has-text-centered">'.$categoria["categoria_nombre"].'</h2>
                <p class="has-text-centered pb-6" >'.$categoria["categoria_ubicacion"].'</p>
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
                $url= "index.php?vista=product_category&category_id=$categoria_id&page=";
                $registros=15;//cantidad de registros por pagina
                $busqueda="";//de categorias
                
                require_once("./php/producto_lista.php");

            } else {
                echo '<h2 class="has-text-centered title" >Seleccione una categoría</h2>';
            }

            $categoria=null;
            ?>


        </div>

    </div>
</div>