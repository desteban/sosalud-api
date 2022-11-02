<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CambioPasswordMailable extends Mailable
{
    use Queueable, SerializesModels;

    public array $data = array();

    /**
     * Create a new message instance.
     * @param nombre nombre del usuario
     * @param password string generado para que sea su contraseña
     * @return void
     */
    public function __construct()
    {
        $this->subject('Cambio de contraseña');
        $this->data['ruta'] = env('APP_FRONT');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.CambioPassword');
    }
}
