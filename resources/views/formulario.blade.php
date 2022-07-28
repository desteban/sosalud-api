<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Formulario</title>

    <style>
        form {
            margin: 5rem 10rem
        }

        button {
            display: block;
            margin: 10px 2rem
        }
    </style>

</head>

<body>

    <form action="{{ route('archivos.store') }}" method="POST">

        @if ($errors->any())
            <div>
                Algo ha salido mal
                @foreach ($errors->all() as $error)
                    -- { $error } -- <br>
                @endforeach
            </div>
        @endif

        <input type="file" name="archivo" id="archivo">

        <button>Enviar</button>
    </form>

</body>

</html>
