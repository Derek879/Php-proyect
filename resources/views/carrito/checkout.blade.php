@extends('layouts.app')
@section('title', 'Mi Carrito')

@section('content')
<div class="page-header">
    <div class="container">
        <a href="{{ route('productos.index') }}" style="color:var(--dulce-rose)">
            ← Seguir comprando
        </a>
        <h1 class="mt-3">🛒 Mi Carrito</h1>
    </div>
</div>

<div class="container py-4">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($items->isEmpty())

        <div class="card-dulce p-5 text-center">
            <p class="mb-3">Tu carrito está vacío.</p>
            <a href="{{ route('productos.index') }}" class="btn-rose btn">
                Ver productos
            </a>
        </div>

    @else

        <div class="card-dulce p-4">

            @php $total = 0; @endphp

            @foreach($items as $item)
                @php $subtotal = $item->producto->precio * $item->cantidad; $total += $subtotal; @endphp

                <div class="row align-items-center mb-3 pb-3" style="border-bottom:1px solid #f5e6e8">

                    <div class="col-2 col-md-1">
                        @if($item->producto->imagen)
                            <img src="{{ $item->producto->imagen_url }}"
                                 class="img-fluid rounded" style="max-height:60px;object-fit:cover;">
                        @else
                            <div class="text-center" style="font-size:1.8rem;">🍰</div>
                        @endif
                    </div>

                    <div class="col-4 col-md-5">
                        <div class="fw-bold">{{ $item->producto->nombre }}</div>
                        <div class="text-muted small">S/ {{ number_format($item->producto->precio, 2) }} c/u</div>
                    </div>

                    <div class="col-3 col-md-3">
                        <form action="{{ route('carrito.cantidad', $item->producto_id) }}" method="POST"
                              class="d-flex align-items-center gap-2">
                            @csrf
                            @method('PATCH')
                            <button type="submit" name="cantidad" value="{{ max(1, $item->cantidad - 1) }}"
                                    class="btn btn-sm btn-outline-secondary rounded-circle">−</button>

                            <span class="fw-bold">{{ $item->cantidad }}</span>

                            <button type="submit" name="cantidad" value="{{ $item->cantidad + 1 }}"
                                    class="btn btn-sm btn-outline-secondary rounded-circle">+</button>
                        </form>
                    </div>

                    <div class="col-2 col-md-2 text-end fw-bold" style="color:var(--dulce-rose)">
                        S/ {{ number_format($subtotal, 2) }}
                    </div>

                    <div class="col-1 text-end">
                        <form action="{{ route('carrito.quitar', $item->producto_id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm" style="color:#dc3545;border:none;background:none;font-size:1.2rem;" title="Quitar">×</button>
                        </form>
                    </div>

                </div>

            @endforeach

            <div class="d-flex justify-content-between align-items-center mt-4">
                <form action="{{ route('carrito.vaciar') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger rounded-pill">
                        Vaciar carrito
                    </button>
                </form>

                <h4 class="fw-bold mb-0">
                    Total: <span style="color:var(--dulce-rose)">S/ {{ number_format($total, 2) }}</span>
                </h4>
            </div>

            <div class="text-end mt-4">
                <a href="{{ route('pedidos.create') }}" class="btn-rose btn">
                    Confirmar pedido
                </a>
            </div>

        </div>

    @endif

</div>
@endsection