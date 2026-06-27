<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>

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
            max-width: 950px;
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
            opacity: 0.9;
            line-height: 1.5;
        }

        .right {
            width: 55%;
            padding: 55px 60px;
        }

        .brand {
            font-size: 32px;
            font-weight: 900;
            color: #7c2d12;
            margin-bottom: 25px;
        }

        .title {
            font-size: 42px;
            font-weight: 900;
            color: #111827;
            margin-bottom: 8px;
        }

        .subtitle {
            color: #6b7280;
            margin-bottom: 30px;
        }

        .input-group {
            margin-bottom: 18px;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #374151;
        }

        .input-group input {
            width: 100%;
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 12px;
            font-size: 16px;
            outline: none;
        }

        .input-group input:focus {
            border-color: #b45309;
        }

        .bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 25px;
            gap: 15px;
        }

        .bottom a {
            color: #b45309;
            font-weight: bold;
            text-decoration: none;
        }

        .bottom a:hover {
            text-decoration: underline;
        }

        .btn {
            padding: 15px 35px;
            border: none;
            border-radius: 12px;
            background: #b45309;
            color: white;
            font-size: 17px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn:hover {
            background: #92400e;
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
        <div class="icon">🍰</div>
        <h2>Dulces Delicias</h2>
        <p>Crea tu cuenta y disfruta nuestros productos.</p>
    </div>

    <div class="right">

        <div class="brand">🍰 Dulces Delicias</div>

        <h1 class="title">Crear cuenta</h1>
        <p class="subtitle">Regístrate para continuar</p>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="input-group">
                <label for="name">Nombre</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
            </div>

            <div class="input-group">
                <label for="email">Correo electrónico</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
            </div>

            <div class="input-group">
                <label for="password">Contraseña</label>
                <input id="password" type="password" name="password" required autocomplete="new-password">
            </div>

            <div class="input-group">
                <label for="password_confirmation">Confirmar contraseña</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
            </div>

            <div class="bottom">
                <a href="{{ route('login') }}">
                    ¿Ya tienes cuenta?
                </a>

                <button type="submit" class="btn">
                    Registrarse
                </button>
            </div>
        </form>

    </div>

</div>

</body>
</html>