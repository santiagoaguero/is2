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
		<h1 class="title">Sucursal</h1>
		<h2 class="subtitle">Actualizar Sucursal</h2>
	</div>
<?php 
    include("./inc/btn_back.php");

	require_once("./php/main.php");

    $id=(isset($_GET["sucursal_id_upd"])) ? $_GET["sucursal_id_upd"] : 0;
    $id=limpiar_cadena($id);

    $check_sucursal = con();
    $check_sucursal = $check_sucursal->query("SELECT * FROM sucursales WHERE sucursal_id = '$id'");

    if($check_sucursal->rowCount()>0){
        $datos=$check_sucursal->fetch();
?>

	<div class="form-rest mb-6 mt-6"></div>

	<form action="./php/sucursal_actualizar.php" method="POST" class="formularioAjax" autocomplete="off" >

		<input type="hidden" name="sucursal_id" value="<?php echo $datos["sucursal_id"];?>" required >

		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Nombre</label>
				  	<input class="input" type="text" name="nombre" required autofocus value="<?php echo $datos["suc_nombre"];?>">
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Dirección</label>
					<input class="input" type="text" name="direccion" autofocus value="<?php echo $datos["suc_direccion"];?>">
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Estado</label><br/>
					<div class="select is-rounded">
						<select name="estado">
							<option value="1" <?php echo ($datos['suc_activo'] == '1') ? 'selected' : '' ?>>Activo</option>
							<option value="0" <?php echo ($datos['suc_activo'] == '0') ? 'selected' : '' ?>>Inactivo</option>
						</select>
					</div>
				</div>
		  	</div>
		</div>
		
		<p class="has-text-centered">
			<button type="submit" class="button is-success is-rounded">Actualizar</button>
		</p>
	</form>
	<?php 
    } else {
        include("./inc/error_alert.php");
    }
    $check_sucursal=null;
?>
</div>