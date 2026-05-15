<?php
// app/Traits/NewsletterTrait.php

namespace App\Traits;

use App\Models\Newsletter;
use App\Models\Actualite;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

trait NewsletterTrait
{
    /**
     * Envoyer une actualité à tous les abonnés actifs
     */
    public function sendActualiteToSubscribers(Actualite $actualite)
    {
        try {
            // Récupérer tous les abonnés actifs
            $subscribers = Newsletter::actifs()->get();
            
            if ($subscribers->isEmpty()) {
                Log::info('Aucun abonné actif pour envoyer l\'actualité', [
                    'actualite_id' => $actualite->id,
                    'actualite_titre' => $actualite->titre
                ]);
                return [
                    'success' => false,
                    'message' => 'Aucun abonné actif trouvé',
                    'count' => 0
                ];
            }
            
            $successCount = 0;
            $failCount = 0;
            $errors = [];
            
            foreach ($subscribers as $subscriber) {
                try {
                    Mail::send('emails.actualite-notification', [
                        'subscriber' => $subscriber,
                        'actualite' => $actualite
                    ], function ($message) use ($subscriber, $actualite) {
                        $message->to($subscriber->email, $subscriber->nom)
                                ->subject('Nouvelle actualité : ' . $actualite->titre)
                                ->from(config('mail.from.address'), config('mail.from.name'));
                    });
                    
                    $successCount++;
                    
                } catch (\Exception $e) {
                    $failCount++;
                    $errors[] = $subscriber->email . ': ' . $e->getMessage();
                    Log::error('Erreur lors de l\'envoi de l\'actualité à ' . $subscriber->email, [
                        'error' => $e->getMessage(),
                        'actualite_id' => $actualite->id
                    ]);
                }
            }
            
            Log::info('Actualité envoyée par email', [
                'actualite_id' => $actualite->id,
                'actualite_titre' => $actualite->titre,
                'total_abonnes' => $subscribers->count(),
                'success' => $successCount,
                'fail' => $failCount
            ]);
            
            return [
                'success' => true,
                'total' => $subscribers->count(),
                'success_count' => $successCount,
                'fail_count' => $failCount,
                'errors' => $errors,
                'message' => "Actualité envoyée à {$successCount} abonnés sur {$subscribers->count()}"
            ];
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de l\'actualité', [
                'error' => $e->getMessage(),
                'actualite_id' => $actualite->id
            ]);
            return [
                'success' => false,
                'message' => 'Erreur lors de l\'envoi: ' . $e->getMessage(),
                'count' => 0
            ];
        }
    }
    
    /**
     * Envoyer une newsletter personnalisée
     */
    public function sendCustomNewsletter($subject, $content, $actualiteId = null)
    {
        try {
            $subscribers = Newsletter::actifs()->get();
            
            if ($subscribers->isEmpty()) {
                return [
                    'success' => false,
                    'message' => 'Aucun abonné actif trouvé',
                    'count' => 0
                ];
            }
            
            $successCount = 0;
            $failCount = 0;
            
            foreach ($subscribers as $subscriber) {
                try {
                    Mail::send('emails.custom-newsletter', [
                        'subscriber' => $subscriber,
                        'subject' => $subject,
                        'content' => $content,
                        'actualite_id' => $actualiteId
                    ], function ($message) use ($subscriber, $subject) {
                        $message->to($subscriber->email, $subscriber->nom)
                                ->subject($subject)
                                ->from(config('mail.from.address'), config('mail.from.name'));
                    });
                    
                    $successCount++;
                } catch (\Exception $e) {
                    $failCount++;
                    Log::error('Erreur envoi newsletter: ' . $e->getMessage());
                }
            }
            
            return [
                'success' => true,
                'total' => $subscribers->count(),
                'success_count' => $successCount,
                'fail_count' => $failCount,
                'message' => "Newsletter envoyée à {$successCount} abonnés"
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }
}