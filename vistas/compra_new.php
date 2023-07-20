<?php
	require_once("./php/main.php");
?>
<div class="container is-fluid mb-6">
	<h1 class="title">Compras</h1>
	<h2 class="subtitle">Nueva Compra</h2>
</div>

<div class="container pb-6 pt-6">

	<div class="form-rest mb-6 mt-6"></div><!--to show notifications -->
	<?php 
		$fecha_obj = new DateTime();//format fecha
		$fechaES = $fecha_obj->format('Y-m-d');
	?>

	<form action="./php/compra_guardar.php" method="POST" class="formularioAjax" id="formul" autocomplete="off" >
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<span>Fecha</span>
				  	<input class="input" type="date" name="compra_fecha" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ. ]{3,40}" max="<?php echo $fechaES ?>" required autofocus value="<?php echo $fechaES ?>">
				</div>
		  	</div>
			<div class="column">
				<div class="control">
					<span>Proveedor</span>
					<p class="control has-icons-left">
                    	<input class="input" type="text" name="busca_provee" id="busca_provee" placeholder="Proveedor" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ .]{1,30}" maxlength="30" autocomplete="off" required>
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
				  	<input class="input" type="text" name="compra_factura" pattern="^[0-9\-]{6,30}$" maxlength="30" required>
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<span>Condición</span>
					<div class="select is-rounded">
						<select name="compra_condicion" required>
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
				<div class="buttons is-centered has-addons">
					<p class="control">
						<button class="button modal-button is-info is-rounded is-outlined" type="button" data-target="modalBuscarProducto">
							<!-- button type button doesn't send form -->
						<span class="icon is-small">
        					<i class="fas fa-search"></i>
						</span>
						<span>Buscar producto</span>
						</button>
					</p>
					<p class="control">
						<button class="button modal-button is-info is-rounded is-outlined" type="button" data-target="modalCrearProducto">
							<!-- button type button doesn't send form -->
						<span class="icon is-small">
        					<i class="fas fa-add"></i>
						</span>
						<span>Nuevo producto</span>
						</button>
					</p>
				</div>
			</div>
		</div>
		<div class="columns">
			<div class="column">
				<table class="table is-fullwidth" name="compra_prod">
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
					<tbody id="tabla-productos-seleccionados">

					</tbody>
					<tfoot>
						<tr>
							<td><h3>Total IVA 5%:</h3></td>
							<td><span id="total_iva_5">0</span></td>
							<td colspan="4"></td>
						</tr>
						<tr>
							<td><h3>Total IVA 10%:</h3></td>
							<td><span id="total_iva_10">0</span></td>
							<td colspan="4"></td>
						</tr>
						<tr>
							<td><h3>Total de Compra: </h3></td>
							<td><span id="compra_total" name="compra_total">0</span></td>
							<td colspan="4"></td>
						</tr>
					</tfoot>
				</table>
				<br>
				
				
				
			</div>
    	</div>
		<p class="has-text-centered">
			<input type="submit" class="button is-info is-rounded" value="Guardar"></input>
		</p>
	</form>
</div>
<?php 
	include("./modal/crear_producto.php");
	include("./modal/buscar_producto.php");
?>

<script>
	function seleccionarProducto(id, nombre, iva) {
		const tableBody = document.getElementById('tabla-productos-seleccionados');
		const row = document.createElement('tr');

		const idInput = document.createElement('input');
		idInput.type = 'hidden';
		idInput.name = 'id_producto[]';
		idInput.value = id;

		const cantidadInput = document.createElement('input');
		cantidadInput.type = 'number';
		cantidadInput.className = 'input is-rounded is-small';
		cantidadInput.name = 'cantidad_producto[]';
		cantidadInput.min = '1';
		cantidadInput.required = true;

		const precioInput = document.createElement('input');
		precioInput.type = 'number';
		precioInput.className = 'input is-rounded is-small';
		precioInput.name = 'precio_producto[]';
		precioInput.min = '1';
		precioInput.step = '1';
		precioInput.required = true;

		row.innerHTML = `
		<td>${id}</td>
        <td>${nombre}</td>
        <td></td>
        <td></td>
		<td>${iva}</td>
        <td></td>
		<td class="has-text-centered"><button onclick="quitarProducto(this)" class="button is-danger is-outlined is-small is-rounded">Quitar</button></td>
      `;

	  	row.cells[0].appendChild(idInput);
		row.cells[2].appendChild(cantidadInput);
		row.cells[3].appendChild(precioInput);
		row.cells[5].innerText = '0';

		tableBody.appendChild(row);

      // Actualizar el total de precio cuando se cambie la cantidad o el precio
      cantidadInput.addEventListener('input', function () {
        actualizarTotalPrecio(cantidadInput, precioInput, row.cells[5]);
		actualizarTotalesIVA();
      });
      precioInput.addEventListener('input', function () {
        actualizarTotalPrecio(cantidadInput, precioInput, row.cells[5]);
		actualizarTotalesIVA();
      });

	// Prevenir el envío del formulario al presionar Enter en los campos de cantidad y precio
	cantidadInput.addEventListener('keydown', function (event) {
		if (event.key === 'Enter') {
			event.preventDefault();
		}
	});
	precioInput.addEventListener('keydown', function (event) {
		if (event.key === 'Enter') {
			event.preventDefault();
		}
	});

    }

    function actualizarTotalPrecio(cantidadInput, precioInput, totalPrecioCell) {
      const cantidad = parseInt(cantidadInput.value);
      const precio = parseInt(precioInput.value);

      const totalPrecio = isNaN(cantidad) || isNaN(precio) ? 0 : cantidad * precio;
      totalPrecioCell.innerText = totalPrecio;
    }

    function actualizarTotalesIVA() {
      const totalGeneralElement = document.getElementById('compra_total');
      const totalIVA5Element = document.getElementById('total_iva_5');
      const totalIVA10Element = document.getElementById('total_iva_10');
      const productosSeleccionados = document.querySelectorAll('#tabla-productos-seleccionados tr');
      let totalGeneral = 0;
      let totalIVA5 = 0;
      let totalIVA10 = 0;

      for (let i = 0; i < productosSeleccionados.length; i++) {
        const cantidad = parseInt(productosSeleccionados[i].cells[2].querySelector('input').value);
        const precio = parseInt(productosSeleccionados[i].cells[3].querySelector('input').value);
        const porcentajeIVA = parseInt(productosSeleccionados[i].cells[4].innerText);

        const totalProducto = isNaN(cantidad) || isNaN(precio) ? 0 : cantidad * precio;
        totalGeneral += totalProducto;

        if (porcentajeIVA === 5) {
          totalIVA5 += Math.round(totalProducto / 21);
        } else if (porcentajeIVA === 10) {
          totalIVA10 += Math.round(totalProducto / 11);
        }
      }

      totalGeneralElement.innerText = totalGeneral;
      totalIVA5Element.innerText = totalIVA5;
      totalIVA10Element.innerText = totalIVA10;
    }

	function seleccionarProv(id, nombre, ruc) {
		document.getElementById('busca_provee').value = nombre;
		document.getElementById('provee_ruc').value = ruc;
		document.getElementById("lista_proveedores").innerHTML = "";
	}

	function quitarProducto(button) {
		var fila = button.parentNode.parentNode;
		var totalCompra = document.getElementById('compra_total');
		totalCompra.innerHTML = parseInt(totalCompra.innerHTML) - parseInt(fila.cells[5].innerHTML);
		fila.remove();
	}


	function vaciarColumnaOculta() {
		// Vaciar la tabla de productos seleccionados
		var vaciarProductosSeleccionados = document.getElementById('tabla-productos-seleccionados');
		vaciarProductosSeleccionados.innerHTML = '<tr><th>Producto</th><th>Cantidad</th><th>Precio Unitario</th><th>IVA</th><th>Total</th><th></th></tr>';

		// Reiniciar el total de venta
		var totalCompra = document.getElementById('compra_total');
		totalCompra.innerHTML = "0";
		
		//Reinicar buscador de productos
		document.getElementById("campo").value = '';
		var lista = document.getElementById("lista_productos");
		lista.innerHTML = '';
	}
</script>