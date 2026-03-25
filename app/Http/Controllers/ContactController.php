<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    /**
     * Afficher la page de contact
     */
    public function index()
    {
        return view('contact.index');
    }

    /**
     * Envoyer le message de contact
     */
    public function send(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'sujet' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        Contact::create([
            'nom' => $request->nom,
            'email' => $request->email,
            'sujet' => $request->sujet,
            'message' => $request->message,
            'lu' => false
        ]);

        return redirect()->back()->with('success', 'Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.');
    }
}