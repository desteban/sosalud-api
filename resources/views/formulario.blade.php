<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Formulario</title>

    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bulma.min.css') }}">

</head>

<body>
    <section class="container">

        <h1 class="title">Formulario</h1>
        {{-- enctype="multipart/form-data" para poder enviar archivos --}}
        <form action="{{ route('comprimidos.guardar') }}" method="POST" enctype="multipart/form-data" class="form box">
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

                <label class="file-label" for="archivo">el archivo a subir no debe pesar mas de 5 megabytes(MB)
                </label>
                <input class="input" type="file" name="archivo" id="archivo" accept=".rar,.zip,.7z">
            </div>

            <button class="button is-medium is-fullwidth is-info">Enviar</button>
        </form>
    </section>

</body>

</html>
