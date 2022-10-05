<!DOCTYPE html>
<html lang="es-CO">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Welcome</title>

    <link rel="stylesheet" href="http://159.223.194.40:8081/css/bulma.min.css">
</head>

<body>

    <h1>¡Hola {{ $data['nombre'] }}!</h1>

    <p>Tu contraseña para ingresar al sistemas es: </p>

    <div class="notification is-link is-light">
        <p>{{ $data['password'] }}</p>
    </div>


</body>

</html>
