<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistroMailable extends Mailable
{
    use Queueable, SerializesModels;

    public array $data = array();

    /**
     * Create a new message instance.
     * @param nombre nombre del usuario
     * @param password string generado para que sea su contraseña
     * @return void
     */
    public function __construct(string $nombre, string $nombreUsuario, string $token)
    {
        $this->subject('Te damos la bienvenida ' . $nombre);
        $this->data['nombre'] = $nombre;
        $this->data['nombreUsuario'] = $nombreUsuario;
        $this->data['token'] = env('APP_URL') . $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.Registro');
    }
}
