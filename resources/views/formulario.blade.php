<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Formulario</title>

    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">

</head>

<body>
    <h1>Formulario</h1>
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

        <label for="archivo">el archivo a subir no debe pesar mas de 5 megabytes(MB) </label>
        <input type="file" name="archivo" id="archivo" accept=".rar,.zip">

        <button>Enviar</button>
    </form>

</body>

</html>
