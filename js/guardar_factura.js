function guardarFactura(event) {
    event.preventDefault();

    var nombre = document.getElementById('cliente').value;
    var ruc = document.getElementById('ruc').value;
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
    console.log("enviando->", data)
    
    fetch('./php/guardar_factura.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.text())
    .then(result => {
        // Aquí puedes manejar la respuesta del servidor, como mostrar un mensaje de éxito o error.
        document.getElementById('cliente').value = "";
        document.getElementById('ruc').value = "";
        factura.innerHTML = result;
    })
    .catch(error => {
        // Aquí puedes manejar errores de la solicitud HTTP.
        console.error('ErrorHTTP->:', error);
    });
}