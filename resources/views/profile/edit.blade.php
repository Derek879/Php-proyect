<x-app-layout>

<style>
    body {
        background: linear-gradient(135deg, #FFF0F5 0%, #FFF8F0 50%, #F5F0FF 100%);
    }

    .profile-wrapper {
        padding: 3rem 1rem;
    }

    .profile-title {
        font-size: 2.5rem;
        font-weight: 900;
        color: #2d1b12;
        text-align: center;
    }

    .profile-subtitle {
        text-align: center;
        color: #6b3b2a;
        margin-bottom: 2rem;
    }

    .card-dulce {
        background: white;
        border-radius: 20px;
        padding: 1.8rem;
        box-shadow: 0 10px 20px rgba(0,0,0,.08);
        transition: .2s;
    }

    .card-dulce:hover {
        transform: translateY(-4px);
    }

    .section-title {
        font-size: 1.3rem;
        font-weight: 800;
        color: #2d1b12;
        margin-bottom: 1rem;
    }

    .danger-title {
        color: #ec4899;
        font-weight: 900;
    }

    /* BOTÓN ROSA */
    .btn-rose {
        background: #ec4899;
        color: white;
        border-radius: 9999px;
        border: none;
        padding: .9rem 2rem;
        font-weight: 800;
        text-decoration: none;
        display: inline-block;
        transition: .2s;
        min-width: 180px;
        text-align: center;
        cursor: pointer;
    }

    .btn-rose:hover {
        background: #db2777;
        transform: translateY(-2px);
    }

    /* INPUTS BONITOS */
    input, select, textarea {
        border-radius: 12px !important;
        border: 1px solid #f9a8d4 !important;
        padding: .6rem .8rem;
        outline: none;
    }

    input:focus, textarea:focus {
        border-color: #ec4899 !important;
        box-shadow: 0 0 0 2px #fbcfe8;
    }
</style>

    <x-slot name="header">
        <div class="profile-title">Perfil</div>
        <div class="profile-subtitle">
            Administra tu información, seguridad y cuenta
        </div>
    </x-slot>

    <div class="profile-wrapper">

        <div class="max-w-4xl mx-auto space-y-6">

            <!-- Información -->
            <div class="card-dulce">
                <div class="section-title">Información personal</div>
                @include('profile.partials.update-profile-information-form')
            </div>

            <!-- Contraseña -->
            <div class="card-dulce">
                <div class="section-title">Seguridad</div>
                @include('profile.partials.update-password-form')
            </div>

            <!-- Zona peligrosa -->
            <div class="card-dulce" style="border: 2px solid #f9a8d4;">
                <div class="danger-title section-title">
                    Zona peligrosa
                </div>
                @include('profile.partials.delete-user-form')
            </div>

        </div>
    </div>

</x-app-layout>