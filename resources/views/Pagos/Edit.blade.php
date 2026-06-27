@extends('layouts.app')
@section('title', 'Gestionar Pago')
 
@section('content')
<div class="page-header">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small">
                <li class="breadcrumb-item"><a href="{{ route('pagos.index') }}" style="color:var(--dulce-rose)">Pagos</a></li>
                <li class="breadcrumb-item active">Gestionar</li>
            </ol>
        </nav>
        <h1>Gestionar Pago ✏️</h1>
    </div>
</div>
 
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <!-- COMPROBANTE ACTUAL -->
            @if($pago->comprobante_imagen)
            <div class="card-dulce p-4 mb-4 text-center">
                <h6 class="fw-bold mb-3">📸 Comprobante del cliente</h6>
                <img src="{{ asset('storage/' . $pago->comprobante_imagen) }}"
                     class="img-fluid rounded-3" style="max-height:300px;border:3px solid var(--dulce-warm)">
                <div class="mt-2">
                    <code style="color:var(--dulce-purple);font-size:1.1rem">{{ $pago->codigo_operacion }}</code>
                </div>
                <div class="fw-bold mt-1" style="color:var(--dulce-rose)">S/ {{ number_format($pago->monto, 2) }}</div>
            </div>
            @endif
 
            <div class="card-dulce p-4">
                <form action="{{ route('pagos.update', $pago) }}" method="POST">
                    @csrf @method('PUT')
 
                    <div class="mb-4">
                        <label class="form-label-dulce">Estado del Pago <span class="text-danger">*</span></label>
                        <div class="row g-2">
                            @foreach([
                                ['pendiente',   'warning', '⏳', 'En espera'],
                                ['verificando', 'info',    '🔍', 'Verificando'],
                                ['confirmado',  'success', '✅', 'Confirmado'],
                                ['rechazado',   'danger',  '❌', 'Rechazado'],
                            ] as $opt)
                            <div class="col-6">
                                <label class="d-flex align-items-center gap-2 p-3 rounded-3 cursor-pointer"
                                       style="border:2px solid var(--dulce-warm);cursor:pointer;transition:all .2s"
                                       onclick="this.style.borderColor='var(--dulce-rose)'">
                                    <input type="radio" name="estado" value="{{ $opt[0] }}"
                                           {{ $pago->estado == $opt[0] ? 'checked' : '' }}
                                           class="form-check-input">
                                    <span>{{ $opt[2] }} {{ $opt[3] }}</span>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
 
                    <div class="mb-3">
                        <label class="form-label-dulce">Código de Operación</label>
                        <input type="text" name="codigo_operacion" class="form-control form-control-dulce"
                               value="{{ old('codigo_operacion', $pago->codigo_operacion) }}"
                               style="font-family:monospace;letter-spacing:2px">
                    </div>
 
                    <div class="mb-4">
                        <label class="form-label-dulce">Notas internas</label>
                        <textarea name="notas" rows="2" class="form-control form-control-dulce"
                                  placeholder="Observaciones sobre el pago...">{{ old('notas', $pago->notas) }}</textarea>
                    </div>
 
                    <div class="d-flex gap-3">
                        <button type="submit" class="btn-gold btn px-4">
                            <i class="bi bi-check-circle me-2"></i> Actualizar Pago
                        </button>
                        <a href="{{ route('pagos.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection