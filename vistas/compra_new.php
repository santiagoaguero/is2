<div class="container is-fluid mb-6">
	<h1 class="title">Compras</h1>
	<h2 class="subtitle">Nueva Compra</h2>
</div>

<div class="container pb-6 pt-6">

	<div class="form-rest mb-6 mt-6"></div><!--to show notifications -->

	<form action="./php/compra_guardar.php" method="POST" class="formularioAjax" id="formul" autocomplete="off" >
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<span>Fecha</span>
				  	<input class="input" type="date" name="compra_fecha" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ. ]{3,40}" maxlength="40" required >
				</div>
		  	</div>
			<div class="column">
				<div class="control">
					<span>Proveedor</span>
					<p class="control has-icons-left">
                    	<input class="input mb-2" type="text" name="busca_provee" id="busca_provee" placeholder="Proveedor" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ .]{1,30}" maxlength="30" autofocus autocomplete="off">
						<span class="icon is-small is-left">
                            <i class="fas fa-search"></i>
                        </span>
                    </p>

                    <span id="lista_proveedores"></span>
                </div>
		  	</div>
			<div class="column">
		    	<div class="control">
					<span>RUC</span>
				  	<input class="input" type="text" name="provee_ruc" id="provee_ruc" pattern="^[0-9\-]{6,15}$" maxlength="11" required readonly>
				</div>
		  	</div>

			<div class="column">
		    	<div class="control">
					<span>Factura</span>
				  	<input class="input" type="text" name="compra_factura" pattern="[0-9 -]{6,30}" maxlength="30" >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<span>Condición</span>
					<div class="select is-rounded">
						<select name="compra_condicion" >
							<option value="" selected="" >Seleccione una opción</option>
							<option value="0">Contado</option>
							<option value="1">Crédito</option>
						</select>
					</div>
				</div>
		  	</div>
		</div>
		<div class="columns">
			<div class="column">
				<p class="subtitle has-text-centered mb-2">Agregar productos</p>

				<div class="control">
					<input class="input is-rounded mb-3" type="text" name="campo" id="campo" placeholder="¿Qué estas buscando?" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" maxlength="30" >
					<span id="lista_productos"></span>
				</div>

			</div>
		</div>
		<div class="columns">
			<div class="column">
				<table id="tabla-productos-seleccionados" class="table is-fullwidth" name="compra_prod">
					<thead>
						<tr>
							<th class="has-text-centered">Cód.</th>
							<th class="has-text-centered">Producto</th>
							<th class="has-text-centered">Cantidad</th>
							<th class="has-text-centered">Precio Unitario</th>
							<th class="has-text-centered">IVA</th>
							<th class="has-text-centered">Total</th>
							<th class="has-text-centered"></th>
						</tr>
					</thead>
				</table>
				<br>
				<h3>Total de Compra: <span id="total-compra">0</span></h3>
			</div>
    	</div>
		<p class="has-text-centered">
			<button type="submit" class="button is-info is-rounded">Guardar</button>
		</p>
	</form>
</div>
<script>
	function seleccionarProducto(id, nombre, precio, iva) {
		var cantidadInput = document.querySelector('input[name="cantidad_producto' + id + '"]');
		var cantidad = cantidadInput.value;
		if (cantidad > 0) {
			var total = cantidad * precio;

			var tablaProductosSeleccionados = document.getElementById('tabla-productos-seleccionados');
			var fila = document.createElement('tr');
			fila.innerHTML = 
							'<td class="has-text-centered">' + id + '</td>' +
							'<td class="has-text-centered">' + nombre + '</td>' +
								'<td class="has-text-centered">' + cantidad + '</td>' +
								'<td class="has-text-centered">' + precio + '</td>' +
								'<td class="has-text-centered">' + iva + '</td>' +
								'<td class="has-text-centered">' + total + '</td>' +
								'<td class="has-text-centered"><button onclick="quitarProducto(this)" class="button is-danger is-outlined is-small is-rounded">Quitar</button></td>';
			tablaProductosSeleccionados.appendChild(fila);

			var totalCompra = document.getElementById('total-compra');
			totalCompra.innerHTML = parseInt(totalCompra.innerHTML) + total;

			cantidadInput.value = "";
			mostrarColumnaOculta();
		}
	}

	function seleccionarProv(id, nombre, ruc) {
		document.getElementById('busca_provee').value = nombre;
		document.getElementById('provee_ruc').value = ruc;
		document.getElementById("lista_proveedores").innerHTML = "";
	}

	function quitarProducto(button) {
		var fila = button.parentNode.parentNode;
		var totalCompra = document.getElementById('total-compra');
		totalCompra.innerHTML = parseInt(totalCompra.innerHTML) - parseInt(fila.cells[3].innerHTML);
		fila.remove();

		if (document.getElementById('tabla-productos-seleccionados').rows.length === 1) {
			ocultarColumnaOculta();
		}
	}

	function mostrarColumnaOculta() {
		var columnaOculta = document.querySelector('.columna-oculta');
		columnaOculta.classList.remove('hidden');
	}

	function ocultarColumnaOculta() {
		var columnaOculta = document.querySelector('.columna-oculta');
		columnaOculta.classList.add('hidden');
	}

	function vaciarColumnaOculta() {
		// Cerrar la ventana modal
		var modal = document.getElementById('modal');
		modal.style.display = "none";

		// Vaciar la tabla de productos seleccionados
		var vaciarProductosSeleccionados = document.getElementById('tabla-productos-seleccionados');
		vaciarProductosSeleccionados.innerHTML = '<tr><th>Producto</th><th>Cantidad</th><th>Precio Unitario</th><th>IVA</th><th>Total</th><th></th></tr>';

		// Reiniciar el total de venta
		var totalCompra = document.getElementById('total-compra');
		totalCompra.innerHTML = "0";
		
		//Reinicar buscador de productos
		document.getElementById("campo").value = '';
		var lista = document.getElementById("lista_productos");
		lista.innerHTML = '';
	}

	function mostrarModal() {
		var modal = document.getElementById('modal');
		modal.style.display = "block";
	}
</script>