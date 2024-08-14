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
		<h1 class="title">Timbrado</h1>
		<h2 class="subtitle">Actualizar Timbrado</h2>
	</div>
<?php 
    include("./inc/btn_back.php");

	require_once("./php/main.php");

    $id=(isset($_GET["timbrado_id_upd"])) ? $_GET["timbrado_id_upd"] : 0;
    $id=limpiar_cadena($id);

    $check_timbrado = con();
    $check_timbrado = $check_timbrado->query("SELECT * FROM timbrados WHERE timbrado_id = '$id'");

    if($check_timbrado->rowCount()>0){
        $datos=$check_timbrado->fetch();
?>

	<div class="form-rest mb-6 mt-6"></div>

	<form action="./php/timbrado_actualizar.php" method="POST" class="formularioAjax" autocomplete="off" >

		<input type="hidden" name="timbrado_id" value="<?php echo $datos["timbrado_id"];?>" required >

		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Número</label>
				  	<input class="input" type="text" name="timbrado_numero" required autofocus value="<?php echo $datos["numero"];?>" >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Fecha de Emisión</label>
				  	<input class="input" type="date" name="timbrado_fecha_emision" required autofocus value="<?php echo substr($datos["fecha_emision"], 0, 10);?>" >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Fecha de Vencimiento</label>
				  	<input class="input" type="date" name="timbrado_fecha_vencimiento" required autofocus value="<?php echo substr($datos["fecha_vencimiento"], 0, 10);?>" >
				</div>
		  	</div>
		</div>
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Estado</label><br/>
					<div class="select is-rounded">
						<select name="timbrado_estado">
							<option value="1" <?php echo ($datos['estado'] == '1') ? 'selected' : '' ?>>Activo</option>
							<option value="0" <?php echo ($datos['estado'] == '0') ? 'selected' : '' ?>>Inactivo</option>
						</select>
					</div>
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Descripción</label>
				  	<input class="input" type="text" name="timbrado_descripcion" autofocus placeholder="(Opcional)" value="<?php echo $datos["descripcion"];?>">
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
    $check_timbrado=null;
?>
</div>