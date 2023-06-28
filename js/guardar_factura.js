function guardarFactura(event) {
    event.preventDefault();

    var nombre = document.getElementById('boris').value;
    var ruc = document.getElementById('rufo').value;

    var productosSeleccionados = document.getElementById('tabla-productos-seleccionados').getElementsByTagName('tr');
    var productos = [];

    for (var i = 1; i < productosSeleccionados.length; i++) {
        var fila = productosSeleccionados[i];
        var producto = {
            nombre: fila.cells[0].innerHTML,
            cantidad: fila.cells[1].innerHTML,
            precioUnitario: fila.cells[2].innerHTML,
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
    .then(response => response.json())
    .then(result => {
        // Aquí puedes manejar la respuesta del servidor, como mostrar un mensaje de éxito o error.
        console.log(result);
    })
    .catch(error => {
        // Aquí puedes manejar errores de la solicitud HTTP.
        console.error('Error:', error);
    });
}