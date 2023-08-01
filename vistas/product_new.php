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
	<h1 class="title">Productos</h1>
	<h2 class="subtitle">Nuevo producto</h2>
</div>

<div class="container pb-6 pt-6">
    <?php 
    require_once("./php/main.php");
    ?>

	<div class="form-rest mb-6 mt-6"></div>

	<form action="./php/producto_guardar.php" method="POST" class="formularioAjax" autocomplete="off" enctype="multipart/form-data" >
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<span>Código de barra</span>
				  	<input class="input" type="text" name="producto_codigo" pattern="[a-zA-Z0-9- ]{1,70}" maxlength="70" required autofocus>
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<span>Nombre</span>
				  	<input class="input" type="text" name="producto_nombre" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}" maxlength="70" required >
				</div>
		  	</div>
			<div class="column">
		    	<div class="control">
					<span>Precio</span>
				  	<input class="input" type="text" name="producto_precio" pattern="[0-9.]{1,25}" maxlength="25" required >
				</div>
		  	</div>
		</div>
		<div class="columns">
			<div class="column">
				<span>IVA</span><br>
		    	<div class="select is-rounded">
				  	<select name="producto_iva" >
				    	<option value="" selected="" >Seleccione una opción</option>
						<option value="0">0</option>
						<option value="5">5</option>
						<option value="10">10</option>
				  	</select>
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<span>Stock</span>
				  	<input class="input" type="text" name="producto_stock" pattern="[0-9]{1,25}" maxlength="25" required >
				</div>
		  	</div>
			<div class="column">
		    	<div class="control">
					<span>Stock mínimo</span>
				  	<input class="input" type="text" name="producto_stock_min" pattern="[0-9]{1,25}" maxlength="25" required >
				</div>
		  	</div>
		</div>
		<div class="columns">
			<div class="column">
				<span>Categoría</span><br>
		    	<div class="select is-rounded">
				  	<select name="producto_categoria" >
				    	<option value="" selected="" >Seleccione una opción</option>
                        <?php
                        $categorias = con();
                        $categorias = $categorias->query("SELECT * FROM categoria");
                        if($categorias->rowCount()>0){
                            $categorias = $categorias->fetchAll();
                            foreach($categorias as $cat){
                                echo '<option value="'.$cat['categoria_id'].'" >'.$cat['categoria_nombre'].'</option>';
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
				    	<option value="" selected="" >Seleccione una opción</option>
                        <?php
                        $familia = con();
                        $familia = $familia->query("SELECT * FROM familia");
                        if($familia->rowCount()>0){
                            $familia = $familia->fetchAll();
                            foreach($familia as $fam){
                                echo '<option value="'.$fam['familia_id'].'" >'.$fam['familia_nombre'].'</option>';
                            }
                        }
                        $familia=null;
                        ?>
				  	</select>
				</div>
		  	</div>
			<div class="column">
				<span>Proveedor</span><br>
		    	<div class="select is-rounded">
				  	<select name="producto_provee" >
				    	<option value="" selected="" >Seleccione una opción</option>
                        <?php
                        $proveedores = con();
                        $proveedores = $proveedores->query("SELECT * FROM proveedor");
                        if($proveedores->rowCount()>0){
                            $proveedores = $proveedores->fetchAll();
                            foreach($proveedores as $prov){
                                echo '<option value="'.$prov['prov_id'].'" >'.$prov['prov_nombre'].'</option>';
                            }
                        }
                        $proveedores=null;
                        ?>
				  	</select>
				</div>
		  	</div>

		</div>
		<div class="columns">
			<div class="column">
				<span>Foto o imagen del producto</span><br>
				<div class="file is-small has-name">
				  	<label class="file-label">
				    	<input class="file-input" type="file" name="producto_foto" accept=".jpg, .png, .jpeg" >
				    	<span class="file-cta">
				      		<span class="file-label">Imagen</span>
				    	</span>
				    	<span class="file-name">JPG, JPEG, PNG. (MAX 3MB)</span>
				  	</label>
				</div>
			</div>
		</div>
		<p class="has-text-centered">
			<button type="submit" class="button is-info is-rounded">Guardar</button>
		</p>
	</form>
</div>