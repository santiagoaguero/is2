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
        <h2 class="subtitle">Lista de productos por familia</h2>
    </div>
<?php 
    require_once("./php/main.php");
?>
    <div class="columns">

        <div class="column is-one-third">
            <h2 class="title has-text-centered">Familias</h2>
                <?php
                    $familias = con();
                    $familias = $familias->query("SELECT * FROM familia");
                    if($familias->rowCount()>0){
                        $familias = $familias->fetchAll();
                        foreach($familias as $fam){
                            echo '<a href="index.php?vista=product_family&family_id='.$fam["familia_id"].'" class="button is-link is-inverted is-fullwidth">'.$fam["familia_nombre"].'</a>';
                        }
                    } else {
                        echo '<p class="has-text-centered" >No hay familias registradas</p>';
                    }
                    $familias=null;
                    ?>
        </div>

        <div class="column">
            <?php 
            $familia_id = (isset($_GET["family_id"])) ? $_GET["family_id"] : 0;

            $familia = con();
            $familia = $familia->query("SELECT * FROM familia WHERE familia_id = '$familia_id'");
            if($familia->rowCount()>0){

                $familia = $familia->fetch();

                echo '
                <h2 class="title has-text-centered">'.$familia["familia_nombre"].'</h2>
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
                $url= "index.php?vista=product_family&family_id=$familia_id&page=";
                $registros=10;//cantidad de registros por pagina
                $busqueda="";//de categorias
                
                require_once("./php/producto_lista.php");

            } else {
                echo '<h2 class="has-text-centered title" >Seleccione una familia</h2>';
            }

            $familia=null;
            ?>


        </div>

    </div>
</div>