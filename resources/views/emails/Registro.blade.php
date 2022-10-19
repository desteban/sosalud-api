<!DOCTYPE html>
<html lang="es-CO">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registro</title>
</head>

<body>

    <h1>¡Hola {{ $data['nombre'] }}!</h1>

    <p>Tu nombre de usuario es <span style="font-weight: bold; font-weight: 1.1rem">{{ $data['nombreUsuario'] }}</span>
    </p>

    <p>Copia o da click en el enlace para activar tu cuenta</p>

    <div style="background-color: #eff1fa; color:#3850b7; padding: 1.3rem .8rem; margin: 1.2rem 0px; font-size: 1.2rem">
        <p><a href="{{ $data['token'] }}">{{ $data['token'] }}</a></p>
    </div>


</body>

</html>
