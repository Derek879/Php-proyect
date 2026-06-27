@extends('layouts.app')
@section('title', 'Nuevo Pedido')

@push('styles')

<style>
.cart-item {
    background: white;
    border: 1px solid var(--dulce-warm);
    border-radius: 12px;
    padding: .75rem 1rem;
    margin-bottom: .5rem;
}
 
.total-row {
    background: var(--dulce-rose);
    color: white;
    border-radius: var(--radius);
    padding: 1rem 1.25rem;
}
</style>
@endpush
 
@section('content')
<div class="page-header">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small">
                <li class="breadcrumb-item"><a href="{{ route('pedidos.index') }}" style="color:var(--dulce-rose)">Pedidos</a></li>
                <li class="breadcrumb-item active">Nuevo</li>
            </ol>
        </nav>
        <h1>Nuevo Pedido 🛍️</h1>
        <p class="text-muted mb-0">El pago se realizará por <strong style="color:var(--dulce-purple)">💜 Yape</strong></p>
    </div>
</div>
 
<div class="container py-4">
    <form action="{{ route('pedidos.store') }}" method="POST" id="formPedido">
        @csrf
        <div class="row g-4">
            <!-- DATOS DEL CLIENTE -->
            <div class="col-lg-6">
                <div class="card-dulce p-4 mb-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-person-circle me-2" style="color:var(--dulce-rose)"></i>Datos del Cliente</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-dulce">Nombre completo <span class="text-danger">*</span></label>
                            <input type="text" name="cliente_nombre" class="form-control form-control-dulce @error('cliente_nombre') is-invalid @enderror"
                                   value="{{ old('cliente_nombre') }}" placeholder="Nombre del cliente">
                            @error('cliente_nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-dulce">Teléfono <span class="text-danger">*</span></label>
                            <input type="text" name="cliente_telefono" class="form-control form-control-dulce @error('cliente_telefono') is-invalid @enderror"
                                   value="{{ old('cliente_telefono') }}" placeholder="Ej: 999 888 777">
                            @error('cliente_telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-dulce">Email</label>
                            <input type="email" name="cliente_email" class="form-control form-control-dulce"
                                   value="{{ old('cliente_email') }}" placeholder="correo@ejemplo.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-dulce">Fecha de entrega</label>
                            <input type="datetime-local" name="fecha_entrega" class="form-control form-control-dulce"
                                   value="{{ old('fecha_entrega') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label-dulce">Dirección de entrega</label>
                            <input type="text" name="direccion_entrega" class="form-control form-control-dulce"
                                   value="{{ old('direccion_entrega') }}" placeholder="Calle, número, distrito...">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-dulce">Delivery (S/)</label>
                            <input type="number" name="delivery" id="delivery" step="0.50" min="0"
                                   class="form-control form-control-dulce" value="{{ old('delivery', 5) }}"
                                   readonly style="background:#f5f5f5;cursor:not-allowed">
                        </div>
                        <div class="col-12">
                            <label class="form-label-dulce">Notas adicionales</label>
                            <textarea name="notas" rows="2" class="form-control form-control-dulce"
                                      placeholder="Sabores, decoraciones especiales...">{{ old('notas') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
 
            <!-- RESUMEN -->
            <div class="col-lg-6">
                <div class="card-dulce p-4 sticky-top" style="top:80px">
                    <h5 class="fw-bold mb-3"><i class="bi bi-receipt me-2" style="color:var(--dulce-rose)"></i>Resumen del Pedido</h5>

                    @error('productos')<div class="alert alert-danger rounded-3 small py-2">{{ $message }}</div>@enderror

                    <div id="cartItems">
                        <p class="text-muted text-center py-3 small" id="emptyCart">
                            Tu carrito está vacío. <a href="{{ route('productos.index') }}">Ver productos</a>
                        </p>
                    </div>

                    <div id="hiddenInputs"></div>
 
                    <hr style="border-color:var(--dulce-warm)">
 
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Subtotal</span>
                        <span id="subtotalDisplay">S/ 0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Delivery</span>
                        <span id="deliveryDisplay">S/ 5.00</span>
                    </div>
                    <div class="total-row d-flex justify-content-between align-items-center">
                        <span class="fw-bold fs-5">TOTAL</span>
                        <span class="fw-bold fs-4" id="totalDisplay">S/ 5.00</span>
                    </div>
 
                    <!-- YAPE INFO -->
                    <div class="mt-3 p-3 rounded-3 text-center" style="background:linear-gradient(135deg,#6B21A8,#9333EA);color:white">
                        <div style="font-size:1.5rem">💜</div>
                        <div class="fw-bold">Pago exclusivo por Yape</div>
                        <div style="font-size:.8rem;opacity:.8">Al confirmar, te pediremos el comprobante</div>
                    </div>
 
                    <button type="submit" class="btn-rose btn w-100 mt-3 py-3 fs-5 fw-bold" id="btnPedir">
                        <i class="bi bi-bag-check me-2"></i> Confirmar Pedido
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
 
@push('scripts')
<script>
// Items que ya tenía el usuario en su carrito (vienen del controlador: producto_id, nombre, precio, cantidad)
const selected = {};
(@json($carritoItems ?? [])).forEach(item => {
    selected[item.producto_id] = {
        nombre: item.nombre,
        precio: parseFloat(item.precio),
        cant: item.cantidad,
    };
});

function renderCart() {
    const cartDiv = document.getElementById('cartItems');
    const hiddenDiv = document.getElementById('hiddenInputs');
    hiddenDiv.innerHTML = '';

    const keys = Object.keys(selected);
    if (keys.length === 0) {
        cartDiv.innerHTML = '<p class="text-muted text-center py-3 small">Tu carrito está vacío. <a href="{{ route("productos.index") }}">Ver productos</a></p>';
        return;
    }

    cartDiv.innerHTML = '';
    keys.forEach(id => {
        const item = selected[id];
        const sub = (item.precio * item.cant).toFixed(2);

        cartDiv.innerHTML += `
            <div class="cart-item d-flex justify-content-between align-items-center">
                <div>
                    <div class="fw-bold small">${item.nombre}</div>
                    <div class="text-muted" style="font-size:.75rem">${item.cant} × S/ ${item.precio.toFixed(2)}</div>
                </div>
                <span class="fw-bold" style="color:var(--dulce-rose)">S/ ${sub}</span>
            </div>
        `;
        hiddenDiv.innerHTML += `
            <input type="hidden" name="productos[]" value="${id}">
            <input type="hidden" name="cantidades[]" value="${item.cant}">
        `;
    });
}

function recalcular() {
    let subtotal = 0;
    Object.values(selected).forEach(item => {
        subtotal += item.precio * item.cant;
    });
    const delivery = parseFloat(document.getElementById('delivery').value || 0);
    const total = subtotal + delivery;

    document.getElementById('subtotalDisplay').textContent = 'S/ ' + subtotal.toFixed(2);
    document.getElementById('deliveryDisplay').textContent  = 'S/ ' + delivery.toFixed(2);
    document.getElementById('totalDisplay').textContent     = 'S/ ' + total.toFixed(2);
}

document.getElementById('delivery').addEventListener('input', recalcular);
renderCart();
recalcular();
</script>
@endpush