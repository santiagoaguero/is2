<?php // Verificar los permisos del usuario para esta página
	include("./inc/check_rol.php");
	if (isset($_SESSION['rol']) && isset($_GET['vista'])) {
		$vistaSolicitada = $_GET['vista'];
		$rolUsuario = $_SESSION['rol'];
	
		check_rol($vistaSolicitada, $rolUsuario);
		
	}
?>

<div class="container pb-6 pt-6">
    <div class="is-fluid mb-2">
        <h1 class="title">Facturas</h1>
        <h2 class="subtitle">Lista de facturas</h2>
    </div>

<?php 
    require_once("./php/main.php");

    //ELIMINAR PRODUCTOS
    if(isset($_GET["fact_nro"])){
        require_once("./php/factura_anular.php");
    }
    
    if(!isset($_GET["page"])){
        $pagina = 1;
    } else {
        $pagina = (int)$_GET["page"];
        if($pagina<=1){
            $pagina = 1;//controlar que siempre sea 1
        }
    }

    $fact_id = (isset($_GET["fact_id"])) ? $_GET["fact_id"] : 0;
    $pagina = limpiar_cadena($pagina);
    $url= "index.php?vista=factur_list&page=";
    $registros=10;//cantidad de registros por pagina
    $busqueda="";//de categorias
    require_once("./php/factura_lista.php");

?>


</div>