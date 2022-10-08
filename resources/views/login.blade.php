<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>

    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bulma.min.css') }}">

</head>

<body>
    <div class="container">
        <h1 class="title">Iniciar secion</h1>

        <form action="{{ route('usuario.login') }}" method="POST" class="form box">
            @csrf

            @if ($errors->any())
                <div class="error">
                    <p><strong>Algo ha salido mal</strong></p>
                    @foreach ($errors->all() as $error)
                        <p> -- {{ $error }} -- </p>
                    @endforeach
                </div>
            @endif

            <div class="field">
                <label class="label" for="nombreUsuario">Correo electronico o nombre de usuario</label>
                <input class="input is-medium" type="text" name="nombreUsuario" id="nombreUsuario"
                    placeholder="Correo electronico o nombre de usuario">
            </div>

            <div class="field">
                <label class="label" for="password">Contraseña</label>
                <input class="input is-medium" type="password" name="password" id="password" placeholder="Contraseña">
            </div>

            <input type="submit" value="Entrar" class="button is-medium is-fullwidth is-info">
        </form>

        <div>
            <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Est, quod voluptas itaque molestiae quisquam
                debitis! Voluptate autem placeat officia cumque praesentium. Unde dicta molestiae, quibusdam magni
                aperiam ipsam voluptatum facere?</p>
        </div>

    </div>
</body>

</html>
