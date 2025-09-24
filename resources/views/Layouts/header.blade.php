<header class="p-3 mb-3 border-bottom">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start fw-bold">
            <i class="fa-duotone fa-solid fa-user-alien fa-lg"></i> {{ session('nombreUsuario') }}
            &nbsp;
            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('panel') ? 'active' : '' }}" aria-current="page"
                            href="{{ route('dashboard') }}"><i class="fa-solid fa-duotone fa-dashboard"></i>
                            Panel</a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('empleados') ? 'active' : '' }}" aria-current="page"
                            href="{{ route('empleados.index') }}"><i class="fa-solid fa-duotone fa-user-tag"></i>
                            Empleados</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('usuarios') ? 'active' : '' }}" aria-current="page"
                            href="{{ route('usuarios.index') }}"><i class="fa-solid fa-duotone fa-user-tie"></i>
                            Usuarios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('clientes') ? 'active' : '' }}" aria-current="page"
                            href="#"><i class="fa-solid fa-duotone fa-address-card"></i>
                            Clientes</a>
                    </li>
                </ul>
            </ul>
            <div class="dropdown text-end"> <a href="#"
                    class="d-block link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown"
                    aria-expanded="false"> <img src="https://github.com/JaviSoria19.png" alt="mdo" width="32"
                        height="32" class="rounded-circle"> </a>
                <ul class="dropdown-menu text-small" style="">
                    <li><a class="dropdown-item" href="#"><i class="fa-solid fa-duotone fa-alien"></i>
                            {{ session('nombreUsuario') }}</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item {{ request()->is('parametros') ? 'active' : '' }}"
                            href="{{ route('parametros.index') }}"><i class="fa-solid fa-duotone fa-sliders"></i>
                            Parámetros</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    </li>
                    <li><button type="button" class="dropdown-item" data-bs-toggle="modal"
                            data-bs-target="#modalSignOut">
                            <i class="fa-solid fa-duotone  fa-sign-out"></i> Cerrar sesión</button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
