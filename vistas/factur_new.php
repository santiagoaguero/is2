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
            <p class="subtitle has-text-centered mb-2">Agregar productos</p>
            <form action="" method="POST" autocomplete="off" >
                <div class="control">
                    <input class="input is-rounded mb-3" type="text" name="campo" id="campo" placeholder="¿Qué estas buscando?" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" maxlength="30" autofocus>
                    <span id="lista_productos"></span>
                </div>
            </form>
        </div>
        <div class="column box columna-oculta hidden">
            <table id="tabla-productos-seleccionados" class="table is-fullwidth">
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
            <h3>Total de Venta: <span id="total-venta">0</span></h3>
            <button class="button modal-button is-success is-outlined" data-target="modalFactura">Imprimir Factura</button>
            <button class="button modal-button is-success is-outlined" data-target="modalPresupuesto">Presupuesto</button>
        </div>
    </div>
<?php 
    include("./modal/cliente.php");
    include("./modal/presupuesto.php");
?>
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

                var totalVenta = document.getElementById('total-venta');
                totalVenta.innerHTML = parseInt(totalVenta.innerHTML) + total;

                cantidadInput.value = "";
                mostrarColumnaOculta();
            }
        }

        function seleccionarCliente(lista, id, nombre, ruc) {
            if(lista == "fact"){
                document.getElementById('cliente_nombreF').value = nombre;
                document.getElementById('cliente_rucF').value = ruc;

            } else if (lista == "presup"){
                document.getElementById('cliente_nombreP').value = nombre;
                document.getElementById('cliente_rucP').value = ruc;
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

        function vaciarColumnaOculta() {
            // Vaciar la tabla de productos seleccionados
            var vaciarProductosSeleccionados = document.getElementById('tabla-productos-seleccionados');
            vaciarProductosSeleccionados.innerHTML = '<tr><th>Producto</th><th>Cantidad</th><th>Precio Unitario</th><th>IVA</th><th>Total</th><th></th></tr>';

            // Reiniciar el total de venta
            var totalVenta = document.getElementById('total-venta');
            totalVenta.innerHTML = "0";
            
            //Reinicar buscador de productos
            document.getElementById("campo").value = '';
            var lista = document.getElementById("lista_productos");
            lista.innerHTML = '';
        }
    </script>
</div>