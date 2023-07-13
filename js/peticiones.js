// Obtener el parámetro 'vista' de la URL
var urlParams = new URLSearchParams(window.location.search);
var vista = urlParams.get('vista');

// Verificar la vista actual y activar/desactivar los eventos según corresponda
//esto para evitar que se activen los eventos y de errores en lugares no solicitados
if (vista === 'compra_new') {
  document.getElementById("busca_provee").addEventListener("keyup", getProvee);
  document.getElementById("campo").addEventListener("keyup", getProduct);
} else {
  document.getElementById("campo").addEventListener("keyup", getProduct);
  document.getElementById("busca_cliente").addEventListener("keyup", getClient);
}

function getProduct(){
    let inputCp = document.getElementById("campo").value;
    let lista = document.getElementById("lista_productos");

    if(inputCp.length > 0){

        let url = "./php/getProductDetalle.php";
        let formData = new FormData();
        formData.append("campo", inputCp);

        fetch(url, {
            method: "POST",
            body: formData,
            mode: "cors"
        })
        .then(response => response.json())
        .then(data => {

            lista.innerHTML = data;

        })
        .catch(err=> console.log("catch->",err));
    } else {
        lista.innerHTML = '';
    }
}

function getClient(){
    let inputCp = document.getElementById("busca_cliente").value;
    let lista = document.getElementById("lista_clientes");

    if(inputCp.length > 0){

        let url = "./php/getClientesFactura.php";
        let formData = new FormData();
        formData.append("busca_cliente", inputCp);

        fetch(url, {
            method: "POST",
            body: formData,
            mode: "cors"
        })
        .then(response => response.json())
        .then(data => {

            lista.innerHTML = data;

        })
        .catch(err=> console.log("catch->",err));
    } else {
        lista.innerHTML = '';
    }
}

function getProvee(){
    let inputCp = document.getElementById("busca_provee").value;
    let lista = document.getElementById("lista_proveedores");

    console.log(inputCp);
    if(inputCp.length > 0){

        let url = "./php/getProveedoresFactura.php";
        let formData = new FormData();
        formData.append("busca_provee", inputCp);

        fetch(url, {
            method: "POST",
            body: formData,
            mode: "cors"
        })
        .then(response => response.json())
        .then(data => {

            lista.innerHTML = data;

        })
        .catch(err=> console.log("catch->",err));
    } else {
        lista.innerHTML = '';
        document.getElementById('provee_ruc').value = '';
    }
}