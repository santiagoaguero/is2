// Obtener el parámetro 'vista' de la URL
var urlParams = new URLSearchParams(window.location.search);
var vista = urlParams.get('vista');

// Verificar la vista actual y activar/desactivar los eventos según corresponda
//esto para evitar que se activen los eventos y de errores en lugares no solicitados
if (vista === 'compra_new') {
  document.getElementById("busca_provee").addEventListener("keyup", getProvee);
  document.getElementById("campo").addEventListener("keyup", getProductCompras);
} else {
  document.getElementById("campo").addEventListener("keyup", getProduct);
  document.getElementById("busca_clienteF").addEventListener("keyup", getClientF);
  document.getElementById("busca_clienteP").addEventListener("keyup", getClientP);
}//no puede haber 2 id iguales aunque sean modales

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

function getClientF(){
    let inputCp = document.getElementById("busca_clienteF").value;
    console.log("factura->",inputCp);
    let lista = document.getElementById("lista_clientesF");

    if(inputCp.length > 0){

        let url = "./php/getClientesFactura.php";
        let formData = new FormData();
        formData.append("busca_clienteF", inputCp);

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

function getClientP(){
    let inputCp = document.getElementById("busca_clienteP").value;
    console.log("presup->",inputCp);
    let lista = document.getElementById("lista_clientesP");

    if(inputCp.length > 0){

        let url = "./php/getClientesFactura.php";
        let formData = new FormData();
        formData.append("busca_clienteP", inputCp);

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

function getProductCompras(){
    let inputCp = document.getElementById("campo").value;
    let lista = document.getElementById("lista_productos");

    if(inputCp.length > 0){

        let url = "./php/getProductCompras.php";
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