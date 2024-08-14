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
        <h2 class="subtitle">Lista de productos por depósito</h2>
    </div>
<?php 
    require_once("./php/main.php");
?>
    <div class="columns">

        <div class="column is-one-third">
            <h2 class="title has-text-centered">Depósitos</h2>
                <?php
                    $depositos = con();
                    $depositos = $depositos->query("SELECT * FROM depositos");
                    if($depositos->rowCount()>0){
                        $depositos = $depositos->fetchAll();
                        foreach($depositos as $dep){
                            echo '<a href="index.php?vista=product_deposit&deposit_id='.$dep["deposito_id"].'" class="button is-link is-inverted is-fullwidth">'.$dep["dep_nombre"].'</a>';
                        }
                    } else {
                        echo '<p class="has-text-centered" >No hay depósitos registrados</p>';
                    }
                    $depositos=null;
                    ?>
        </div>

        <div class="column">
            <?php 
            $deposito_id = (isset($_GET["deposit_id"])) ? $_GET["deposit_id"] : 0;

            $deposito = con();
            $deposito = $deposito->query("SELECT * FROM depositos WHERE deposito_id = '$deposito_id'");
            if($deposito->rowCount()>0){

                $deposito = $deposito->fetch();

                echo '
                <h2 class="title has-text-centered">'.$deposito["dep_nombre"].'</h2>
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
                $url= "index.php?vista=product_deposit&deposit_id=$deposito_id&page=";
                $registros=10;//cantidad de registros por pagina
                $busqueda="";//de categorias
                
                require_once("./php/producto_lista.php");

            } else {
                echo '<h2 class="has-text-centered title" >Seleccione un depósito</h2>';
            }

            $deposito=null;
            ?>


        </div>

    </div>
</div>