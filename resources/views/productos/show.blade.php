@extends('layouts.app')
@section('title', $producto->nombre)

@section('content')
<div class="page-header">
    <div class="container">
        <a href="{{ route('productos.index') }}" style="color:var(--dulce-rose)">
            ← Volver a productos
        </a>

        <h1 class="mt-3">{{ $producto->nombre }} 🍰</h1>
    </div>
</div>

<div class="container py-4">
    <div class="card-dulce p-4">
        <div class="row g-4 align-items-center">

            <div class="col-md-5">
                @if($producto->imagen)
                    <img src="{{ asset('storage/' . $producto->imagen) }}"
                         class="img-fluid rounded"
                         style="max-height:350px;object-fit:cover;width:100%;">
                @else
                    <div class="text-center p-5 rounded" style="background:#fff0f5;">
                        Sin imagen
                    </div>
                @endif
            </div>

            <div class="col-md-7">
                <span class="badge rounded-pill mb-3" style="background:#ffe4ec;color:#e83e8c;">
                    {{ $producto->categoria->nombre ?? 'Sin categoría' }}
                </span>

                <h2 class="fw-bold" style="color:#7c2d12;">
                    {{ $producto->nombre }}
                </h2>

                <p class="text-muted">
                    {{ $producto->descripcion ?? 'Sin descripción.' }}
                </p>

                <h3 class="fw-bold" style="color:var(--dulce-rose);">
                    S/ {{ number_format($producto->precio, 2) }}
                </h3>

                <p class="mt-3">
                    <strong>Stock:</strong> {{ $producto->stock }}
                </p>

                <p>
                    <strong>Estado:</strong>
                    {{ $producto->disponible ? 'Disponible' : 'No disponible' }}
                </p>

                @auth
                    @if(auth()->user()->role === 'admin')

                        <a href="{{ route('productos.edit', $producto) }}"
                           class="btn-rose btn mt-3">
                            Editar Producto
                        </a>

                    @else
                        {{-- CLIENTE --}}
                        @php
                            $tieneEnCarrito = \App\Models\CarritoItem::where('user_id', auth()->id())->exists();
                        @endphp

                        @if($producto->disponible)
                            @if($tieneEnCarrito)
                                {{-- Ya hay items en el carrito → ir a pagar --}}
                                <a href="{{ route('carrito.checkout') }}"
                                   class="btn-rose btn mt-3">
                                    🛒 Comprar
                                </a>
                            @else
                                {{-- Carrito vacío → agregar este producto --}}
                                <form action="{{ route('carrito.agregar') }}" method="POST" class="mt-3">
                                    @csrf
                                    <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                                    <input type="hidden" name="cantidad" value="1">
                                    <button type="submit" class="btn-rose btn">
                                        Agregar al carrito
                                    </button>
                                </form>
                            @endif
                        @else
                            <button class="btn btn-secondary mt-3" disabled>
                                No disponible
                            </button>
                        @endif

                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-dark rounded-pill mt-3">
                        Iniciar sesión para comprar
                    </a>
                @endauth
            </div>

        </div>
    </div>
</div>
@endsection