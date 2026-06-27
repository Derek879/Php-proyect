@extends('layouts.app')
@section('title', 'Pedido ' . $pedido->codigo)

@section('content')
<div class="page-header">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small">
                <li class="breadcrumb-item"><a href="{{ route('pedidos.index') }}" style="color:var(--dulce-rose)">Pedidos</a></li>
                <li class="breadcrumb-item active">{{ $pedido->codigo }}</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <h1>Pedido <span style="color:var(--dulce-rose)">{{ $pedido->codigo }}</span></h1>
                <span class="badge bg-{{ $pedido->estado_badge }} status-pill fs-6">{{ $pedido->estado_label }}</span>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('pedidos.edit', $pedido) }}" class="btn-gold btn">
                    <i class="bi bi-pencil me-1"></i> Editar
                </a>
                @if(!$pedido->pago)
                <a href="{{ route('pagos.create', ['pedido' => $pedido->id]) }}"
                   class="btn" style="background:var(--dulce-purple);color:white;border-radius:50px;font-weight:600">
                    💜 Registrar Pago Yape
                </a>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="container py-4">

    @if(session('success'))
        <div class="alert alert-success rounded-4 mb-4 d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill fs-5"></i>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger rounded-4 mb-4 d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-circle-fill fs-5"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- TRACKER RAPPI --}}
    @if($pedido->estado !== 'cancelado')
    <div class="card-dulce p-4 mb-4">
        <h5 class="fw-bold mb-4 d-flex align-items-center gap-2">
            <i class="bi bi-geo-alt-fill" style="color:var(--dulce-rose)"></i>
            Seguimiento del Pedido
        </h5>

        @php
            $paso       = $pedido->estado_paso;
            $porcentaje = ($paso - 1) / 3 * 100;
        @endphp

        <div class="position-relative mb-3" style="padding: 0 12%">
            <div class="position-absolute top-50 translate-middle-y w-100 rounded"
                 style="height:6px; background:#e9ecef; left:0; right:0; z-index:0;"></div>
            <div class="position-absolute top-50 translate-middle-y rounded"
                 style="height:6px; width:{{ $porcentaje }}%; background: linear-gradient(90deg, var(--dulce-rose), var(--dulce-purple)); z-index:1; transition:width .5s ease;"></div>

            <div class="d-flex justify-content-between position-relative" style="z-index:2;">
                @php
                    $pasos = [
                        1 => ['icono' => '📥', 'label' => 'Recibido'],
                        2 => ['icono' => '👨‍🍳', 'label' => 'Preparando'],
                        3 => ['icono' => '🛵', 'label' => 'En camino'],
                        4 => ['icono' => '✅', 'label' => 'Entregado'],
                    ];
                @endphp

                @foreach($pasos as $num => $info)
                    @php
                        $completado = $paso >= $num;
                        $activo     = $paso === $num;
                    @endphp
                    <div class="d-flex flex-column align-items-center text-center" style="width:60px">
                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold mb-2"
                             style="
                                width:52px; height:52px;
                                font-size:1.4rem;
                                border: 3px solid {{ $completado ? 'var(--dulce-rose)' : '#dee2e6' }};
                                background: {{ $activo ? 'linear-gradient(135deg,var(--dulce-rose),var(--dulce-purple))' : ($completado ? 'var(--dulce-warm)' : '#f8f9fa') }};
                                box-shadow: {{ $activo ? '0 4px 15px rgba(0,0,0,.15)' : 'none' }};
                                transform: {{ $activo ? 'scale(1.15)' : 'scale(1)' }};
                                transition: all .3s;
                             ">
                            {{ $info['icono'] }}
                        </div>
                        <small class="fw-{{ $activo ? 'bold' : 'normal' }}"
                               style="font-size:.72rem; color:{{ $completado ? 'var(--dulce-brown)' : '#adb5bd' }}; line-height:1.2;">
                            {{ $info['label'] }}
                        </small>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="text-center mt-4 py-3 rounded-4" style="background:var(--dulce-warm)">
            @php
                $mensajes = [
                    'recibido'   => ['msg' => '¡Tu pedido fue recibido! Lo estamos revisando.', 'color' => '#0dcaf0'],
                    'preparando' => ['msg' => 'Nuestro equipo está preparando tu pedido con mucho cariño 💕', 'color' => '#ffc107'],
                    'en_camino'  => ['msg' => '¡Tu pedido está en camino! El repartidor ya salió 🛵', 'color' => '#0d6efd'],
                    'entregado'  => ['msg' => '¡Pedido entregado! Esperamos que lo disfrutes mucho 🎉', 'color' => '#198754'],
                ];
                $info = $mensajes[$pedido->estado] ?? ['msg' => '', 'color' => '#6c757d'];
            @endphp
            <p class="mb-0 fw-bold" style="color:{{ $info['color'] }}; font-size:1rem">
                {{ $info['msg'] }}
            </p>
        </div>

        @auth
        @if(Auth::user()->role === 'admin' && $pedido->es_activo)
        <div class="d-flex gap-2 mt-4 flex-wrap justify-content-center">

            @if($pedido->siguiente_estado)
            <form action="{{ route('pedidos.avanzar', $pedido) }}" method="POST">
                @csrf
                <button type="submit" class="btn fw-bold px-4 py-2"
                        style="background:linear-gradient(135deg,var(--dulce-rose),var(--dulce-purple));color:white;border-radius:50px;border:none">
                    @php
                        $nextLabel = [
                            'preparando' => '👨‍🍳 Iniciar preparación',
                            'en_camino'  => '🛵 Marcar en camino',
                            'entregado'  => '✅ Confirmar entrega',
                        ][$pedido->siguiente_estado] ?? 'Avanzar estado';
                    @endphp
                    {{ $nextLabel }}
                </button>
            </form>
            @endif

            <form action="{{ route('pedidos.cancelar', $pedido) }}" method="POST"
                  onsubmit="return confirm('¿Cancelar este pedido?')">
                @csrf
                <button type="submit" class="btn btn-outline-danger fw-bold px-4 py-2"
                        style="border-radius:50px">
                    ❌ Cancelar pedido
                </button>
            </form>

        </div>
        @endif
        @endauth
    </div>

    @else
    <div class="card-dulce p-4 mb-4 text-center" style="border: 2px solid #dc3545 !important;">
        <div style="font-size:3rem">❌</div>
        <h5 class="fw-bold text-danger mt-2">Pedido Cancelado</h5>
        <p class="text-muted mb-0">Este pedido fue cancelado y no puede procesarse.</p>
    </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-7">

            <div class="card-dulce p-4 mb-4">
                <h5 class="fw-bold mb-3">🛍️ Productos del Pedido</h5>
                @foreach($pedido->detalles as $det)
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom"
                     style="border-color:var(--dulce-warm)!important">
                    <div>
                        <div class="fw-bold">{{ $det->producto_nombre }}</div>
                        <div class="text-muted small">{{ $det->cantidad }} × S/ {{ number_format($det->precio_unitario, 2) }}</div>
                    </div>
                    <span class="fw-bold" style="color:var(--dulce-rose)">S/ {{ number_format($det->subtotal, 2) }}</span>
                </div>
                @endforeach

                <div class="mt-3 pt-2">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Subtotal</span>
                        <span>S/ {{ number_format($pedido->subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Delivery</span>
                        <span>S/ {{ number_format($pedido->delivery, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between p-3 rounded-3 fw-bold"
                         style="background:var(--dulce-warm)">
                        <span style="font-size:1.1rem">TOTAL</span>
                        <span style="color:var(--dulce-rose);font-size:1.3rem">S/ {{ number_format($pedido->total, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="card-dulce p-4">
                <h5 class="fw-bold mb-3">👤 Información del Cliente</h5>
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="text-muted small mb-1">Nombre</div>
                        <div class="fw-bold">{{ $pedido->cliente_nombre }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small mb-1">Teléfono</div>
                        <div class="fw-bold">{{ $pedido->cliente_telefono }}</div>
                    </div>
                    @if($pedido->cliente_email)
                    <div class="col-sm-6">
                        <div class="text-muted small mb-1">Email</div>
                        <div>{{ $pedido->cliente_email }}</div>
                    </div>
                    @endif
                    @if($pedido->fecha_entrega)
                    <div class="col-sm-6">
                        <div class="text-muted small mb-1">Fecha de entrega</div>
                        <div>{{ $pedido->fecha_entrega->format('d/m/Y H:i') }}</div>
                    </div>
                    @endif
                    @if($pedido->direccion_entrega)
                    <div class="col-12">
                        <div class="text-muted small mb-1">Dirección</div>
                        <div>{{ $pedido->direccion_entrega }}</div>
                    </div>
                    @endif
                    @if($pedido->notas)
                    <div class="col-12">
                        <div class="text-muted small mb-1">Notas</div>
                        <div class="p-2 rounded" style="background:var(--dulce-cream)">{{ $pedido->notas }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            @if($pedido->pago)
            <div class="card-dulce p-4">
                <h5 class="fw-bold mb-3">💜 Pago por Yape</h5>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Estado</span>
                    <span class="badge bg-{{ $pedido->pago->estado_badge }} status-pill">{{ ucfirst($pedido->pago->estado) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Código operación</span>
                    <code style="color:var(--dulce-purple)">{{ $pedido->pago->codigo_operacion ?? '—' }}</code>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Monto</span>
                    <strong style="color:var(--dulce-rose)">S/ {{ number_format($pedido->pago->monto, 2) }}</strong>
                </div>
                @if($pedido->pago->comprobante_imagen)
                <div class="text-center">
                    <p class="small fw-bold mb-2">Comprobante Yape</p>
                    <img src="{{ asset('storage/' . $pedido->pago->comprobante_imagen) }}"
                         class="img-fluid rounded-3" style="max-height:250px;border:3px solid var(--dulce-warm)">
                </div>
                @endif
                @auth
                @if(Auth::user()->role === 'admin')
                <div class="mt-3">
                    <a href="{{ route('pagos.edit', $pedido->pago) }}" class="btn-gold btn w-100">
                        <i class="bi bi-pencil me-2"></i> Gestionar Pago
                    </a>
                </div>
                @endif
                @endauth
            </div>
            @else
            <div class="yape-card">
                <div class="yape-logo mb-3">💜 Yape</div>
                <p class="mb-4" style="opacity:.9">Este pedido aún no tiene pago registrado.</p>
                <a href="{{ route('pagos.create', ['pedido' => $pedido->id]) }}"
                   class="btn w-100 py-2 fw-bold" style="background:white;color:#6B21A8;border-radius:50px">
                    <i class="bi bi-phone me-2"></i> Registrar Pago Yape
                </a>
            </div>
            @endif

            @auth
            @if(Auth::user()->role === 'admin')
            <div class="card-dulce p-4 mt-4">
                <h6 class="fw-bold mb-3">⚙️ Cambio manual de estado</h6>
                <form action="{{ route('pedidos.updateEstado', $pedido) }}" method="POST">
                    @csrf
                    <div class="d-flex gap-2">
                        <select name="estado" class="form-select form-control-dulce">
                            @foreach(\App\Models\Pedido::ESTADOS as $e)
                            <option value="{{ $e }}" {{ $pedido->estado === $e ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $e)) }}
                            </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-sm fw-bold px-3"
                                style="background:var(--dulce-rose);color:white;border-radius:50px;border:none;white-space:nowrap">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
            @endif
            @endauth
        </div>
    </div>
</div>
@endsection