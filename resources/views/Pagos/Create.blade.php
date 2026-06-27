@extends('layouts.app')
@section('title', 'Pagar con Yape')
 
@push('styles')
<style>
.yape-hero {
    background: linear-gradient(135deg, #4A0080, #6B21A8, #9333EA, #7C3AED);
    padding: 3rem 0;
    color: white;
    position: relative;
    overflow: hidden;
}
 
.yape-hero::before {
    content: '💜💜💜';
    position: absolute;
    top: -30px; right: -20px;
    font-size: 8rem;
    opacity: .06;
    pointer-events: none;
}
 
.step-circle {
    width: 40px; height: 40px;
    background: var(--dulce-rose);
    color: white;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: 900;
    font-size: 1.1rem;
    flex-shrink: 0;
}
 
.step-item {
    background: white;
    border-radius: var(--radius);
    padding: 1rem 1.25rem;
    border: 2px solid var(--dulce-warm);
    transition: border-color .2s;
}
 
.step-item:hover { border-color: var(--dulce-rose); }
 
.upload-zone {
    border: 3px dashed var(--dulce-purple);
    border-radius: var(--radius);
    padding: 2rem;
    text-align: center;
    cursor: pointer;
    transition: all .2s;
    background: rgba(107,33,168,.03);
}
 
.upload-zone:hover {
    background: rgba(107,33,168,.08);
    border-color: #9333EA;
}
 
.upload-zone.has-file {
    border-color: #22c55e;
    background: rgba(34,197,94,.05);
}
 
.qr-placeholder {
    width: 200px; height: 200px;
    background: linear-gradient(135deg, #f0f0f0, #e0e0e0);
    border-radius: 12px;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    color: #666;
    font-size: .85rem;
    font-weight: 600;
    border: 3px dashed #ccc;
    margin: 0 auto;
}

.yape-qr-container{
    display:flex;
    align-items:center;
    justify-content:center;
    gap:30px;
    flex-wrap:wrap;
}
</style>
@endpush
 
@section('content')
 
<!-- HERO YAPE -->
<div class="yape-hero">
    <div class="container text-center">
        <div style="font-size:3rem; margin-bottom:1rem">💜</div>
        <h1 class="mb-2" style="font-family:'Playfair Display',serif;font-weight:900;font-size:2.5rem">
            Paga con Yape
        </h1>
        <p style="opacity:.85;font-size:1.1rem">Rápido, seguro y sin complicaciones</p>
        @if($pedido)
        <div class="d-inline-block mt-3 px-4 py-2 rounded-pill" style="background:rgba(255,255,255,.2);backdrop-filter:blur(4px)">
            Pedido: <strong>{{ $pedido->codigo }}</strong> &mdash;
            Total: <strong>S/ {{ number_format($pedido->total, 2) }}</strong>
        </div>
        @endif
    </div>
</div>
 
<div class="container py-5">
    <div class="row g-5 align-items-start">
 
        <!-- QR YAPE -->
        <div class="col-lg-5">
            <div class="card-dulce p-4 text-center sticky-top" style="top:80px">
                <div class="yape-card mb-4">
                    <div class="position-relative" style="z-index:1">
                        <div class="yape-logo mb-3"> Yape</div>
                        <div class="yape-qr-container">
                            @if(file_exists(public_path('images/yape-qr.png')))
                                <img src="{{ asset('images/yape-qr.png') }}" alt="QR Yape" style="width:200px;height:200px;object-fit:contain;display:block">
                            @else
                                <div class="qr-placeholder">
                                    <div style="font-size:3rem">📱</div>
                                    <div class="mt-2">Agrega tu QR aquí</div>
                                    <div style="font-size:.7rem;color:#999;margin-top:.3rem">public/images/yape-qr.png</div>
                                </div>
                            @endif
                        </div>
                        <div class="mt-3">
                            <div class="mt-4 text-center">
    <div class="fw-semibold fs-4" style="color:#5b217a">
        Escanea con tu app Yape
    </div>
</div>
                        </div>
                    </div>
                </div>
 
                <div class="p-3 rounded-3 text-start" style="background:var(--dulce-warm)">
                    <div class="fw-bold mb-1" style="font-size:.85rem">📋 Pasos para pagar:</div>
                    <ol class="small text-muted mb-0 ps-3">
                        <li>Abre tu app <strong>Yape</strong></li>
                        <li>Escanea el código QR o busca el número</li>
                        <li>Ingresa el monto exacto del pedido</li>
                        <li>Completa el pago y anota el <strong>código de operación</strong></li>
                        <li>Sube la captura del comprobante aquí</li>
                    </ol>
                </div>
            </div>
        </div>
 
        <!-- FORMULARIO PAGO -->
        <div class="col-lg-7">
            <h4 class="fw-bold mb-4" style="font-family:'Playfair Display',serif">Registrar tu pago </h4>
 
            <form action="{{ route('pagos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
 
                <!-- SELECCIÓN DE PEDIDO -->
                <div class="card-dulce p-4 mb-4">
                    <h6 class="fw-bold mb-3">1️ Selecciona tu pedido</h6>
 
                    @if($pedido)
                    <input type="hidden" name="pedido_id" value="{{ $pedido->id }}">
                    <div class="d-flex justify-content-between align-items-center p-3 rounded-3"
                         style="background:var(--dulce-warm);border:2px solid var(--dulce-rose)">
                        <div>
                            <div class="fw-bold">{{ $pedido->codigo }}</div>
                            <div class="text-muted small">{{ $pedido->cliente_nombre }} · {{ $pedido->cliente_telefono }}</div>
                            <div class="mt-1 small">
                                @foreach($pedido->detalles as $det)
                                <span class="me-2">{{ $det->cantidad }}× {{ Str::limit($det->producto_nombre, 20) }}</span>
                                @endforeach
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="badge-precio fs-5">S/ {{ number_format($pedido->total, 2) }}</div>
                        </div>
                    </div>
                    @else
                    <select name="pedido_id" class="form-select form-control-dulce @error('pedido_id') is-invalid @enderror" required>
                        <option value="">Selecciona tu pedido...</option>
                        @foreach($pedidos as $p)
                        <option value="{{ $p->id }}" {{ old('pedido_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->codigo }} — {{ $p->cliente_nombre }} — S/ {{ number_format($p->total, 2) }}
                        </option>
                        @endforeach
                    </select>
                    @error('pedido_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    @endif
                </div>
 
        
 
                <!-- COMPROBANTE -->
                <div class="card-dulce p-4 mb-4">
                    <h6 class="fw-bold mb-3">2 Sube la captura del comprobante</h6>
                    <div class="upload-zone" id="uploadZone" onclick="document.getElementById('comprobante').click()">
                        <div id="uploadContent">
                            <div style="font-size:3rem">📸</div>
                            <div class="fw-bold mt-2">Toca para subir la captura</div>
                            <div class="text-muted small mt-1">JPG, PNG o WEBP · Máx. 3MB</div>
                        </div>
                        <img id="comprobantePreview" class="d-none mt-3 rounded-3" style="max-width:300px;max-height:300px;object-fit:contain">
                    </div>
                    <input type="file" id="comprobante" name="comprobante_imagen"
                           accept="image/*" class="d-none @error('comprobante_imagen') is-invalid @enderror"
                           onchange="previewComprobante(this)" required>
                    @error('comprobante_imagen')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
 
                <!-- NOTAS -->
                <div class="card-dulce p-4 mb-4">
                    <h6 class="fw-bold mb-3">3 Notas adicionales (opcional)</h6>
                    <textarea name="notas" rows="2" class="form-control form-control-dulce"
                              placeholder="Alguna observación sobre tu pago...">{{ old('notas') }}</textarea>
                </div>
 
                <button type="submit" class="btn w-100 py-3 fw-bold"
                        style="background:linear-gradient(135deg,#6B21A8,#9333EA);color:white;border:none;border-radius:50px;font-size:1.1rem">
                     Enviar Pago Yape
                </button>
 
                <p class="text-center text-muted small mt-3">
                    <i class="bi bi-shield-check me-1"></i>
                    Tu pago será verificado y te confirmaremos pronto
                </p>
            </form>
        </div>
    </div>
</div>
@endsection
 
@push('scripts')
<script>
function previewComprobante(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const preview = document.getElementById('comprobantePreview');
            const zone    = document.getElementById('uploadZone');
            const content = document.getElementById('uploadContent');
            preview.src = e.target.result;
            preview.classList.remove('d-none');
            content.innerHTML = '<div class="text-success fw-bold small">✅ Imagen cargada — toca para cambiar</div>';
            zone.classList.add('has-file');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush