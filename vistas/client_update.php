<div class="container is-fluid mb-6">
	<h1 class="title">Clientes</h1>
	<h2 class="subtitle">Actualizar cliente</h2>
</div>

<div class="container pb-6 pt-6">
<?php 
    include("./inc/btn_back.php");

	require_once("./php/main.php");

    $id=(isset($_GET["client_id_upd"])) ? $_GET["client_id_upd"] : 0;
    $id=limpiar_cadena($id);

    $check_client = con();
    $check_client = $check_client->query("SELECT * FROM clientes WHERE cliente_id = '$id'");

    if($check_client->rowCount()>0){
        $datos=$check_client->fetch();
?>

	<div class="form-rest mb-6 mt-6"></div>

	<form action="./php/cliente_actualizar.php" method="POST" class="formularioAjax" autocomplete="off" >

		<input type="hidden" name="cliente_id" value="<?php echo $datos["cliente_id"];?>" required >

		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Nombre</label>
				  	<input class="input" type="text" name="cliente_nombre" value="<?php echo $datos["cliente_nombre"];?>" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ. ]{3,40}" maxlength="40" required >
				</div>
		  	</div>
			<div class="column">
		    	<div class="control">
					<label>RUC</label>
				  	<input class="input" type="text" name="cliente_ruc" value="<?php echo $datos["cliente_ruc"];?>" pattern="^[0-9a-zA-Z.\-]{4,12}$" maxlength="11" required >
				</div>
		  	</div>
		</div>
		<div class="columns">
		<div class="column">
		    	<div class="control">
					<label>Teléfono</label>
				  	<input class="input" type="text" name="cliente_telefono" value="<?php echo $datos["cliente_telefono"];?>" pattern="[0-9 -]{6,30}" maxlength="30" >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Email</label>
				  	<input class="input" type="email" name="cliente_email" value="<?php echo $datos["cliente_email"];?>" maxlength="70" >
				</div>
		  	</div>
		</div>
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Dirección</label>
				  	<input class="input" type="text" name="cliente_direccion" value="<?php echo $datos["cliente_direccion"];?>" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ.- ]{3,255}" maxlength="255" >
				</div>
		  	</div>
			<div class="column">
		    	<div class="control">
					<label>Contacto</label>
				  	<input class="input" type="text" name="cliente_contacto" value="<?php echo $datos["cliente_contacto"];?>" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ ]{0,40}" maxlength="40" >
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