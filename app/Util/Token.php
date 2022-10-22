<?php

namespace App\Util;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\DB;

class Token
{

    /**
     * @param data arreglo con la informacion del token
     * @return string JWT
     */
    public static function crear(array $data = ['sub' => 0], $creacion = 0, $duracion = 0): string
    {
        $key = env('JWT_KEY');
        $token = array(
            'iat' => $creacion,
            'exp' => $duracion,
            'data' => $data
        );

        return JWT::encode($token, $key, 'HS256');
    }

    /**
     * @param token JWT
     * @return DecodificarToken regresa un arreglo vacio al no poder decodificar el token
     */
    public static function decodificar(string $token)
    {
        $key = env('JWT_KEY');

        try
        {

            return JWT::decode($token, new Key($key, 'HS256'));
        }
        catch (\Throwable $th)
        {
            return [];
        }
    }

    /**
     * @param data debe cumplir con la estrucura de la tabla personal_access_tokens
     * @return bool que define el exito de la operacion
     */
    public static function guardarToken(array $data = [])
    {
        if (empty($token))
        {
            return DB::table('personal_access_tokens')->insert($data);
        }

        return false;
    }

    public static function eliminarToken(string $token = '')
    {
        if (!empty($token))
        {

            try
            {
                return DB::statement('DELETE FROM personal_access_tokens WHERE token=?', bindings: [$token]);
            }
            catch (\Throwable $th)
            {
                return false;
            }
        }

        return false;
    }
}
