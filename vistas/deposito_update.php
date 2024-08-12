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
		<h1 class="title">Depósito</h1>
		<h2 class="subtitle">Actualizar datos del depósito</h2>
	</div>
<?php 
    include("./inc/btn_back.php");

	require_once("./php/main.php");

    $id=(isset($_GET["deposito_id_upd"])) ? $_GET["deposito_id_upd"] : 0;
    $id=limpiar_cadena($id);

    $check_deposito = con();
    $check_deposito = $check_deposito->query("SELECT * FROM depositos WHERE deposito_id = '$id'");

    if($check_deposito->rowCount()>0){
        $datos=$check_deposito->fetch();
?>

	<div class="form-rest mb-6 mt-6"></div>

	<form action="./php/deposito_actualizar.php" method="POST" class="formularioAjax" autocomplete="off" >

		<input type="hidden" name="deposito_id" value="<?php echo $datos["deposito_id"];?>" required >

		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Nombre</label>
				  	<input class="input" type="text" name="nombre" required autofocus value="<?php echo $datos["dep_nombre"];?>">
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
    $check_deposito=null;
?>
</div>