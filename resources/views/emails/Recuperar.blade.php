<!DOCTYPE html>
<html lang="es-CO">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recuperacion</title>
</head>

<body>

    <h1>Recuperar cuenta</h1>

    <p>Has solicitado un cambio de contraseña, entra en el siguiente link, este link es valido por 15 minutos</p>

    <div
        style="background-color: #eff1fa; color:#3850b7; padding: 1.3rem .8rem; margin: 1.2rem 0px; font-size: 1.2rem;
    border-radius: 6px">
        <p><a href="{{ $data['token'] }}">{{ $data['token'] }}</a></p>
    </div>


</body>

</html>
