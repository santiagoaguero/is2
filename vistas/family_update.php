<div class="container is-fluid mb-6">
	<h1 class="title">Familia</h1>
	<h2 class="subtitle">Actualizar familia</h2>
</div>

<div class="container pb-6 pt-6">
<?php 
    include("./inc/btn_back.php");

	require_once("./php/main.php");

    $id=(isset($_GET["family_id_upd"])) ? $_GET["family_id_upd"] : 0;
    $id=limpiar_cadena($id);

    $check_familia = con();
    $check_familia = $check_familia->query("SELECT * FROM familia WHERE familia_id = '$id'");

    if($check_familia->rowCount()>0){
        $datos=$check_familia->fetch();
?>

	<div class="form-rest mb-6 mt-6"></div>

	<form action="./php/familia_actualizar.php" method="POST" class="formularioAjax" autocomplete="off" >

		<input type="hidden" name="familia_id" value="<?php echo $datos["familia_id"];?>" required >

		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Nombre</label>
				  	<input class="input" type="text" name="familia_nombre" value="<?php echo $datos["familia_nombre"];?>" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{4,50}" maxlength="50" required >
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
    $check_familia=null;
?>
</div>