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
		<h2 class="subtitle">Actualizar producto</h2>
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
	
	<h2 class="title has-text-centered"><?php echo $datos["producto_nombre"] ;?></h2>

	<form action="./php/producto_actualizar.php" method="POST" class="formularioAjax" autocomplete="off" >

		<input type="hidden" name="producto_id" required value="<?php echo $datos["producto_id"] ;?>" >

		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<span>Código de barra</span>
				  	<input class="input" type="text" name="producto_codigo" pattern="[a-zA-Z0-9- ]{1,70}" maxlength="70" required value="<?php echo $datos["producto_codigo"] ;?>" >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<span>Nombre</span>
				  	<input class="input" type="text" name="producto_nombre" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}" maxlength="70" required value="<?php echo $datos["producto_nombre"] ;?>" >
				</div>
		  	</div>
			<div class="column">
		    	<div class="control">
					<span>Precio</span>
				  	<input class="input" type="text" name="producto_precio" pattern="[0-9.]{1,25}" maxlength="25" required value="<?php 
                    //echo number_format($datos["producto_precio"], 0, ',', '.');se guarda como entero -> 9.000 y no 9000
					echo $datos["producto_precio"];
                    ?>" >
				</div>
		  	</div>
			<div class="column">
		    	<div class="control">
					<span>Precio Compra</span>
				  	<input class="input" type="text" name="producto_precio_compra" pattern="[0-9.]{1,25}" maxlength="25" required value="<?php 
                    //echo number_format($datos["producto_precio_compra"], 0, ',', '.');se guarda como entero -> 9.000 y no 9000
					echo $datos["producto_precio_compra"];
                    ?>" >
				</div>
		  	</div>
		</div>
		<div class="columns">
			<div class="column">
				<span>IVA</span><br>
		    	<div class="select is-rounded">
				  	<select name="producto_iva" >
						<?php
						$iva = (int)$datos["producto_iva"];
						switch ($iva) {
							case 5:
								echo '
								<option value="5" selected="">5 (Actual)</option>
								<option value="0">0</option>
								<option value="10">10</option>';
								break;
							case 10:
								echo '
								<option value="10" selected="">10 (Actual)</option>
								<option value="0">0</option>
								<option value="5">5</option>';
								break;
							case 0:
								echo '
								<option value="0" selected="">0 (Actual)</option>
								<option value="5">5</option>
								<option value="10">10</option>';
								break;
						}
						?>
				  	</select>
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<span>Stock</span>
				  	<input class="input" type="text" name="producto_stock" pattern="[0-9]{1,25}" maxlength="25" required value="<?php echo $datos["producto_stock"] ;?>" >
				</div>
		  	</div>
			<div class="column">
		    	<div class="control">
					<span>Stock mínimo</span>
				  	<input class="input" type="text" name="producto_stock_min" pattern="[0-9]{1,25}" maxlength="25" required value="<?php echo $datos["producto_stock_min"] ;?>" >
				</div>
		  	</div>
		</div>
		<div class="columns">
			<div class="column">
				<span>Categoría</span><br>
		    	<div class="select is-rounded">
				  	<select name="producto_categoria" >   
                        <?php
                        $categorias = con();
                        $categorias = $categorias->query("SELECT * FROM categoria");
                        if($categorias->rowCount()>0){
                            $categorias = $categorias->fetchAll();
                            foreach($categorias as $cat){
                                if($datos["categoria_id"] == $cat['categoria_id']){
                                    echo '
                                    <option value="'.$cat['categoria_id'].'" selected="" >'.$cat['categoria_nombre'].' (Actual)</option>
                                    ';
                                } else {
                                    echo '<option value="'.$cat['categoria_id'].'" >'.$cat['categoria_nombre'].'</option>';
                                }
                            }
                        }
                        $categorias=null;
                        ?>
				  	</select>
				</div>
		  	</div>
			<div class="column">
				<span>Familia</span><br>
		    	<div class="select is-rounded">
				  	<select name="producto_familia" >   
                        <?php
                        $familias = con();
                        $familias = $familias->query("SELECT * FROM familia");
                        if($familias->rowCount()>0){
                            $familias = $familias->fetchAll();
                            foreach($familias as $fam){
                                if($datos["familia_id"] == $fam['familia_id']){
                                    echo '
                                    <option value="'.$fam['familia_id'].'" selected="" >'.$fam['familia_nombre'].' (Actual)</option>
                                    ';
                                } else {
                                    echo '<option value="'.$fam['familia_id'].'" >'.$fam['familia_nombre'].'</option>';
                                }
                            }
                        }
                        $categorias=null;
                        ?>
				  	</select>
				</div>
		  	</div>
			<div class="column">
				<span>Proveedor</span><br>
		    	<div class="select is-rounded">
				  	<select name="producto_provee" >   
                        <?php
                        $proveedores = con();
                        $proveedores = $proveedores->query("SELECT * FROM proveedor");
                        if($proveedores->rowCount()>0){
                            $proveedores = $proveedores->fetchAll();
                            foreach($proveedores as $prov){
                                if($datos["prov_id"] == $prov['prov_id']){
                                    echo '
                                    <option value="'.$prov['prov_id'].'" selected="" >'.$prov['prov_nombre'].' (Actual)</option>
                                    ';
                                } else {
                                    echo '<option value="'.$prov['prov_id'].'" >'.$prov['prov_nombre'].'</option>';
                                }
                            }
                        }
                        $proveedores=null;
                        ?>
				  	</select>
				</div>
		  	</div>
			<div class="column">
				<span>Depósito</span><br>
		    	<div class="select is-rounded">
				  	<select name="producto_deposito" >   
                        <?php
                        $deposito = con();
                        $deposito = $deposito->query("SELECT * FROM depositos");
                        if($deposito->rowCount()>0){
                            $deposito = $deposito->fetchAll();
                            foreach($deposito as $dep){
                                if($datos["deposito_id"] == $dep['deposito_id']){
                                    echo '
                                    <option value="'.$dep['deposito_id'].'" selected="" >'.$dep['dep_nombre'].' (Actual)</option>
                                    ';
                                } else {
                                    echo '<option value="'.$dep['deposito_id'].'" >'.$dep['dep_nombre'].'</option>';
                                }
                            }
                        }
                        $deposito=null;
                        ?>
				  	</select>
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
    $check_producto=null;
?>

</div>