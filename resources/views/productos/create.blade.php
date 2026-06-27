@extends('layouts.app')
@section('title', 'Nuevo Producto')
 
@section('content')
<div class="page-header">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small">
                <li class="breadcrumb-item"><a href="{{ route('productos.index') }}" style="color:var(--dulce-rose)">Productos</a></li>
                <li class="breadcrumb-item active">Nuevo</li>
            </ol>
        </nav>
        <h1>Nuevo Producto 🍰</h1>
    </div>
</div>
 
<div class="container py-4">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card-dulce p-4">
                <form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @include('productos.form')
                    <div class="d-flex gap-3 mt-4">
                        <button type="submit" class="btn-rose btn px-4">
                            <i class="bi bi-check-circle me-2"></i> Guardar Producto
                        </button>
                        <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card-dulce p-4" style="background:var(--dulce-warm)">
                <h6 class="fw-bold mb-3">💡 Consejos</h6>
                <ul class="small text-muted ps-3 mb-0">
                    <li class="mb-2">Usa fotos de buena calidad (mínimo 400×400 px)</li>
                    <li class="mb-2">Describe bien los ingredientes y el tamaño</li>
                    <li class="mb-2">Activa "Destacado" para mostrarlo en el inicio</li>
                    <li>Mantén el stock actualizado</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection