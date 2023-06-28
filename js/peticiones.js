document.getElementById("campo").addEventListener("keyup", getProduct);

function getProduct(){
    let inputCp = document.getElementById("campo").value;
    let lista = document.getElementById("lista");

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
    }
}