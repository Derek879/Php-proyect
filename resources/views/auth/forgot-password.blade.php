<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, Helvetica, sans-serif;
            background: #f3e4dc;
        }

        .container {
            width: 100%;
            max-width: 900px;
            display: flex;
            background: white;
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        }

        .left {
            width: 45%;
            background: linear-gradient(135deg, #b45309, #7c2d12);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 15px;
            padding: 50px;
            text-align: center;
        }

        .left .icon {
            font-size: 70px;
        }

        .left h2 {
            font-size: 34px;
            font-weight: 900;
        }

        .left p {
            font-size: 17px;
            line-height: 1.5;
            opacity: 0.9;
        }

        .right {
            width: 55%;
            padding: 60px;
        }

        .brand {
            font-size: 32px;
            font-weight: 900;
            color: #7c2d12;
            margin-bottom: 30px;
        }

        .title {
            font-size: 40px;
            font-weight: 900;
            color: #111827;
            margin-bottom: 12px;
            line-height: 1.1;
        }

        .text {
            color: #4b5563;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .input-group {
            margin-bottom: 25px;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #374151;
        }

        .input-group input {
            width: 100%;
            padding: 16px;
            border: 2px solid #ddd;
            border-radius: 12px;
            font-size: 16px;
            outline: none;
        }

        .input-group input:focus {
            border-color: #b45309;
        }

        .btn {
            width: 100%;
            padding: 17px;
            border: none;
            border-radius: 12px;
            background: #b45309;
            color: white;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn:hover {
            background: #92400e;
        }

        .back {
            display: block;
            margin-top: 22px;
            text-align: center;
            color: #b45309;
            font-weight: bold;
            text-decoration: none;
        }

        .back:hover {
            text-decoration: underline;
        }

        @media(max-width: 850px) {
            .container {
                flex-direction: column;
                max-width: 500px;
            }

            .left,
            .right {
                width: 100%;
            }

            .right {
                padding: 40px;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <div class="left">
        <div class="icon">🔐</div>
        <h2>Recupera tu cuenta</h2>
        <p>Te enviaremos un enlace para crear una nueva contraseña.</p>
    </div>

    <div class="right">

        <div class="brand">🍰 M&M </div>

        <h1 class="title">Olvidé mi contraseña</h1>

        <p class="text">
            Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.
        </p>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="input-group">
                <label for="email">Correo electrónico</label>

                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                >

                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <button type="submit" class="btn">
                Enviar enlace de recuperación
            </button>

            <a href="{{ route('login') }}" class="back">
                Volver al inicio de sesión
            </a>
        </form>

    </div>

</div>

</body>
</html>