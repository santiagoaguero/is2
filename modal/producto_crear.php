<div id="modalCrearProducto" class="modal modal-fx-slideTop">
	<div class="modal-background"></div>
	<div class="modal-content">
		<div class="box">
			<p class="subtitle has-text-centered mb-2">Crear producto</p>
			<form action="./php/producto_guardar.php" method="POST" class="formularioAjax" autocomplete="off" enctype="multipart/form-data">
				<div class="columns">
		  			<div class="column">
		    			<div class="control">
							<span>Código de barra</span>
				  			<input class="input" type="text" name="producto_codigo" pattern="[a-zA-Z0-9- ]{1,70}" maxlength="70" required >
						</div>
		  			</div>
		  			<div class="column">
		    			<div class="control">
							<span>Nombre</span>
				  			<input class="input" type="text" name="producto_nombre" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}" maxlength="70" required >
						</div>
		  			</div>
				</div>
				<div class="columns">
		  			<div class="column">
		    			<div class="control">
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
		  			<div class="column">
		    			<div class="control">
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
		  			</div>
				</div>
				<div class="columns">
					<div class="column">
		    			<div class="control">
							<span>Precio</span>
				  			<input class="input" type="text" name="producto_precio" pattern="[0-9.]{1,25}" maxlength="25" required >
						</div>
		  			</div>
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
				</div>
				<div class="columns">
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
	</div>
</div>