<div class="container is-fluid mb-6">
    <h1 class="title">Facturas</h1>
    <h2 class="subtitle">Nueva Factura</h2>
</div>

<div class="container pb-6 pt-6">
<?php 
    require_once("./php/main.php");
?>
    <div class="columns">

        <div class="column is-one-third">
            <p class="subtitle has-text-centered">Agregar productos</p>
                <?php

				    include("product_factur_search.php");

                ?>
        </div>
        <div class="column box">
        <div class="columna-oculta hidden">

        <table id="tabla-productos-seleccionados" class="table is-striped is-narrow is-hoverable is-fullwidth">
            <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Total</th>
                <th></th>
            </tr>
            </thead>
        </table>
        <br>
        <h3>Total de Venta: <span id="total-venta">0</span></h3>
        <button onclick="imprimirFactura()">Imprimir Factura</button>
    </div>
    </div>
</div>

    <script>
        function seleccionarProducto(id) {
            var cantidadInput = document.querySelector('input[name="cantidad_producto' + id + '"]');
            var cantidad = cantidadInput.value;
            var precioInput = document.querySelector('input[name="prod_precio' + id + '"]');
            var precio = precioInput.value;
            var productoInput = document.querySelector('input[name="prod_nombre' + id + '"]');
            var producto = productoInput.value;
            console.log("precio->", precio);
            console.log("cantidad->",cantidad);
            console.log("producto->", producto);
            if (cantidad > 0) {
                var total = cantidad * precio;

                var tablaProductosSeleccionados = document.getElementById('tabla-productos-seleccionados');
                var fila = document.createElement('tr');
                fila.innerHTML = '<td>' + producto + '</td>' +
                                 '<td>' + cantidad + '</td>' +
                                 '<td>' + precio + '</td>' +
                                 '<td>' + total + '</td>' +
                                 '<td><button onclick="quitarProducto(this)">Quitar</button></td>';
                tablaProductosSeleccionados.appendChild(fila);

                var totalVenta = document.getElementById('total-venta');
                totalVenta.innerHTML = parseInt(totalVenta.innerHTML) + total;

                cantidadInput.value = "";
                mostrarColumnaOculta();
            }
        }

        function quitarProducto(button) {
            var fila = button.parentNode.parentNode;
            var totalVenta = document.getElementById('total-venta');
            totalVenta.innerHTML = parseInt(totalVenta.innerHTML) - parseInt(fila.cells[3].innerHTML);
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

        function imprimirFactura() {
            // Aquí puedes implementar la lógica para imprimir la factura.
            // Por simplicidad, este ejemplo solo muestra una alerta.
            alert('Factura impresa.');
        }
    </script>

    </div>
</div>
