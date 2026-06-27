<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label-dulce">Nombre <span class="text-danger">*</span></label>
        <input type="text" name="nombre" class="form-control form-control-dulce @error('nombre') is-invalid @enderror"
               value="{{ old('nombre', $producto->nombre ?? '') }}" placeholder="Ej: Keke de vainilla con manjar...">
        @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label-dulce">Categoría <span class="text-danger">*</span></label>
        <select name="categoria_id" class="form-select form-control-dulce @error('categoria_id') is-invalid @enderror">
            <option value="">Seleccionar...</option>
            @foreach($categorias as $cat)
            <option value="{{ $cat->id }}" {{ old('categoria_id', $producto->categoria_id ?? '') == $cat->id ? 'selected' : '' }}>
                {{ $cat->icono }} {{ $cat->nombre }}
            </option>
            @endforeach
        </select>
        @error('categoria_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label class="form-label-dulce">Descripción</label>
        <textarea name="descripcion" rows="3" class="form-control form-control-dulce @error('descripcion') is-invalid @enderror"
                  placeholder="Describe los ingredientes, tamaño, sabor...">{{ old('descripcion', $producto->descripcion ?? '') }}</textarea>
        @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label-dulce">Precio (S/) <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text" style="background:var(--dulce-warm);border:2px solid var(--dulce-warm);border-radius:var(--radius) 0 0 var(--radius);font-weight:700;color:var(--dulce-brown)">S/</span>
            <input type="number" name="precio" step="0.50" min="0"
                   class="form-control form-control-dulce @error('precio') is-invalid @enderror"
                   style="border-radius:0 var(--radius) var(--radius) 0"
                   value="{{ old('precio', $producto->precio ?? '') }}" placeholder="0.00">
        </div>
        @error('precio')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label-dulce">Stock <span class="text-danger">*</span></label>
        <input type="number" name="stock" min="0"
               class="form-control form-control-dulce @error('stock') is-invalid @enderror"
               value="{{ old('stock', $producto->stock ?? 0) }}">
        @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
    <label class="form-label-dulce">Foto del producto</label>
        <input type="file" name="imagen" accept="image/*" class="form-control form-control-dulce"
               onchange="previewImg(this)">
        <div class="text-center mt-2">
            <img id="imgPreview" class="img-upload-preview d-none" alt="Vista previa">
        </div>
    </div>

    <div class="col-12">
        <div class="d-flex gap-4">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="disponible" id="disponible" value="1"
                       {{ old('disponible', $producto->disponible ?? true) ? 'checked' : '' }}>
                <label class="form-check-label fw-600" for="disponible">Disponible para venta</label>
            </div>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="destacado" id="destacado" value="1"
                       {{ old('destacado', $producto->destacado ?? false) ? 'checked' : '' }}>
                <label class="form-check-label fw-600" for="destacado">⭐ Producto destacado</label>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function previewImg(input) {
    const preview = document.getElementById('imgPreview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { preview.src = e.target.result; preview.classList.remove('d-none'); };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush

@push('styles')
<style>
.img-upload-preview {
    width: 280px;
    height: 280px;
    object-fit: cover;
    background: #fff0f5;
    border-radius: 16px;
    padding: 8px;
    display: block;
    margin: 0 auto;
}

</style>
@endpush