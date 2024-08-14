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
		<h1 class="title">Entidad Bancaria</h1>
		<h2 class="subtitle">Actualizar entidad bancaria</h2>
	</div>
<?php 
    include("./inc/btn_back.php");

	require_once("./php/main.php");

    $id=(isset($_GET["banco_id_upd"])) ? $_GET["banco_id_upd"] : 0;
    $id=limpiar_cadena($id);

    $check_banco = con();
    $check_banco = $check_banco->query("SELECT * FROM bancos WHERE banco_id = '$id'");

    if($check_banco->rowCount()>0){
        $datos=$check_banco->fetch();
?>

	<div class="form-rest mb-6 mt-6"></div>

	<form action="./php/banco_actualizar.php" method="POST" class="formularioAjax" autocomplete="off" >

		<input type="hidden" name="banco_id" value="<?php echo $datos["banco_id"];?>" required >

		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Nombre</label>
				  	<input class="input" type="text" name="nombre" required autofocus value="<?php echo $datos["ban_nombre"];?>">
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>RUC</label>
					<input class="input" type="text" name="ruc" autofocus value="<?php echo $datos["ban_ruc"];?>">
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Dirección</label>
					<input class="input" type="text" name="direccion" autofocus value="<?php echo $datos["ban_direccion"];?>">
				</div>
		  	</div>
		</div>
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Teléfono</label>
					<input class="input" type="text" name="telefono" autofocus value="<?php echo $datos["ban_telefono"];?>">
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Mail</label>
					<input class="input" type="text" name="mail" autofocus value="<?php echo $datos["ban_mail"];?>">
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
    $check_banco=null;
?>
</div>