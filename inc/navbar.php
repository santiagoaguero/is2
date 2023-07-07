<nav class="navbar mb-5" role="navigation" aria-label="main navigation">
  <div class="navbar-brand">
    <a class="navbar-item-img-max-height" href="index.php?vista=home">
      <img src="./img/logo.png" width="80" alt="logo"><!-- height-> img-max-height class -->
    </a>
    <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
      <span aria-hidden="true"></span>
      <span aria-hidden="true"></span>
      <span aria-hidden="true"></span>
    </a>
  </div>
  <div id="navbarBasicExample" class="navbar-menu">
    <div class="navbar-start">
      <div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link">Facturas</a>
        <div class="navbar-dropdown">
          <a class="navbar-item" href="index.php?vista=factur_new">Nuevo</a>
          <a class="navbar-item" href="index.php?vista=factur_list">Lista</a>
          <a class="navbar-item" href="index.php?vista=factur_search">Buscar</a>
        </div>
      </div>
      <div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link">Productos</a>
        <div class="navbar-dropdown">
          <a class="navbar-item" href="index.php?vista=product_new">Nuevo</a>
          <a class="navbar-item" href="index.php?vista=product_list">Lista</a>
          <a class="navbar-item" href="index.php?vista=product_category">Por Categoría</a>
          <a class="navbar-item" href="index.php?vista=product_search">Buscar</a>
        </div>
      </div>
      <div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link">Categorías</a>
        <div class="navbar-dropdown">
            <a class="navbar-item" href="index.php?vista=category_new">Nuevo</a>
            <a class="navbar-item" href="index.php?vista=category_list">Lista</a>
            <a class="navbar-item" href="index.php?vista=category_search">Buscar</a>
        </div>
      </div>
      <div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link">Proveedores</a>
        <div class="navbar-dropdown">
          <a class="navbar-item" href="index.php?vista=provee_new">Nuevo</a>
          <a class="navbar-item" href="index.php?vista=provee_list">Lista</a>
          <a class="navbar-item" href="index.php?vista=provee_search">Buscar</a>
        </div>
      </div>
      <div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link">Clientes</a>
        <div class="navbar-dropdown">
            <a class="navbar-item" href="index.php?vista=client_new">Nuevo</a>
            <a class="navbar-item" href="index.php?vista=client_list">Lista</a>
            <a class="navbar-item" href="index.php?vista=client_search">Buscar</a>
        </div>
      </div>
      <div class="navbar-item has-dropdown is-hoverable">
          <a class="navbar-link">Usuarios</a>
          <div class="navbar-dropdown">
              <a class="navbar-item" href="index.php?vista=user_new">Nuevo</a>
              <a class="navbar-item" href="index.php?vista=user_list">Lista</a>
              <a class="navbar-item" href="index.php?vista=user_search">Buscar</a>
          </div>
        </div>

    </div>
<!-- ñ -->
    <div class="navbar-end">
      <div class="navbar-item">
        <div class="buttons">
          <a href="index.php?vista=user_update&user_id_upd=<?php echo $_SESSION['id']?>" class="button is-primary is-rounded">
            Mi Cuenta
          </a>
          <a href="index.php?vista=logout" class="button is-link is-rounded">
            Salir
          </a>
        </div>
      </div>
    </div>
  </div>
</nav>