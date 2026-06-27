<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            font-family:Arial, Helvetica, sans-serif;
            background:#f3e4dc;
        }

        .container{
            width:100%;
            max-width:900px;
            display:flex;
            background:white;
            border-radius:25px;
            overflow:hidden;
            box-shadow:0 10px 40px rgba(0,0,0,0.15);
        }

        .left{
            width:50%;
            background:linear-gradient(135deg,#b45309,#7c2d12);
            color:white;
            display:flex;
            flex-direction:column;
            justify-content:center;
            align-items:center;
            padding:50px;
        }

        .left h1{
            font-size:55px;
            margin-bottom:15px;
        }

        .left h2{
            margin-bottom:10px;
        }

        .left p{
            font-size:18px;
            text-align:center;
            opacity:.9;
        }

        .right{
            width:50%;
            padding:60px;
        }

        .title{
            font-size:45px;
            font-weight:900;
            margin-bottom:10px;
            color:#111827;
        }

        .subtitle{
            color:#6b7280;
            margin-bottom:35px;
        }

        .input-group{
            margin-bottom:20px;
        }

        .input-group label{
            display:block;
            margin-bottom:8px;
            font-weight:bold;
            color:#374151;
        }

        .input-group input{
            width:100%;
            padding:16px;
            border:2px solid #ddd;
            border-radius:12px;
            font-size:16px;
            outline:none;
        }

        .input-group input:focus{
            border-color:#b45309;
        }

        .options{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:25px;
            font-size:14px;
        }

        .options a{
            text-decoration:none;
            color:#b45309;
            font-weight:bold;
        }

        .btn{
            width:100%;
            padding:18px;
            border:none;
            border-radius:12px;
            background:#b45309;
            color:white;
            font-size:20px;
            font-weight:bold;
            cursor:pointer;
            transition:.3s;
        }

        .btn:hover{
            background:#92400e;
        }

        .register{
            margin-top:25px;
            text-align:center;
        }

        .register p{
            color:#6b7280;
            margin-bottom:12px;
        }

        .btn-register{
            display:block;
            width:100%;
            padding:16px;
            border-radius:12px;
            background:#16a34a;
            color:white;
            text-decoration:none;
            font-size:18px;
            font-weight:bold;
            transition:.3s;
        }

        .btn-register:hover{
            background:#15803d;
        }

        @media(max-width:800px){

            .container{
                flex-direction:column;
                max-width:500px;
            }

            .left,
            .right{
                width:100%;
            }

            .left{
                padding:35px;
            }

            .left h1{
                font-size:40px;
            }

            .right{
                padding:35px;
            }

            .title{
                font-size:35px;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <div class="left">
        <h1>🍰</h1>
        <h2>Dulces Delicias</h2>
        <p>Bienvenido nuevamente</p>
    </div>

    <div class="right">

        <h1 class="title">Iniciar Sesión</h1>

        <p class="subtitle">
            Accede a tu cuenta
        </p>

        @if ($errors->any())
            <div style="background:#fee2e2;padding:10px;margin-bottom:15px;border-radius:8px;">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="input-group">
                <label>Correo electrónico</label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                >
            </div>

            <div class="input-group">
                <label>Contraseña</label>
                <input
                    type="password"
                    name="password"
                    required
                >
            </div>

            <div class="options">

                <label>
                    <input type="checkbox" name="remember">
                    Recordarme
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">
                        ¿Olvidaste tu contraseña?
                    </a>
                @endif

            </div>

            <button type="submit" class="btn">
                Iniciar sesión
            </button>

            <div class="register">
                <p>¿No tienes una cuenta?</p>

                <a href="{{ route('register') }}" class="btn-register">
                    Registrarse
                </a>
            </div>

        </form>

    </div>

</div>

</body>
</html>