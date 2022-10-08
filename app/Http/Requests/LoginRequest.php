<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'nombreUsuario' => 'required',
            'password' => 'required'
        ];
    }

    public function credenciales()
    {
        $nombreUsuario = $this->get('nombreUsuario');

        if ($this->esEmail($nombreUsuario))
        {
            return [
                'email' => $nombreUsuario,
                'password' => $this->get('password')
            ];
        }

        return $this->only('nombreUsuario', 'password');
    }

    public function esEmail($valorValidar)
    {
        $validacion = Validator::make(['email' =>  $valorValidar], ['email' => 'email']);

        return !$validacion->failed();
    }
}
