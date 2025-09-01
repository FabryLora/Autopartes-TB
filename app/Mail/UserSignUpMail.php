<?php

// app/Mail/ContactFormMail.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserSignUpMail extends Mailable
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
        return $this->subject('Nueva solicitud de registro')
            ->view('emails.user_sign_up') // Vista del correo
            ->with('data', $this->data);
    }
}
