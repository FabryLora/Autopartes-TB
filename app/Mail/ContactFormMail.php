<?php

// app/Mail/ContactFormMail.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
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
        return $this->subject('Nueva Consulta desde el Formulario de Contacto')
            ->view('emails.contact_form') // Vista del correo
            ->with('data', $this->data);
    }
}
