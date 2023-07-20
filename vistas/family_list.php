<div class="container is-fluid mb-6">
    <h1 class="title">Familia</h1>
    <h2 class="subtitle">Lista de familias</h2>
</div>

<div class="container pb-6 pt-6">
<?php 
    require_once("./php/main.php");

    //ELIMINAR CATEGORIAS
    if(isset($_GET["family_id_del"])){
        require_once("./php/familia_eliminar.php");
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
    $url= "index.php?vista=family_list&page=";
    $registros=10;//cantidad de registros por pagina
    $busqueda="";//de categorias
    require_once("./php/familia_lista.php");

?>

</div>