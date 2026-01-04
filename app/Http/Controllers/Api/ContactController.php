<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Envoyer un message de contact
     */
    public function send(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'subject' => 'required|string|max:255',
                'message' => 'required|string|max:5000',
            ]);

            // Email de l'administrateur/support
            $adminEmail = config('mail.admin_email', 'infodjstar7@gmail.com');

            // Envoyer l'email a l'admin
            Mail::raw(
                $this->formatAdminEmail($validated),
                function ($mail) use ($validated, $adminEmail) {
                    $mail->to($adminEmail)
                         ->replyTo($validated['email'], $validated['name'])
                         ->subject('[Contact SIE] ' . $validated['subject']);
                }
            );

            // Envoyer un email de confirmation a l'expediteur
            Mail::raw(
                $this->formatConfirmationEmail($validated),
                function ($mail) use ($validated) {
                    $mail->to($validated['email'])
                         ->subject('Confirmation de votre message - SIE');
                }
            );

            Log::info('Message de contact envoye', [
                'from' => $validated['email'],
                'subject' => $validated['subject']
            ]);

            return response()->json([
                'message' => 'Votre message a ete envoye avec succes. Nous vous repondrons dans les plus brefs delais.'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Donnees invalides',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi du message de contact: ' . $e->getMessage());
            return response()->json([
                'message' => 'Une erreur est survenue lors de l\'envoi de votre message. Veuillez reessayer.'
            ], 500);
        }
    }

    /**
     * Formater l'email pour l'admin
     */
    private function formatAdminEmail(array $data): string
    {
        return <<<EMAIL
Nouveau message de contact recu sur SIE

----------------------------------------
De: {$data['name']}
Email: {$data['email']}
Objet: {$data['subject']}
----------------------------------------

Message:

{$data['message']}

----------------------------------------
Ce message a ete envoye depuis le formulaire de contact du site SIE.
EMAIL;
    }

    /**
     * Formater l'email de confirmation pour l'expediteur
     */
    private function formatConfirmationEmail(array $data): string
    {
        return <<<EMAIL
Bonjour {$data['name']},

Nous avons bien recu votre message et nous vous en remercions.

Voici un recapitulatif de votre demande:
----------------------------------------
Objet: {$data['subject']}

Message:
{$data['message']}
----------------------------------------

Notre equipe traitera votre demande dans les plus brefs delais et vous repondra a l'adresse {$data['email']}.

Cordialement,
L'equipe SIE

---
Ceci est un email automatique, merci de ne pas y repondre directement.
Pour nous contacter: support@sie.com | +237 674 69 36 25
EMAIL;
    }
}
