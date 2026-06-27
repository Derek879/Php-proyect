@extends('layouts.app')
@section('title', 'Detalle de Pago')
 
@section('content')
<div class="page-header">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small">
                <li class="breadcrumb-item"><a href="{{ route('pagos.index') }}" style="color:var(--dulce-rose)">Pagos</a></li>
                <li class="breadcrumb-item active">Detalle</li>
            </ol>
        </nav>
        <h1>💜 Detalle del Pago</h1>
    </div>
</div>
 
<div class="container py-4">
    <div class="row g-4 justify-content-center">
        <div class="col-md-6">
            <div class="card-dulce p-4">
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Pedido</span>
                    <a href="{{ route('pedidos.show', $pago->pedido) }}" style="color:var(--dulce-rose);font-weight:700">
                        {{ $pago->pedido->codigo }}
                    </a>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Cliente</span>
                    <span>{{ $pago->pedido->cliente_nombre }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Método</span>
                    <span class="fw-bold" style="color:var(--dulce-purple)">💜 Yape</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Código operación</span>
                    <code style="color:var(--dulce-purple);font-size:1.1rem">{{ $pago->codigo_operacion ?? '—' }}</code>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Monto</span>
                    <strong style="color:var(--dulce-rose);font-size:1.2rem">S/ {{ number_format($pago->monto, 2) }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-4">
                    <span class="text-muted">Estado</span>
                    <span class="badge bg-{{ $pago->estado_badge }} status-pill">{{ ucfirst($pago->estado) }}</span>
                </div>
 
                @if($pago->comprobante_imagen)
                <div class="text-center mb-4">
                    <p class="fw-bold small mb-2">Comprobante Yape</p>
                    <img src="{{ asset('storage/' . $pago->comprobante_imagen) }}"
                         class="img-fluid rounded-3" style="max-height:320px;border:3px solid var(--dulce-warm)">
                </div>
                @endif
 
                @if($pago->notas)
                <div class="p-3 rounded-3 mb-3" style="background:var(--dulce-cream)">
                    <div class="small fw-bold mb-1">Notas:</div>
                    <div class="text-muted small">{{ $pago->notas }}</div>
                </div>
                @endif
 
                <div class="d-flex gap-2">
                    <a href="{{ route('pagos.edit', $pago) }}" class="btn-gold btn flex-grow-1">
                        <i class="bi bi-pencil me-2"></i> Editar Pago
                    </a>
                    <a href="{{ route('pagos.index') }}" class="btn btn-outline-secondary rounded-pill px-3">Volver</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection