document.getElementById("campo").addEventListener("keyup", getProduct);
document.getElementById("busca_cliente").addEventListener("keyup", getClient);

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
    }
}