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
<div class="container is-fluid mb-6">
	<h1 class="title">Proveedores</h1>
	<h2 class="subtitle">Actualizar proveedor</h2>
</div>

<div class="container pb-6 pt-6">
<?php 
    include("./inc/btn_back.php");

	require_once("./php/main.php");

    $id=(isset($_GET["prov_id_upd"])) ? $_GET["prov_id_upd"] : 0;
    $id=limpiar_cadena($id);

    $check_provee = con();
    $check_provee = $check_provee->query("SELECT * FROM proveedor WHERE prov_id = '$id'");

    if($check_provee->rowCount()>0){
        $datos=$check_provee->fetch();
?>

	<div class="form-rest mb-6 mt-6"></div>

	<form action="./php/provee_actualizar.php" method="POST" class="formularioAjax" autocomplete="off" >

		<input type="hidden" name="provee_id" value="<?php echo $datos["prov_id"];?>" required >

		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Nombre</label>
				  	<input class="input" type="text" name="provee_nombre" value="<?php echo $datos["prov_nombre"];?>" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ. ]{3,40}" maxlength="40" required >
				</div>
		  	</div>
		  	<div class="column">
                <div class="control">
					<label>RUC</label>
					  <input class="input" type="text" name="provee_ruc" value="<?php echo $datos["prov_ruc"];?>" pattern="[a-zA-Z0-9.-]{4,12}" maxlength="11" required >
				</div>
			  </div>
		</div>
        <div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Teléfono</label>
				  	<input class="input" type="text" name="provee_telefono" value="<?php echo $datos["prov_telefono"];?>" pattern="[0-9- ]{6,30}" maxlength="30" required>
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Dirección</label>
				  	<input class="input" type="text" name="provee_direccion" value="<?php echo $datos["prov_direccion"];?>" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ.- ]{3,255}" maxlength="255" >
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
    $check_provee=null;
?>
</div>