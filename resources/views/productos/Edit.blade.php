@extends('layouts.app')
@section('title', 'Editar Producto')
 
@section('content')
@if($errors->any())
    <div class="container mt-3">
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
<div class="page-header">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small">
                <li class="breadcrumb-item"><a href="{{ route('productos.index') }}" style="color:var(--dulce-rose)">Productos</a></li>
                <li class="breadcrumb-item active">Editar</li>
            </ol>
        </nav>
        <h1>Editar Producto ✏️</h1>
    </div>
</div>
 
<div class="container py-4">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card-dulce p-4">
                <form action="{{ route('productos.update', $producto) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    @include('productos.form')
                    <div class="d-flex gap-3 mt-4">
                        <button type="submit" class="btn-gold btn px-4">
                            <i class="bi bi-check-circle me-2"></i> Actualizar Producto
                        </button>
                        <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-lg-4">
            @if($producto->imagen)
            <div class="card-dulce p-4 text-center">
                <p class="small fw-bold mb-2">Imagen actual</p>
                <img src="{{ asset('storage/' . $producto->imagen) }}" class="img-upload-preview w-100">
            </div>
            @endif
        </div>
    </div>
</div>

@endsection
 