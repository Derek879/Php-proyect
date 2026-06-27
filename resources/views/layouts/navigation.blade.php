<nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top py-3">
    <div class="container">

        <a class="navbar-brand fw-bold d-flex align-items-center gap-2"
           href="{{ url('/') }}"
           style="color:#7c2d12;font-size:1.4rem;">
            🍰 M&M
        </a>

        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">

            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-3">

                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="{{ url('/') }}">
                        Inicio
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="{{ route('productos.index') }}">
                        Productos
                    </a>
                </li>

                @auth

                    @if(auth()->user()->role === 'admin')

                        <li class="nav-item">
                            <a class="nav-link fw-semibold" href="{{ route('categorias.index') }}">
                                Categorías
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link fw-semibold" href="{{ route('pedidos.index') }}">
                                Gestión Pedidos
                            </a>
                        </li>

                    @else

                        <li class="nav-item">
                            <a class="nav-link fw-semibold" href="{{ route('pedidos.index') }}">
                                Mis Pedidos
                            </a>
                        </li>

                    @endif

                @endauth

            </ul>

            <div class="d-flex align-items-center gap-2">

                @auth

                    {{-- CARRITO (solo clientes) --}}
                    @if(auth()->user()->role !== 'admin')
                    @php
                        $carritoItems = \App\Models\CarritoItem::with('producto')
                            ->where('user_id', auth()->id())
                            ->get();
                        $carritoTotal = $carritoItems->sum(fn($i) => $i->producto->precio * $i->cantidad);
                        $carritoCount = $carritoItems->sum('cantidad');
                    @endphp

                    <div class="dropdown">
                        <button class="btn btn-outline-secondary rounded-pill px-3 position-relative"
                                data-bs-toggle="dropdown" data-bs-auto-close="outside">
                            🛒
                            @if($carritoCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill"
                                  style="background:var(--dulce-rose);font-size:.65rem">
                                {{ $carritoCount }}
                            </span>
                            @endif
                        </button>

                        <div class="dropdown-menu dropdown-menu-end p-3 shadow" style="min-width:320px;border-radius:16px;border:none">
                            <h6 class="fw-bold mb-3">🛒 Mi Carrito</h6>

                            @if($carritoItems->isEmpty())
                                <p class="text-muted text-center small py-2">Tu carrito está vacío</p>
                            @else
                                @foreach($carritoItems as $item)
                                <div class="d-flex justify-content-between align-items-center mb-2 pb-2"
                                     style="border-bottom:1px solid #f5e6e8">
                                    <div style="flex:1">
                                        <div class="fw-bold small">{{ $item->producto->nombre }}</div>
                                        <div class="text-muted" style="font-size:.75rem">
                                            {{ $item->cantidad }} × S/ {{ number_format($item->producto->precio, 2) }}
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-bold small" style="color:var(--dulce-rose)">
                                            S/ {{ number_format($item->producto->precio * $item->cantidad, 2) }}
                                        </span>
                                        <form action="{{ route('carrito.quitar', $item->producto_id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm p-0"
                                                    style="color:#dc3545;background:none;border:none;font-size:1rem"
                                                    title="Quitar">×</button>
                                        </form>
                                    </div>
                                </div>
                                @endforeach

                                <div class="d-flex justify-content-between fw-bold mt-2 mb-3">
                                    <span>Subtotal</span>
                                    <span style="color:var(--dulce-rose)">S/ {{ number_format($carritoTotal, 2) }}</span>
                                </div>

                                <a href="{{ route('carrito.checkout') }}"
                                   class="btn-rose btn w-100 text-center mb-2">
                                    Ir a pagar
                                </a>

                                <form action="{{ route('carrito.vaciar') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger rounded-pill w-100 btn-sm">
                                        Vaciar carrito
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                    @endif

                    <span class="fw-semibold me-2" style="color:#7c2d12;">
                        👋 {{ Auth::user()->name }}
                    </span>

                    <a href="{{ route('profile.edit') }}"
                       class="btn btn-outline-secondary rounded-pill px-3">
                        Perfil
                    </a>

                    <form method="POST"
                          action="{{ route('logout') }}"
                          class="m-0">
                        @csrf
                        <button type="submit"
                                class="btn btn-outline-danger rounded-pill px-3">
                            Cerrar sesión
                        </button>
                    </form>

                @else

                    <a href="{{ route('login') }}"
                       class="btn btn-outline-dark rounded-pill px-4">
                        Iniciar sesión
                    </a>

                    <a href="{{ route('register') }}"
                       class="btn-rose btn">
                        Registrarse
                    </a>

                @endauth

            </div>

        </div>
    </div>
</nav>