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
		<h1 class="title">Forma de Pago</h1>
		<h2 class="subtitle">Actualizar Forma de Pago</h2>
	</div>
<?php 
    include("./inc/btn_back.php");

	require_once("./php/main.php");

    $id=(isset($_GET["forma_pago_id_upd"])) ? $_GET["forma_pago_id_upd"] : 0;
    $id=limpiar_cadena($id);

    $check_forma_pago = con();
    $check_forma_pago = $check_forma_pago->query("SELECT * FROM formas_pago WHERE forma_pago_id = '$id'");

    if($check_forma_pago->rowCount()>0){
        $datos=$check_forma_pago->fetch();
?>

	<div class="form-rest mb-6 mt-6"></div>

	<form action="./php/forma_pago_actualizar.php" method="POST" class="formularioAjax" autocomplete="off" >

		<input type="hidden" name="forma_pago_id" value="<?php echo $datos["forma_pago_id"];?>" required >

		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Nombre</label>
				  	<input class="input" type="text" name="forma_pago_descripcion" required autofocus value="<?php echo $datos["descripcion"];?>">
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
    $check_forma_pago=null;
?>
</div>