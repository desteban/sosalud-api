<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RecuperacionMailable extends Mailable
{
    use Queueable, SerializesModels;

    public array $data = array();

    /**
     * Create a new message instance.
     * @param nombre nombre del usuario
     * @param password string generado para que sea su contraseña
     * @return void
     */
    public function __construct(string $token)
    {
        $this->subject('Recuperación de contraseña ');
        $this->data['token'] = env('APP_FRONT') . $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.Recuperar');
    }
}
