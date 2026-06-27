<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>M&M Repostería</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>

<div class="container">

    <a class="navbar-brand" href="/">
        M&M Repostería
    </a>

    <div>

        <a href="/" class="text-white me-3 text-decoration-none">
            Inicio
        </a>

        <a href="/productos" class="text-white me-3 text-decoration-none">
            Productos
        </a>

        <a href="/pedidos" class="text-white me-3 text-decoration-none">
            Pedidos
        </a>

        <a href="/clientes" class="text-white me-3 text-decoration-none">
            Clientes
        </a>

        <a href="/categorias" class="text-white text-decoration-none">
            Categorías
        </a>

    </div>

</div>

    <section class="hero">
        <div class="container">

            <h1>Dulces que alegran tu día</h1>

            <p>
                Kekes, alfajores, cupcakes y postres personalizados.
            </p>

            <br>

            <a href="/productos" class="btn btn-custom">
                Ver Productos
            </a>

        </div>
    </section>

    <div class="container mb-5">

        <div class="row g-4">

            <div class="col-md-4">
                <div class="card p-4 text-center">
                    <h3>Kekes</h3>
                    <p>Variedad de kekes artesanales y personalizados.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card p-4 text-center">
                    <h3>Alfajores</h3>
                    <p>Alfajores rellenos y decorados para toda ocasión.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card p-4 text-center">
                    <h3>Cupcakes</h3>
                    <p>Cupcakes temáticos y personalizados.</p>
                </div>
            </div>

        </div>

    </div>

</body>
</html>