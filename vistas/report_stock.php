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
    require_once("./php/main.php");
?>

<div class="container pb-6 pt-6">
	<div class="is-fluid mb-2">
		<h1 class="title">Reporte</h1>
		<h2 class="subtitle">Ventas Realizadas</h2>
	</div>

	<div class="form-rest mb-6 mt-6"></div>

	<form target="_blank" action="./php/reporte_stock.php" method="POST" autocomplete="off" enctype="multipart/form-data" >

        <div class="column is-narrow">
            <span>Filtro Categoria</span><br>
            <div class="select is-rounded">
                <select name="filtro_categoria" >
                    <option value="" selected="" >Sin filtro</option>
                    <?php
                        $cat = con();
                        $cat = $cat->query("SELECT * FROM categoria");
                        if($cat->rowCount()>0){
                            $cat = $cat->fetchAll();
                            foreach($cat as $key){
                                echo '<option value="'.$key['categoria_id'].'" >'.$key['categoria_nombre'].'</option>';
                            }
                        }
                        $cat=null;
                    ?>
                </select>
            </div>
        </div>

        <div class="column is-narrow">
            <span>Filtro Familia</span><br>
            <div class="select is-rounded">
                <select name="filtro_familia" >
                    <option value="" selected="" >Sin filtro</option>
                    <?php
                        $fam = con();
                        $fam = $fam->query("SELECT * FROM familia");
                        if($fam->rowCount()>0){
                            $fam = $fam->fetchAll();
                            foreach($fam as $key){
                                echo '<option value="'.$key['familia_id'].'" >'.$key['familia_nombre'].'</option>';
                            }
                        }
                        $fam=null;
                    ?>
                </select>
            </div>
        </div>

        <div class="column is-narrow">
            <span>Filtro Depósito</span><br>
            <div class="select is-rounded">
                <select name="filtro_deposito" >
                    <option value="" selected="" >Sin filtro</option>
                    <?php
                        $dep = con();
                        $dep = $dep->query("SELECT * FROM depositos");
                        if($dep->rowCount()>0){
                            $dep = $dep->fetchAll();
                            foreach($dep as $key){
                                echo '<option value="'.$key['deposito_id'].'" >'.$key['dep_nombre'].'</option>';
                            }
                        }
                        $dep=null;
                    ?>
                </select>
            </div>
        </div>

		<p class="has-text-centered">
			<button type="submit" class="button is-info is-rounded">Generar Reporte</button>
		</p>
	</form>
</div>