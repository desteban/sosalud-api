<!DOCTYPE html>
<html lang="es-CO">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registro</title>

    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bulma.min.css') }}">
</head>

<body>

    <section class="container">

        <h1 class="title">Registro</h1>
        <form action="{{ route('usuario.crear') }}" method="POST" class="form">
            @csrf

            <div>
                <div class="field">
                    <label for="name" class="label">Nombre</label>
                    <input class="input is-medium" type="text" name="name" id="name" autocomplete="OFF"
                        placeholder="Nombre">
                </div>

                <div class="field">
                    <label class="label" for="email">Correo</label>
                    <input class="input is-medium" type="text" name="email" id="email" autocomplete="OFF"
                        placeholder="Correo">
                </div>

                <div class="field">
                    <label class="label" for="password">Contraseña</label>
                    <input class="input is-medium" type="password" name="password" id="password"
                        placeholder="Contraseña">
                </div>

                <input type="submit" value="Registrar" class="button is-medium is-fullwidth is-info">
            </div>

        </form>
        <div>
            <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Labore dolore magnam, voluptatibus temporibus
                esse voluptatum rem ea expedita, deserunt earum ab minus repudiandae ullam obcaecati beatae explicabo
                provident. Dignissimos, illum.</p>
        </div>
    </section>


</body>

</html>
