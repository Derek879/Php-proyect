@extends('layouts.app')
@section('title', 'Editar Pedido')
 
@section('content')
<div class="page-header">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small">
                <li class="breadcrumb-item"><a href="{{ route('pedidos.index') }}" style="color:var(--dulce-rose)">Pedidos</a></li>
                <li class="breadcrumb-item"><a href="{{ route('pedidos.show', $pedido) }}" style="color:var(--dulce-rose)">{{ $pedido->codigo }}</a></li>
                <li class="breadcrumb-item active">Editar</li>
            </ol>
        </nav>
        <h1>Editar Pedido ✏️</h1>
    </div>
</div>
 
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card-dulce p-4">
                <form action="{{ route('pedidos.update', $pedido) }}" method="POST">
                    @csrf @method('PUT')
 
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-dulce">Estado del Pedido</label>
                            <select name="estado" class="form-select form-control-dulce">
                                @foreach(['pendiente','confirmado','preparando','listo','entregado','cancelado'] as $e)
                                <option value="{{ $e }}" {{ $pedido->estado == $e ? 'selected' : '' }}>{{ ucfirst($e) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-dulce">Delivery (S/)</label>
                            <input type="number" name="delivery" step="0.50" min="0"
                                   class="form-control form-control-dulce" value="{{ old('delivery', $pedido->delivery) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-dulce">Nombre del cliente</label>
                            <input type="text" name="cliente_nombre" class="form-control form-control-dulce @error('cliente_nombre') is-invalid @enderror"
                                   value="{{ old('cliente_nombre', $pedido->cliente_nombre) }}">
                            @error('cliente_nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-dulce">Teléfono</label>
                            <input type="text" name="cliente_telefono" class="form-control form-control-dulce @error('cliente_telefono') is-invalid @enderror"
                                   value="{{ old('cliente_telefono', $pedido->cliente_telefono) }}">
                            @error('cliente_telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-dulce">Email</label>
                            <input type="email" name="cliente_email" class="form-control form-control-dulce"
                                   value="{{ old('cliente_email', $pedido->cliente_email) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-dulce">Fecha de entrega</label>
                            <input type="datetime-local" name="fecha_entrega" class="form-control form-control-dulce"
                                   value="{{ old('fecha_entrega', $pedido->fecha_entrega?->format('Y-m-d\TH:i')) }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label-dulce">Dirección de entrega</label>
                            <input type="text" name="direccion_entrega" class="form-control form-control-dulce"
                                   value="{{ old('direccion_entrega', $pedido->direccion_entrega) }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label-dulce">Notas</label>
                            <textarea name="notas" rows="2" class="form-control form-control-dulce">{{ old('notas', $pedido->notas) }}</textarea>
                        </div>
                    </div>
 
                    <div class="d-flex gap-3 mt-4">
                        <button type="submit" class="btn-gold btn px-4">
                            <i class="bi bi-check-circle me-2"></i> Actualizar Pedido
                        </button>
                        <a href="{{ route('pedidos.show', $pedido) }}" class="btn btn-outline-secondary rounded-pill px-4">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
 