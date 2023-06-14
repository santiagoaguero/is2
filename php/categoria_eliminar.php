<?php
$category_id_del = limpiar_cadena($_GET["category_id_del"]);

//verifica categoria
$check_category = con();
$check_category=$check_category->query("SELECT categoria_id FROM categoria WHERE categoria_id = '$category_id_del'");

if($check_category->rowCount()==1){
        //verifica productos asociados a categoria
        $check_productos = con();
        $check_productos=$check_productos->query("SELECT categoria_id FROM producto WHERE categoria_id = '$category_id_del' LIMIT 1");// minimo 1 producto
        
        if($check_productos->rowCount()<=0){//no tiene productos asociados
            $eliminar_categoria = con();
            $eliminar_categoria=$eliminar_categoria->prepare("DELETE FROM categoria WHERE categoria_id=:id");
            //filtro prepare para evitar inyecciones sql xss
    
            $eliminar_categoria->execute([":id"=> $category_id_del]);

            if($eliminar_categoria->rowCount()==1){
                echo '
                <div class="notification is-success is-light">
                    <strong>Categoría eliminada!</strong><br>
                    La categoría ha sido eliminada exitosamente.
                </div>';
            } else {
                echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrió un error inesperado!</strong><br>
                    No se pudo eliminar la categoría, inténtelo nuevamente.
                </div>';
            }
             
            $eliminar_usuario=null;
        } else {
            echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                La categoría tiene productos asociados.
            </div>';
        }
        $check_productos=null;
} else {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        La categoría que intenta eliminar no existe.
    </div>';
}
$check_category=null;