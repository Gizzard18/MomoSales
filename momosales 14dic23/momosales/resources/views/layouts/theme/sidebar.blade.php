<div class="deznav" style="background-color: #6E6E6E;margin-top:-20px;height:90%;position:fixed">
        <div class="deznav-scroll">
            <ul  class="metismenu"  id="menu">

                <li><a href="{{ route('Ventas') }}"  class="ai-icon mt-2" aria-expanded="false">
                        <i class="group las la-store-alt"></i>
                        <span style="color:#D4D4D4" class="nav-text">VENTAS</span>
                    </a>
                </li>

                <li><a href="{{ route('Citas') }}" class="ai-icon" aria-expanded="false" ">
                        <i class="las la-book"></i>
                        <span style="color:#D4D4D4" class="nav-text">CITAS</span>
                    </a>
                </li>

                <li><a href="{{ route('clientes') }}" class="ai-icon" aria-expanded="false">
                        <i class="las la-user-tag"></i>
                        <span style="color:#D4D4D4" class="nav-text" href="{{ route('clientes') }}">CLIENTES</span>
                    </a>
                </li>
                
                <li><a href="#" class="ai-icon" aria-expanded="false">
                        <i class="las la-shopping-basket"></i>
                        <span style="color:#D4D4D4" class="nav-text">COMPRAS</span>
                    </a>
                </li>
                <li><a href="{{ route('Ajustes') }}" class="ai-icon" aria-expanded="false">
                        <i class="las la-percent"></i>
                        <span style="color:#D4D4D4" class="nav-text" href="{{ route('Ajustes') }}">AJUSTES</span>
                    </a>
                </li>
                <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                        <i class="las la-box"></i>
                        <span style="color:#D4D4D4" class="nav-text">INVENTARIOS</span>
                    </a>
                    <ul aria-expanded="false" >
                        <li><a style="color:#D4D4D4" href="{{ route('Empleados') }}">Empleados</a></li>
                        <li><a style="color:#D4D4D4" href="{{ route('productos') }}">Productos</a></li>
                        <li><a style="color:#D4D4D4" href="{{ route('marcas') }}">Proveedores</a></li>
                        <li><a style="color:#D4D4D4" href="{{ route('Servicios') }}">Servicios</a></li>
                    </ul>
                </li>
                <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                        <i class="las la-sliders-h"></i>
                        <span style="color:#D4D4D4" class="nav-text">CATEGORÍAS</span>
                    </a>
                    <ul aria-expanded="false" >
                        <li><a style="color:#D4D4D4" href="{{ route('CategoriaClientes') }}">Clientes</a></li>
                        <li><a style="color:#D4D4D4" href="{{ route('CategoriaProductos') }}">Productos</a></li>
                        <li><a style="color:#D4D4D4" href="{{ route('CategoriaServicios') }}">Servicios</a></li>
                    </ul>
                </li>
                <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                        <i class="las la-chart-bar"></i>
                        <span style="color:#D4D4D4" class="nav-text">REPORTES</span>
                    </a>
                    <ul aria-expanded="false">
                        <li><a style="color:#D4D4D4" href="#">Compras</a></li>
                        <li><a style="color:#D4D4D4" href="#">Órdenes</a></li>
                        <li><a style="color:#D4D4D4" href="#">Ventas</a></li>
                    </ul>

                </li>
            </ul>


            <div class="copyright">
                <p><strong style="color:#D4D4D4">MomoSales V1 | Momó Salon</strong> </p>
            </div>
        </div>
    </div>

    <style>
        i.group.las.la-store-alt{
            color: white !important;
        }
    </style>