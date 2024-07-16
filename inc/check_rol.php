<?php
//Define los roles y accesos
$roles = [
  1 => ['factur_new', 'factur_list', 'factur_search', 'factur_det', 'product_category', 'product_factur_search', 'product_family', 'product_img', 'product_list', 'product_new', 'product_search', 'product_update', 'category_list', 'category_new', 'category_search', 'category_update', 'family_list', 'family_new', 'family_search', 'family_update', 'provee_list', 'provee_new', 'provee_search', 'provee_update', 'client_new', 'client_list', 'client_search', 'client_update','user_list','user_new','user_search','user_update', 'compra_det', 'compra_list', 'compra_new', 'compra_report', 'compra_search', 'report_stock_income'], // admin
  2 => ['factur_new'], // encargado
  3 => ['factur_new', 'factur_list', 'factur_search', 'factur_det', 'client_new', 'client_list', 'client_search', 'client_update'], // vendedor
];

//Verificar el acceso
function check_rol($vista, $rol) {
  global $roles;
  
  if (array_key_exists($rol, $roles) && in_array($vista, $roles[$rol])) {
      // El usuario tiene permiso para acceder a la vista
      return true;
  } else {
      // El usuario no tiene permiso para acceder a la vista
      echo '
      <div class="notification is-warning is-light">
            <button class="delete"></button>
          <div class="icon-text ">
              <span class="icon has-text-warning is-large">
                    <i class="fas fa-2x fa-exclamation-triangle"></i>
              </span>
              <span class="title">Atención</span>
            </div>
          <p class="block subtitle">
              No tiene permisos para acceder a esta página.
              <p>
                  Contacte con el administrador.
              <p>
            </p>
      </div>
      ';
      include("./inc/btn_back.php");
      exit();
  }
}





/*function check_rol ($rol){
    if ($rol === 3) {
        // El usuario no tiene el rol adecuado para acceder a esta página
        echo '
        <div class="notification is-warning is-light">
              <button class="delete"></button>
            <div class="icon-text ">
                <span class="icon has-text-warning is-large">
                      <i class="fas fa-2x fa-exclamation-triangle"></i>
                </span>
                <span class="title">Atención</span>
              </div>
            <p class="block subtitle">
                No tiene permisos para acceder a esta página.
                <p>
                    Contacte con el administrador.
                <p>
              </p>
        </div>
        ';
        include("./inc/btn_back.php");
        exit();
    }
}*/
?>