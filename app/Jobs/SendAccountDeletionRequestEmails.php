<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendAccountDeletionRequestEmails implements ShouldQueue
{
    use Queueable;

    protected string $email;
    protected string $userName;
    protected ?string $reason;
    protected string $requestDate;

    /**
     * Create a new job instance.
     */
    public function __construct(string $email, string $userName, ?string $reason)
    {
        $this->email = $email;
        $this->userName = $userName;
        $this->reason = $reason;
        $this->requestDate = now()->format('d/m/Y H:i');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Envoyer un email de confirmation a l'administrateur
        Mail::raw(
            "Demande de suppression de compte\n\n" .
            "Email: {$this->email}\n" .
            "Nom: {$this->userName}\n" .
            "Date: {$this->requestDate}\n" .
            "Raison: " . ($this->reason ?? 'Non specifiee') . "\n\n" .
            "Veuillez traiter cette demande dans un delai de 30 jours conformement au RGPD.",
            function ($message) {
                $message->to(config('mail.from.address'))
                        ->subject('Demande de suppression de compte - SIE');
            }
        );

        // Envoyer un email de confirmation a l'utilisateur
        Mail::raw(
            "Bonjour {$this->userName},\n\n" .
            "Nous avons bien recu votre demande de suppression de compte.\n\n" .
            "Votre demande sera traitee dans un delai maximum de 30 jours conformement au RGPD.\n" .
            "Vous recevrez une confirmation une fois la suppression effectuee.\n\n" .
            "Si vous n'etes pas a l'origine de cette demande, veuillez nous contacter immediatement.\n\n" .
            "Cordialement,\nL'equipe SIE",
            function ($message) {
                $message->to($this->email)
                        ->subject('Confirmation de demande de suppression - SIE');
            }
        );

        Log::info("Emails de demande de suppression envoyes pour: {$this->email}");
    }
}
