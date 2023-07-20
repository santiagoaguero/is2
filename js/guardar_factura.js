function guardarFactura(event) {
    event.preventDefault();

    var ruc = document.getElementById('cliente_ruc').value;

    var productosSeleccionados = document.getElementById('tabla-productos-seleccionados').getElementsByTagName('tr');
    var productos = [];

    for (var i = 1; i < productosSeleccionados.length; i++) {
        var fila = productosSeleccionados[i];
        var producto = {
            id: fila.cells[0].innerHTML,
            nombre: fila.cells[1].innerHTML,
            cantidad: fila.cells[2].innerHTML,
            precio: fila.cells[3].innerHTML,
            iva: fila.cells[4].innerHTML
        };
        productos.push(producto);
    }

    var data = {
        ruc: ruc,
        productos: productos,

    }

    fetch('./php/guardar_factura.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.text())
    .then(html => {
        // Abrir una nueva ventana con el contenido HTML de la factura
        var facturaWindow = window.open('', '_blank');
        facturaWindow.document.open();
        facturaWindow.document.write(html);
        facturaWindow.document.close();

        // Habilitar la opción de imprimir la factura
        facturaWindow.onload = function() {
            facturaWindow.print();
        }

        vaciarColumnaOculta();

        ocultarColumnaOculta();
    })
    .catch(error => {
        // Aquí puedes manejar errores de la solicitud HTTP.
        console.error('ErrorHTTP->:', error);
    });
}