<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;


class InformacionDePagoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $archivo;

    /**
     * Get the message envelope.
     */
    public function __construct($data, $archivo = null)
    {
        $this->data = $data;
        $this->archivo = $archivo; // Archivo adjunto opcional
    }

    public function build()
    {
        return $this->subject('Nueva Consulta desde el Formulario de Informacion de Pago')
            ->view('emails.informacion_form') // Vista del correo
            ->with('data', $this->data);
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];

        if ($this->archivo) {
            $attachments[] = Attachment::fromPath($this->archivo->getRealPath())
                ->as($this->archivo->getClientOriginalName())
                ->withMime($this->archivo->getMimeType());
        }

        return $attachments;
    }
}
