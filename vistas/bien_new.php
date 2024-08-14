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
		<h1 class="title">Gestión de Bienes y Activos</h1>
		<h2 class="subtitle">Nuevo bien o activo</h2>
	</div>

	<div class="form-rest mb-6 mt-6"></div>

	<form action="./php/bien_guardar.php" method="POST" class="formularioAjax" autocomplete="off" >
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Nombre</label>
				  	<input class="input" type="text" name="nombre" required autofocus >
				</div>
		  	</div>
            <div class="column">
                <div class="control">
                    <label>Cantidad</label>
                    <input class="input" type="number" name="cantidad" required autofocus >
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Marca</label>
                    <input class="input" type="text" name="marca" autofocus >
                </div>
            </div>
		</div>
		<div class="columns">
            <div class="column">
                <div class="control">
                    <label>Modelo</label>
                    <input class="input" type="text" name="modelo" autofocus >
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Fecha de Adquisición</label>
                    <input class="input" type="date" name="fecha_adquisicion" required autofocus >
                </div>
            </div>
            <div class="column is-narrow">
                <span>Proveedor</span><br>
                <div class="select is-rounded">
                    <select name="proveedor" >
                        <?php
                            $prov = con();
                            $prov = $prov->query("SELECT * FROM proveedor");
                            if($prov->rowCount()>0){
                                $prov = $prov->fetchAll();
                                foreach($prov as $key){
                                    echo '<option value="'.$key['prov_id'].'" >'.$key['prov_nombre'].'</option>';
                                }
                            }
                            $prov=null;
                        ?>
                    </select>
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Ubicación</label>
                    <input class="input" type="text" name="ubicacion" autofocus>
                </div>
            </div>
		</div>
		<p class="has-text-centered">
			<button type="submit" class="button is-info is-rounded">Guardar</button>
		</p>
	</form>
</div>