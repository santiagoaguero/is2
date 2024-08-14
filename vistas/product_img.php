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
		<h1 class="title">Productos</h1>
		<h2 class="subtitle">Actualizar imagen de producto</h2>
	</div>
<?php 
    include("./inc/btn_back.php");

	require_once("./php/main.php");

    $id=(isset($_GET["product_id_upd"])) ? $_GET["product_id_upd"] : 0;
    $id=limpiar_cadena($id);

    $check_producto = con();
    $check_producto = $check_producto->query("SELECT * FROM producto WHERE producto_id = '$id'");

    if($check_producto->rowCount()>0){
        $datos=$check_producto->fetch();
?>
	<div class="form-rest mb-6 mt-6"></div>

	<div class="columns">
		<div class="column is-two-fifths">
        <?php 
            if(is_file("./img/productos/".$datos["producto_foto"])){

        ?>
			<figure class="image mb-6">
                <img src="./img/productos/<?php echo $datos['producto_foto'];?>">
			</figure>
			<form class="formularioAjax" action="./php/producto_img_eliminar.php" method="POST" autocomplete="off" >

				<input type="hidden" name="img_del_id" value="<?php echo $datos['producto_id'];?>">

				<p class="has-text-centered">
					<button type="submit" class="button is-danger is-rounded">Eliminar imagen</button>
				</p>
			</form>
		<?php
                } else {
        ?>
			<figure class="image mb-6">
			  	<img src="./img/productos.png">
			</figure>
            <?php
                }
        ?>
		</div>

		<div class="column">
			<form class="mb-6 has-text-centered formularioAjax" action="./php/producto_img_actualizar.php" method="POST" enctype="multipart/form-data" autocomplete="off" >

				<h4 class="title is-4 mb-6"><?php echo $datos['producto_nombre'];?></h4>
				
				<label>Foto o imagen del producto</label><br>

				<input type="hidden" name="img_up_id" value="<?php echo $datos['producto_id'];?>">

				<div class="file has-name is-horizontal is-justify-content-center mb-6">
				  	<label class="file-label">
				    	<input class="file-input" type="file" name="producto_foto" accept=".jpg, .png, .jpeg" >
				    	<span class="file-cta">
				      		<span class="file-label">Imagen</span>
				    	</span>
				    	<span class="file-name">JPG, JPEG, PNG. (MAX 3MB)</span>
				  	</label>
				</div>
				<p class="has-text-centered">
					<button type="submit" class="button is-success is-rounded">Actualizar</button>
				</p>
			</form>
		</div>
	</div>

<?php 
    } else {
        include("./inc/error_alert.php");
    }
    $check_producto=null;
?>

</div>