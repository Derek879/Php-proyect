@extends('layouts.app')

@section('title', 'M&M — Inicio')

@push('styles')

<style>

.hero {
    background: linear-gradient(135deg, #FFF0F5 0%, #FFF8F0 50%, #F5F0FF 100%);
    padding: 4rem 1rem;
    text-align: center;
}

.hero-title {
    font-size: 4rem;
    font-weight: 900;
    line-height: 1.1;
    color: #2d1b12;
}

.hero-title .accent {
    color: #ec4899;
}

.hero p {
    color: #6b3b2a;
    font-size: 1.1rem;
}

.btn-rose {
    background: #ec4899;
    color: white;
    border-radius: 50px;
    border: none;
    padding: .9rem 2rem;
    text-decoration: none;
    font-weight: 700;
    transition: .2s;
    min-width: 200px;
    text-align: center;
    display: inline-block;
}

.btn-rose:hover {
    background: #db2777;
    color: white;
    transform: translateY(-2px);
}

.btn-outline-dulce {
    border: 2px solid #ec4899;
    color: #ec4899;
    border-radius: 50px;
    padding: .9rem 2rem;
    text-decoration: none;
    font-weight: 700;
    transition: .2s;
    min-width: 200px;
    text-align: center;
    background: white;
    display: inline-block;
}

.btn-outline-dulce:hover {
    background: #ec4899;
    color: white;
}

.stat-box {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    text-align: center;
    box-shadow: 0 10px 20px rgba(0,0,0,.08);
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 900;
    color: #ec4899;
}

.stat-label {
    color: #7c2d12;
    font-weight: 700;
}

.admin-title {
    font-size: 3.5rem;
    font-weight: 900;
    color: #2d1b12;
}

.card-dulce {
    background: white;
    border-radius: 20px;
    padding: 1.5rem;
    box-shadow: 0 5px 15px rgba(0,0,0,.08);
    transition: .2s;
}

.card-dulce:hover {
    transform: translateY(-5px);
}

.section-title {
    font-size: 2.2rem;
    font-weight: 800;
    color: #2d1b12;
}
.producto-img {
    width: 100%;
    height: 180px;
    object-fit: contain;
    background: #fff0f5;
    border-radius: 16px;
    padding: 10px;
}

.producto-img-placeholder {
    width: 100%;
    height: 180px;
    background: #fff0f5;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 4rem;
}

</style>

@endpush

@section('content')

@auth

    @if(Auth::user()->role === 'admin')

        <section class="hero">

            <div class="container">

                <h1 class="admin-title">
                    Panel Administrador
                </h1>

                <p class="mt-3 mb-5">
                    Gestiona productos, pedidos y revisa estadísticas del sistema.
                </p>

                <div class="d-flex justify-content-center gap-3 flex-wrap mb-5">

                    <a href="{{ route('productos.index') }}" class="btn-rose">
                        Gestionar Productos
                    </a>

                    <a href="{{ route('pedidos.index') }}" class="btn-outline-dulce">
                        Ver Pedidos
                    </a>
                    

                </div>

                <div class="row g-4">

                    <div class="col-md-4">
                        <div class="stat-box">
                            <div class="stat-number">
                                {{ $stats['clientes'] ?? 0 }}
                            </div>

                            <div class="stat-label">
                                Clientes
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="stat-box">
                            <div class="stat-number">
                                {{ $stats['pedidos'] ?? 0 }}
                            </div>

                            <div class="stat-label">
                                Pedidos
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="stat-box">
                            <div class="stat-number">
                                {{ $stats['productos'] ?? 0 }}
                            </div>

                            <div class="stat-label">
                                Productos
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </section>

    @else

        <section class="hero">

            <div class="container">

                <h1 class="hero-title">
                    Bienvenido a
                    <span class="accent">
                        M&M
                    </span>
                </h1>

                <p class="mt-3 mb-5">
                    Realiza tu pedido y revisa tu historial de compras.
                </p>

                <div class="d-flex justify-content-center gap-3 flex-wrap">

                    <a href="{{ route('productos.index') }}" class="btn-rose">
                        Hacer mi pedido
                    </a>

                    <a href="{{ route('pedidos.index') }}" class="btn-outline-dulce">
                        Mi historial
                    </a>

                </div>

            </div>

        </section>

    @endif

@else

    <section class="hero">

        <div class="container">

            <h1 class="hero-title">
                Bienvenido a
                <span class="accent">
                    M&M
                </span>
            </h1>

            <p class="mt-3 mb-5">
                Inicia sesión para realizar tus pedidos.
            </p>

            <div class="d-flex justify-content-center gap-3 flex-wrap">

                <a href="{{ route('login') }}" class="btn-rose">
                    Iniciar Sesión
                </a>

                <a href="{{ route('register') }}" class="btn-outline-dulce">
                    Registrarse
                </a>

            </div>

        </div>

    </section>

@endauth


<section class="py-5">

    <div class="container">

        <h2 class="section-title text-center mb-5">
            Productos Destacados
        </h2>

        <div class="row g-4">

            @forelse($productos as $producto)

                <div class="col-md-3">

                  <div class="card-dulce text-center h-100">

    @if($producto->imagen)
        <img src="{{ $producto->imagen_url }}"
             class="producto-img mb-3"
             alt="{{ $producto->nombre }}">
    @else
        <div class="producto-img-placeholder mb-3">
            {{ $producto->categoria->icono ?? '🍰' }}
        </div>
    @endif

    <h4 class="mb-3">
    {{ $producto->nombre }}
</h4>

<p class="mb-4">
    S/ {{ number_format($producto->precio, 2) }}
</p>

<div class="d-flex flex-column gap-2">

    <a href="{{ route('productos.show', $producto) }}"
       class="btn-rose">
        Ver producto
    </a>

    @auth
        @if(Auth::user()->role === 'cliente')

            <form action="{{ route('carrito.agregar') }}" method="POST">
                @csrf
                <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                <input type="hidden" name="cantidad" value="1">
                <button type="submit" class="btn-outline-dulce w-100">
                    Agregar al carrito
                </button>
            </form>

        @endif
    @endauth

</div>

                    </div>

                </div>
            
            @empty

                <div class="col-12 text-center">
                    <p>No hay productos registrados.</p>
                </div>

            @endforelse

        </div>

    </div>

</section>

@endsection