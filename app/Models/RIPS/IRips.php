<?php

namespace App\Models\RIPS;

interface IRips
{

    /**
     * @return string con las colomnas de la tabla del rips
     */
    public static function obtenerColumnasDB(bool $array = false): string | array;

    /**
     * @return string con las iniciales del tabla del rips (AC, AF, AM, ....)
     */
    public function tipoRIPS(): string;

    /**
     * esta funcion actualiza los atributos del objeto con los datos que se le pasan
     * @param datos arreglo con los datos del archivo rips
     */
    public function agregarDatos(array $datos);

    /**
     * @return string datos del objeto separados por comas
     */
    public function obtenerDatos(): array;

    /**
     * crea la tabla temporal para vlaidar el archivo rips
     * @param nombreTabla 
     */
    public function crearTablas(string $nombreTabla);

    /**
     * @param datos arreglo que contiene toda la indormacion del RIPS
     * @return bool el cual muestra el exito o fallo de guardar los datos en la base de datos
     */
    public function subirDB(array $datos = []);
}
