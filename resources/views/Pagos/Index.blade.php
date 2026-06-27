@extends('layouts.app')
@section('title', 'Pagos Yape')
 
@section('content')
<div class="page-header">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1><span style="color:var(--dulce-purple)">💜</span> Pagos Yape</h1>
                <p class="text-muted mb-0">Gestiona todos los pagos recibidos</p>
            </div>
            <a href="{{ route('pagos.create') }}" class="btn" style="background:var(--dulce-purple);color:white;border-radius:50px;font-weight:600;padding:.55rem 1.5rem">
                <i class="bi bi-plus-circle me-2"></i> Registrar Pago
            </a>
        </div>
 
        <form method="GET" class="mt-3">
            <div class="row g-2">
                <div class="col-md-4">
                    <select name="estado" class="form-select form-control-dulce">
                        <option value="">Todos los estados</option>
                        @foreach(['pendiente','verificando','confirmado','rechazado'] as $e)
                        <option value="{{ $e }}" {{ request('estado') == $e ? 'selected' : '' }}>{{ ucfirst($e) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn w-100 fw-600" style="background:var(--dulce-purple);color:white;border-radius:50px">Filtrar</button>
                </div>
            </div>
        </form>
    </div>
</div>
 
<div class="container py-4">
    <div class="table-dulce">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Pedido</th>
                    <th>Cliente</th>
                    <th>Código Operación</th>
                    <th>Monto</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pagos as $pago)
                <tr>
                    <td><code style="color:var(--dulce-rose)">{{ $pago->pedido->codigo }}</code></td>
                    <td>{{ $pago->pedido->cliente_nombre }}</td>
                    <td>
                        @if($pago->codigo_operacion)
                        <span class="badge" style="background:var(--dulce-warm);color:var(--dulce-brown);border-radius:50px;font-family:monospace">
                            {{ $pago->codigo_operacion }}
                        </span>
                        @else
                        <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td><strong style="color:var(--dulce-rose)">S/ {{ number_format($pago->monto, 2) }}</strong></td>
                    <td><span class="badge bg-{{ $pago->estado_badge }} status-pill">{{ ucfirst($pago->estado) }}</span></td>
                    <td class="text-muted small">{{ $pago->created_at->format('d/m/y H:i') }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('pagos.show', $pago) }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('pagos.edit', $pago) }}" class="btn btn-sm btn-outline-dulce">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('pagos.destroy', $pago) }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar este pago?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:50px">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <div style="font-size:3rem">💜</div>
                        <p class="mt-2">No hay pagos registrados aún</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $pagos->links() }}</div>
</div>
@endsection
 