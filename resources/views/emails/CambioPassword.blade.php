<!DOCTYPE html>
<html lang="es-CO">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registro</title>
</head>

<body style="font-size: 20px">

    <h1>Se ha cambiado la contraseña</h1>
    <p>
        Tu cuenta fue actualizada satisfactoriamente, puedes acceder a
        nuestros servicios a través del siguiente enlace.
    </p>

    <div style="background-color: #eff1fa; color:#3850b7; padding: 1.3rem .8rem; margin: 1.2rem 0px; font-size: 1.2rem">
        <p><a href="{{ $data['ruta'] }}">{{ $data['ruta'] }}</a></p>
    </div>


</body>

</html>
