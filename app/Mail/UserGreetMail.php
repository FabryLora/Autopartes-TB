<?php

// app/Mail/ContactFormMail.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserGreetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    // Se recibe los datos del formulario para enviarlos en el correo
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        return $this->subject('Registro en Autopartes TB')
            ->view('emails.user_greet') // Vista del correo
            ->with('data', $this->data);
    }
}
