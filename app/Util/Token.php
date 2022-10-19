<?php

namespace App\Util;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Token
{

    /**
     * @param data arreglo con la informacion del token
     * @return string JWT
     */
    public static function crear(array $data = ['sub' => 0], int $diasDuracion = 4): string
    {
        $time = time();
        $key = env('JWT_KEY');
        $token = array(
            'iat' => $time,
            'exp' => $time + (60 * 60 * (24 * $diasDuracion)),
            'data' => $data
        );

        return JWT::encode($token, $key, 'HS256');
    }

    /**
     * @param token JWT
     * @return ContenidoToken
     */
    public static function decodificar(string $token)
    {
        $key = env('JWT_KEY');
        return JWT::decode($token, new Key($key, 'HS256'));
    }
}
