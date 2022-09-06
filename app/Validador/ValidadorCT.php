<?php

namespace App\Validador;

class ValidadorCT
{

    /**
     * @param nombreCarpeta nombre de la carpeta donde se encuentran los archivos
     * @param archivoCT nombre del archivo CT (.txt)
     * @param listaArchivos arreglo con el nombre de los RIPS de una carpeta
     * @param logErrores listado con los errores encontrados en esta validacion
     */
    public function __construct(
        protected string $nombreCarpeta,
        protected string $archivoCT,
        protected array $listaArchivos,
        protected $logErrores = []
    )
    {
    }

    /**
     * @return array log de errores encontrados en la validacion
     */
    public function validar(): array
    {

        $rutaApp = env('APP_DIR');
        $logErrores = array();

        if (is_file("$rutaApp/public/TMPs/$this->nombreCarpeta/$this->archivoCT"))
        {
            $archivoCT = file("$rutaApp/public/TMPs/$this->nombreCarpeta/$this->archivoCT");

            foreach ($archivoCT as $linea)
            {

                //eliminar saltos de linea y espacios
                $registro = str_replace(array("\r\n", "\n", "\r", ' '), '', $linea);

                //convertir un string a un array utilizando las comas (,)
                $registroArray = explode(',', $registro);

                if (preg_match('/([A-Z])+/', $registroArray[2]))
                {
                    $ripsArchivoCt = substr($registroArray[2], 0, 2);

                    if (!in_array($ripsArchivoCt, $this->listaArchivos))
                    {
                        array_push($logErrores, "Dentro del archivo CT se declara el archivo $ripsArchivoCt pero no se encuentra");
                    }
                }
            }
        }

        return $logErrores;
    }
}
