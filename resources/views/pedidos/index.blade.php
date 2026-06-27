@extends('layouts.app')
@section('title', 'Pedidos')

@section('content')
<div class="page-header">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1><i class="bi bi-bag-heart me-2" style="color:var(--dulce-rose)"></i>Pedidos</h1>
                <p class="text-muted mb-0">Gestiona todos los pedidos</p>
            </div>
            <a href="{{ route('pedidos.create') }}" class="btn-rose btn">
                <i class="bi bi-plus-circle me-2"></i> Nuevo Pedido
            </a>
        </div>

        <form method="GET" class="mt-3">
            <div class="row g-2">
                <div class="col-md-5">
                    <input type="text" name="buscar" class="form-control form-control-dulce"
                           placeholder="🔍 Buscar por código o cliente..." value="{{ request('buscar') }}">
                </div>
                <div class="col-md-3">
                    <select name="estado" class="form-select form-control-dulce">
                        <option value="">Todos los estados</option>
                        @foreach(['recibido','preparando','en_camino','entregado','cancelado'] as $e)
                        <option value="{{ $e }}" {{ request('estado') == $e ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$e)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn-rose btn w-100">Filtrar</button>
                </div>
                @if(request()->hasAny(['buscar','estado']))
                <div class="col-md-2">
                    <a href="{{ route('pedidos.index') }}" class="btn btn-outline-secondary rounded-pill w-100">Limpiar</a>
                </div>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="container py-4">
    <div class="table-dulce">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Cliente</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Pago</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pedidos as $pedido)
                <tr>
                    <td><code style="color:var(--dulce-rose);font-weight:700">{{ $pedido->codigo }}</code></td>
                    <td>
                        <div class="fw-bold">{{ $pedido->cliente_nombre }}</div>
                        <div class="text-muted small">📞 {{ $pedido->cliente_telefono }}</div>
                    </td>
                    <td><strong style="color:var(--dulce-brown)">S/ {{ number_format($pedido->total, 2) }}</strong></td>
                    <td>
                        <span class="badge bg-{{ $pedido->estado_badge }} status-pill">{{ $pedido->estado_label }}</span>
                    </td>
                    <td>
                        @if($pedido->pago)
                            <span class="badge bg-{{ $pedido->pago->estado_badge }} status-pill">
                                {{ ucfirst($pedido->pago->estado) }}
                            </span>
                        @else
                            <a href="{{ route('pagos.create', ['pedido' => $pedido->id]) }}"
                               class="badge text-decoration-none" style="background:var(--dulce-purple);color:white;border-radius:50px;font-size:.7rem;padding:.3rem .7rem">
                                💜 Registrar Yape
                            </a>
                        @endif
                    </td>
                    <td class="text-muted small">{{ $pedido->created_at->format('d/m/y H:i') }}</td>
                    <td>
                        <div class="d-flex gap-1 flex-wrap">
                            {{-- Botón Ver Progreso --}}
                            <button class="btn btn-sm fw-bold px-3"
                                    style="background:linear-gradient(135deg,var(--dulce-rose),var(--dulce-purple));color:white;border-radius:50px;border:none;font-size:.75rem"
                                    onclick="verProgreso(
                                        '{{ $pedido->codigo }}',
                                        '{{ $pedido->estado }}',
                                        {{ $pedido->estado_paso }},
                                        '{{ $pedido->cliente_nombre }}'
                                    )">
                                🛵 Ver progreso
                            </button>

                            <a href="{{ route('pedidos.show', $pedido) }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('pedidos.edit', $pedido) }}" class="btn btn-sm btn-outline-dulce">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            @endif
                            <form action="{{ route('pedidos.destroy', $pedido) }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar este pedido?')">
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
                        <div style="font-size:3rem">📦</div>
                        <p class="mt-2">No hay pedidos. <a href="{{ route('pedidos.create') }}">Crear el primero</a></p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $pedidos->links() }}</div>
</div>

{{-- MODAL PROGRESO --}}
<div class="modal fade" id="modalProgreso" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:20px;border:none">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold" id="modalTitulo">Seguimiento del Pedido</h5>
                    <p class="text-muted small mb-0" id="modalCliente"></p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-2 pb-4">

                {{-- Barra de progreso --}}
                <div class="position-relative my-4" style="padding: 0 10%">
                    <div class="position-absolute top-50 translate-middle-y w-100 rounded"
                         style="height:6px;background:#e9ecef;left:0;right:0;z-index:0;"></div>
                    <div class="position-absolute top-50 translate-middle-y rounded"
                         id="barraProgreso"
                         style="height:6px;width:0%;background:linear-gradient(90deg,var(--dulce-rose),var(--dulce-purple));z-index:1;transition:width .5s ease;"></div>

                    <div class="d-flex justify-content-between position-relative" style="z-index:2;">
                        @php
                            $pasosModal = [
                                1 => ['icono' => '📥', 'label' => 'Recibido'],
                                2 => ['icono' => '👨‍🍳', 'label' => 'Preparando'],
                                3 => ['icono' => '🛵', 'label' => 'En camino'],
                                4 => ['icono' => '✅', 'label' => 'Entregado'],
                            ];
                        @endphp
                        @foreach($pasosModal as $num => $info)
                        <div class="d-flex flex-column align-items-center text-center paso-icono" data-paso="{{ $num }}" style="width:60px">
                            <div class="rounded-circle d-flex align-items-center justify-content-center mb-2"
                                 style="width:50px;height:50px;font-size:1.3rem;border:3px solid #dee2e6;background:#f8f9fa;transition:all .3s">
                                {{ $info['icono'] }}
                            </div>
                            <small style="font-size:.72rem;line-height:1.2;color:#adb5bd">{{ $info['label'] }}</small>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Mensaje estado --}}
                <div class="text-center py-3 rounded-4" style="background:var(--dulce-warm)">
                    <p class="mb-0 fw-bold" id="modalMensaje" style="font-size:1rem"></p>
                </div>

                {{-- Estado cancelado --}}
                <div id="modalCancelado" class="text-center py-3 rounded-4 d-none" style="border:2px solid #dc3545">
                    <div style="font-size:2rem">❌</div>
                    <p class="fw-bold text-danger mb-0 mt-1">Pedido Cancelado</p>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function verProgreso(codigo, estado, paso, cliente) {
    const mensajes = {
        recibido:   { msg: '¡Tu pedido fue recibido! Lo estamos revisando.', color: '#0dcaf0' },
        preparando: { msg: 'Nuestro equipo está preparando tu pedido con mucho cariño 💕', color: '#ffc107' },
        en_camino:  { msg: '¡Tu pedido está en camino! El repartidor ya salió 🛵', color: '#0d6efd' },
        entregado:  { msg: '¡Pedido entregado! Esperamos que lo disfrutes mucho 🎉', color: '#198754' },
        cancelado:  { msg: '', color: '#dc3545' },
    };

    document.getElementById('modalTitulo').textContent = 'Pedido ' + codigo;
    document.getElementById('modalCliente').textContent = '👤 ' + cliente;

    const porcentaje = estado === 'cancelado' ? 0 : ((paso - 1) / 3 * 100);
    document.getElementById('barraProgreso').style.width = porcentaje + '%';

    // Íconos
    document.querySelectorAll('.paso-icono').forEach(el => {
        const n = parseInt(el.dataset.paso);
        const circulo = el.querySelector('div');
        const label   = el.querySelector('small');
        const activo  = paso === n;
        const completado = paso >= n && estado !== 'cancelado';

        circulo.style.border = completado ? '3px solid var(--dulce-rose)' : '3px solid #dee2e6';
        circulo.style.background = activo && estado !== 'cancelado'
            ? 'linear-gradient(135deg,var(--dulce-rose),var(--dulce-purple))'
            : (completado ? 'var(--dulce-warm)' : '#f8f9fa');
        circulo.style.transform = activo && estado !== 'cancelado' ? 'scale(1.15)' : 'scale(1)';
        label.style.color = completado ? 'var(--dulce-brown)' : '#adb5bd';
        label.style.fontWeight = activo ? 'bold' : 'normal';
    });

    // Mensaje
    const info = mensajes[estado] || { msg: '', color: '#6c757d' };
    const msgEl = document.getElementById('modalMensaje');
    msgEl.textContent = info.msg;
    msgEl.style.color = info.color;

    // Cancelado
    document.getElementById('modalCancelado').classList.toggle('d-none', estado !== 'cancelado');
    document.querySelector('#modalProgreso .rounded-4').classList.toggle('d-none', estado === 'cancelado');

    new bootstrap.Modal(document.getElementById('modalProgreso')).show();
}
</script>
@endpush