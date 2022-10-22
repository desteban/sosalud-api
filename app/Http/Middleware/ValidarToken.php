<?php

namespace App\Http\Middleware;

use App\Models\Respuestas;
use App\Util\Token;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ValidarToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $token = $request->header('token', '');
        $decode = Token::decodificar($token);

        if (!empty($decode))
        {
            $consulta = "SELECT usuario_id FROM personal_access_tokens WHERE token=? AND usuario_id=?";
            $usuarioId = DB::select(query: $consulta, bindings: [$token, $decode->data->id]);

            if (!empty($usuarioId) && $usuarioId[0]->usuario_id == $decode->data->id)
            {
                return $next($request);
            }
        }

        Token::eliminarToken($token);
        $respuesta = new Respuestas(
            codigoHttp: 401,
            titulo: 'Unauthorized',
            mensaje: 'token invalido'
        );
        return response()->json($respuesta, $respuesta->codigoHttp);
    }
}
