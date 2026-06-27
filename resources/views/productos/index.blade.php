@extends('layouts.app')
@section('title', 'Productos')

@section('content')
<div class="page-header">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1>
                    <i class="bi bi-grid me-2" style="color:var(--dulce-rose)"></i>
                    Productos
                </h1>
                <p class="text-muted mb-0">Gestiona tu catálogo de dulces</p>
            </div>

            @if(auth()->check() && auth()->user()->role === 'admin')
            <div class="d-flex gap-2 flex-wrap justify-content-end">
                <a href="{{ route('categorias.index') }}" class="btn-rose btn">
                    <i class="bi bi-tags me-2"></i> Crear Categorías
                </a>
                <a href="{{ route('productos.create') }}" class="btn-rose btn">
                    <i class="bi bi-plus-circle me-2"></i> Nuevo Producto
                </a>
            </div>
            @endif
        </div>

        <!-- FILTROS -->
        <form method="GET" action="{{ route('productos.index') }}" class="mt-3">
            <div class="row g-2">
                <div class="col-md-5">
                    <input type="text" name="buscar" class="form-control form-control-dulce"
                           placeholder="🔍 Buscar producto..." value="{{ request('buscar') }}">
                </div>
                <div class="col-md-4">
                    <select name="categoria_id" class="form-select form-control-dulce">
                        <option value="">Todas las categorías</option>
                        @foreach($categorias as $cat)
                        <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->icono }} {{ $cat->nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn-rose btn flex-grow-1">Filtrar</button>
                    @if(request('buscar') || request('categoria_id'))
                    <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary rounded-pill">✕</a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

<div class="container py-4">
    <div class="row g-4">

        @forelse($productos as $producto)
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="card-dulce h-100 position-relative">

                @if($producto->destacado)
                <div class="position-absolute top-0 start-0 m-2 z-1">
                    <span class="badge" style="background:var(--dulce-gold);border-radius:50px;font-size:.7rem">⭐ Destacado</span>
                </div>
                @endif

                @if(!$producto->disponible)
                <div class="position-absolute top-0 end-0 m-2 z-1">
                    <span class="badge bg-secondary" style="border-radius:50px;font-size:.7rem">No disponible</span>
                </div>
                @endif

                @if($producto->imagen)
                <img src="{{ $producto->imagen_url }}" class="producto-img" alt="{{ $producto->nombre }}">
                @else
                <div class="card-producto-img-placeholder">
                    {{ $producto->categoria->icono ?? '🍰' }}
                </div>
                @endif

                <div class="p-3">
                    <span class="badge-categoria mb-2 d-inline-block">
                        {{ $producto->categoria->nombre ?? '—' }}
                    </span>

                    <h6 class="fw-bold mb-1">{{ $producto->nombre }}</h6>

                    @if($producto->descripcion)
                    <p class="text-muted small mb-2" style="line-height:1.4">
                        {{ Str::limit($producto->descripcion, 60) }}
                    </p>
                    @endif

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge-precio">S/ {{ number_format($producto->precio, 2) }}</span>
                        <small class="text-muted">Stock: {{ $producto->stock }}</small>
                    </div>

                    {{-- ADMIN --}}
                    @if(auth()->check() && auth()->user()->role === 'admin')
                    <div class="d-flex gap-2">
                        <a href="{{ route('productos.edit', $producto) }}"
                           class="btn btn-sm btn-outline-dulce flex-grow-1">
                            <i class="bi bi-pencil me-1"></i> Editar
                        </a>
                        <form action="{{ route('productos.destroy', $producto) }}" method="POST"
                              onsubmit="return confirm('¿Eliminar este producto?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:50px">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>

                    {{-- CLIENTE LOGUEADO --}}
                    @elseif(auth()->check() && auth()->user()->role !== 'admin')
                    <form action="{{ route('carrito.agregar') }}" method="POST">
                        @csrf
                        <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                        <input type="hidden" name="cantidad" value="1">
                        <button type="submit" class="btn-rose btn w-100 fw-bold"
                                {{ !$producto->disponible ? 'disabled' : '' }}>
                            <i class="bi bi-cart-plus me-1"></i> Agregar al carrito
                        </button>
                    </form>

                    {{-- NO LOGUEADO --}}
                    @else
                    <a href="{{ route('login') }}" class="btn w-100 fw-bold btn-outline-secondary rounded-pill">
                        Inicia sesión para comprar
                    </a>
                    @endif

                </div>
            </div>
        </div>

        @empty
        <div class="col-12 text-center py-5">
            <div style="font-size:4rem">🍰</div>
            <h4 class="mt-3">No hay productos</h4>
            <p class="text-muted">Crea tu primer producto dulce</p>
            @if(auth()->check() && auth()->user()->role === 'admin')
            <a href="{{ route('productos.create') }}" class="btn-rose btn mt-2">
                <i class="bi bi-plus-circle me-2"></i> Crear Producto
            </a>
            @endif
        </div>
        @endforelse

    </div>

    <div class="mt-4">{{ $productos->links() }}</div>
</div>

@push('styles')
<style>
.producto-img {
    width: 100%;
    height: 220px;
    object-fit: contain;
    background: #fff0f5;
    border-radius: 20px 20px 0 0;
    padding: 10px;
}
</style>
@endpush

@endsection