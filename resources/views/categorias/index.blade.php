@extends('layouts.app')
@section('title', 'Categorías')
 
@section('content')
<div class="page-header">
    <div class="container d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="bi bi-tags me-2" style="color:var(--dulce-rose)"></i>Categorías</h1>
            <p class="text-muted mb-0">Organiza tus productos por tipo</p>
        </div>
        <a href="{{ route('categorias.create') }}" class="btn-rose btn">
            <i class="bi bi-plus-circle me-2"></i> Nueva Categoría
        </a>
    </div>
</div>
 
<div class="container py-4">
    <div class="table-dulce">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Ícono</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Productos</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categorias as $cat)
                <tr>
                    <td><span style="font-size:1.8rem">{{ $cat->icono }}</span></td>
                    <td><strong>{{ $cat->nombre }}</strong></td>
                    <td class="text-muted small">{{ Str::limit($cat->descripcion, 60) ?? '—' }}</td>
                    <td>
                        <span class="badge-categoria">{{ $cat->productos_count }} productos</span>
                    </td>
                    <td>
                        @if($cat->activo)
                            <span class="badge bg-success status-pill">Activo</span>
                        @else
                            <span class="badge bg-secondary status-pill">Inactivo</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('categorias.edit', $cat) }}" class="btn btn-sm btn-outline-dulce">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('categorias.destroy', $cat) }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar esta categoría?')">
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
                    <td colspan="6" class="text-center py-5 text-muted">
                        <div style="font-size:3rem">🏷️</div>
                        <p class="mt-2">No hay categorías aún. <a href="{{ route('categorias.create') }}">Crea la primera</a></p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
 
    <div class="mt-3">{{ $categorias->links() }}</div>
</div>
@endsection
 