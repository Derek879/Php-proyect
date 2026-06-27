@php
$iconos = ['🍰','🧁','🍫','🥐','🎂','🍩','🍪','🫓'];
@endphp

<div class="row g-3">

    <div class="col-12">

        <label class="form-label">Ícono *</label>

        <div class="d-flex flex-wrap gap-2 mb-3">

            @foreach($iconos as $ico)

                <button
                    type="button"
                    onclick="
                        document.getElementById('icono').value='{{ $ico }}';
                        document.querySelectorAll('.icon-btn').forEach(b => b.classList.remove('selected'));
                        this.classList.add('selected');
                    "
                    class="icon-btn btn {{ old('icono', $categoria->icono ?? '🍰') == $ico ? 'selected' : '' }}"
                    style="
                        font-size:1.5rem;
                        width:50px;
                        height:50px;
                        border:2px solid #f9a8d4;
                        border-radius:12px;
                        background:white;
                    "
                >
                    {{ $ico }}
                </button>

            @endforeach

        </div>

        <input
            type="hidden"
            name="icono"
            id="icono"
            value="{{ old('icono', $categoria->icono ?? '🍰') }}"
        >

    </div>


    <div class="col-12">

        <label class="form-label">
            Nombre *
        </label>

        <input
            type="text"
            name="nombre"
            class="form-control"
            value="{{ old('nombre', $categoria->nombre ?? '') }}"
            placeholder="Ej: Tortas, Cupcakes..."
            required
        >

    </div>


    <div class="col-12">

        <label class="form-label">
            Descripción
        </label>

        <textarea
            name="descripcion"
            rows="3"
            class="form-control"
            placeholder="Describe la categoría..."
        >{{ old('descripcion', $categoria->descripcion ?? '') }}</textarea>

    </div>


    <div class="col-12">

        <div class="form-check form-switch">

            <input
                class="form-check-input"
                type="checkbox"
                name="activo"
                id="activo"
                value="1"
                {{ old('activo', $categoria->activo ?? true) ? 'checked' : '' }}
            >

            <label class="form-check-label" for="activo">
                Categoría activa
            </label>

        </div>

    </div>

</div>

<style>

.icon-btn.selected {
    border-color: #ec4899 !important;
    background: #fce7f3 !important;
    transform: scale(1.1);
}

</style>