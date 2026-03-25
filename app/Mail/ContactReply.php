<?php

namespace App\Mail;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactReply extends Mailable
{
    use Queueable, SerializesModels;

    public Contact $contact;
    public string $reponse;
    public string $sujet;

    /**
     * Create a new message instance.
     */
    public function __construct(Contact $contact, string $reponse, string $sujet)
    {
        $this->contact = $contact;
        $this->reponse = $reponse;
        $this->sujet = $sujet;
    }

    /**
     * Définition de l'enveloppe (objet + expéditeur si besoin)
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Réponse à votre message : ' . $this->sujet,
        );
    }

    /**
     * Définition du contenu de l'email
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-reply',
            with: [
                'contact' => $this->contact,
                'reponse' => $this->reponse,
                'sujet'   => $this->sujet,
            ],
        );
    }
}