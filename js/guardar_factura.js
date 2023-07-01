function guardarFactura(event) {
    event.preventDefault();

    var nombre = document.getElementById('cliente_nombre').value;
    var ruc = document.getElementById('cliente_ruc').value;
    var factura = document.getElementById('factura');

    var productosSeleccionados = document.getElementById('tabla-productos-seleccionados').getElementsByTagName('tr');
    var productos = [];

    for (var i = 1; i < productosSeleccionados.length; i++) {
        var fila = productosSeleccionados[i];
        var producto = {
            nombre: fila.cells[0].innerHTML,
            cantidad: fila.cells[1].innerHTML,
            precio: fila.cells[2].innerHTML,
            total: fila.cells[3].innerHTML
        };
        productos.push(producto);
    }

    var data = {
        nombre: nombre,
        ruc: ruc,
        productos: productos
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
    })
    .catch(error => {
        // Aquí puedes manejar errores de la solicitud HTTP.
        console.error('ErrorHTTP->:', error);
    });
}