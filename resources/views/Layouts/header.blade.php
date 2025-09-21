<header class="p-3 mb-3 border-bottom">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start fw-bold">
            <i class="fa-duotone fa-solid fa-user-alien fa-lg"></i> {{ session('nombreUsuario') }}
            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <li><a href="#" class="nav-link active"><i class="fa fa-dashboard"></i> Panel</a></li>
                <li><a href="#" class="nav-link px-2 link-body-emphasis"><i class="fa fa-user-tie"></i>
                        Empleados</a></li>
                <li><a href="#" class="nav-link px-2 link-body-emphasis"><i class="fa fa-user"></i>
                        Usuarios</a></li>
                <li><a href="#" class="nav-link px-2 link-body-emphasis"><i class="fa fa-users"></i>
                        Clientes</a></li>
                <li><a href="#" class="nav-link px-2 link-body-emphasis"><i class="fa fa-tags"></i>
                        Marcas</a></li>
                <li><a href="#" class="nav-link px-2 link-body-emphasis"><i class="fa fa-building"></i>
                        Empresas</a></li>
                <li><a href="#" class="nav-link px-2 link-body-emphasis"><i class="fa fa-cart-shopping"></i>
                        Ventas</a></li>
            </ul>
            <div class="dropdown text-end"> <a href="#"
                    class="d-block link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown"
                    aria-expanded="false"> <img src="https://github.com/JaviSoria19.png" alt="mdo" width="32"
                        height="32" class="rounded-circle"> </a>
                <ul class="dropdown-menu text-small" style="">
                    <li><a class="dropdown-item" href="#"><i class="fa fa-gear"></i> Ajustes</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fa fa-user"></i> Perfil</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="#"><i class="fa fa-sign-out"></i> Cerrar sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
