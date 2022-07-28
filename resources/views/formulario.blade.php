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

        .error {
            margin: 10px 5px;
            padding: 1rem;
            background-color: lightcoral
        }
    </style>

</head>

<body>
    {{-- enctype="multipart/form-data" para poder enviar archivos --}}
    <form action="{{ route('archivos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @if ($errors->any())
            <div class="error">
                <p><strong>Algo ha salido mal</strong></p>
                @foreach ($errors->all() as $error)
                    <p> -- {{ $error }} -- </p>
                @endforeach
            </div>
        @endif

        <input type="file" name="archivo" id="archivo" accept=".rar,.zip">

        <button>Enviar</button>
    </form>

</body>

</html>
