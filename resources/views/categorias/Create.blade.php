@extends('layouts.app')

@section('title', 'Nueva Categoría')

@section('content')

<div class="page-header">
    <div class="container">
        <h1>Nueva Categoría 🍰</h1>
    </div>
</div>

<div class="container py-4">
    <div class="card-dulce p-4">
        <form action="{{ route('categorias.store') }}" method="POST">
            @csrf

            @include('categorias.form')

            <div class="d-flex gap-3 mt-4">
                <button type="submit" class="btn-rose btn px-4">
                    Guardar Categoría
                </button>

                <a href="{{ route('categorias.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

@endsection