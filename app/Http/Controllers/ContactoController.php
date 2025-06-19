<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use App\Models\Contacto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;


class ContactoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contacto = Contacto::first();

        return inertia('admin/contactoAdmin', [
            'contacto' => $contacto,

        ]);
    }

    public function sendContact(Request $request)
    {
        // Validar los campos del formulario
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'celular' => 'required|string',
            'empresa' => 'required|string',
            'mensaje' => 'required|string',
            'recaptcha_token' => 'required',  // El token generado por reCAPTCHA v3
        ]);

        // Verificar el token de reCAPTCHA con la API de Google
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA_SECRET_KEY'),
            'response' => $request->input('recaptcha_token'),
            'remoteip' => $request->ip(),
        ]);

        // Si la validación del reCAPTCHA falla
        if (!$response->json()['success']) {
            return redirect()->back()->withErrors(['recaptcha_token' => 'Verificación de reCAPTCHA fallida.']);
        }
        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'celular' => $request->input('celular'),
            'empresa' => $request->input('empresa'),
            'mensaje' => $request->input('mensaje'),
        ];

        // Enviar correo al administrador (o a la dirección que desees)
        Mail::to(Contacto::first()->mail)->send(new ContactFormMail($data));

        // Devolver mensaje de éxito al usuario
        return redirect()->back()->with('success', 'Tu mensaje ha sido enviado con éxito.');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $contacto = Contacto::first();


        $data = $request->validate([

            'phone' => 'sometimes|nullable|string|max:255',
            'mail' => 'sometimes|nullable|email|max:255',
            'location' => 'sometimes|nullable|string|max:255',
            'fb' => 'sometimes|nullable|string|max:255',
            'ig' => 'sometimes|nullable|string|max:255',
            'wp' => 'sometimes|nullable|string|max:255',
        ]);

        if (!$contacto) {
            $contacto = Contacto::create($data);
            return redirect()->back()->with('success', 'Contacto created successfully.');
        } else {
            $contacto->update($data);
        }



        return redirect()->back()->with('success', 'Contacto updated successfully.');
    }
}
