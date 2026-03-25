<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Newsletter;

class NewsletterController extends Controller
{
    /**
     * S'abonner à la newsletter
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:newsletters,email',
        ]);

        Newsletter::create([
            'nom' => $request->nom,
            'email' => $request->email,
            'est_actif' => true,
            'date_inscription' => now()
        ]);

        return redirect()->back()->with('success', 'Merci pour votre inscription à notre newsletter !');
    }
}