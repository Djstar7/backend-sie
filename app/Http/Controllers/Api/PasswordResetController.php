<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetOtp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    /**
     * Envoyer le lien de reinitialisation par email
     */
    public function sendResetLink(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users,email'
            ]);

            // Generer un token unique
            $token = Str::random(64);

            // Creer ou mettre a jour le reset token
            PasswordResetOtp::updateOrCreate(
                ['email' => $request->email],
                [
                    'token' => Hash::make($token),
                    'expires_at' => now()->addMinutes(60),
                ]
            );

            // Construire le lien de reinitialisation
            $frontendUrl = config('app.frontend_url', 'http://localhost:5173');
            $resetLink = "{$frontendUrl}/auth/reset-password?token={$token}&email=" . urlencode($request->email);

            // Envoyer l'email avec le lien
            Mail::raw(
                "Bonjour,\n\nVous avez demande la reinitialisation de votre mot de passe.\n\nCliquez sur le lien ci-dessous pour creer un nouveau mot de passe :\n\n{$resetLink}\n\nCe lien expire dans 60 minutes.\n\nSi vous n'avez pas demande cette reinitialisation, ignorez cet email.\n\nCordialement,\nL'equipe SIE",
                function ($message) use ($request) {
                    $message->to($request->email)
                            ->subject('Reinitialisation de votre mot de passe - SIE');
                }
            );

            return response()->json([
                'message' => 'Lien de reinitialisation envoye avec succes'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Aucun compte trouve avec cet email',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Erreur sendResetLink PasswordResetController: ' . $e->getMessage());
            return response()->json([
                'message' => 'Erreur lors de l\'envoi du lien'
            ], 500);
        }
    }

    /**
     * Verifier le token de reinitialisation
     */
    public function verifyToken(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'token' => 'required|string'
            ]);

            $passwordReset = PasswordResetOtp::where('email', $request->email)
                ->where('expires_at', '>', now())
                ->first();

            if (!$passwordReset) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Lien expire ou invalide'
                ], 422);
            }

            // Verifier le token hashe
            if (!Hash::check($request->token, $passwordReset->token)) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Lien invalide'
                ], 422);
            }

            return response()->json([
                'valid' => true,
                'email' => $request->email,
                'message' => 'Token valide'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur verifyToken PasswordResetController: ' . $e->getMessage());
            return response()->json([
                'valid' => false,
                'message' => 'Erreur lors de la verification'
            ], 500);
        }
    }

    /**
     * Reinitialiser le mot de passe
     */
    public function resetPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'token' => 'required|string',
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    function ($attribute, $value, $fail) {
                        $conditionsMet = 0;
                        if (preg_match('/[a-z]/', $value)) $conditionsMet++;
                        if (preg_match('/[A-Z]/', $value)) $conditionsMet++;
                        if (preg_match('/\d/', $value)) $conditionsMet++;
                        if (preg_match('/[^A-Za-z0-9]/', $value)) $conditionsMet++;

                        if ($conditionsMet < 3) {
                            $fail('Le mot de passe doit contenir au moins 3 des 4 criteres : minuscule, majuscule, chiffre, caractere special.');
                        }
                    },
                ],
            ]);

            $passwordReset = PasswordResetOtp::where('email', $request->email)
                ->where('expires_at', '>', now())
                ->first();

            if (!$passwordReset) {
                return response()->json([
                    'message' => 'Lien expire ou invalide'
                ], 422);
            }

            // Verifier le token hashe
            if (!Hash::check($request->token, $passwordReset->token)) {
                return response()->json([
                    'message' => 'Lien invalide'
                ], 422);
            }

            // Mettre a jour le mot de passe de l'utilisateur
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json([
                    'message' => 'Utilisateur non trouve'
                ], 404);
            }

            $user->update([
                'password' => Hash::make($request->password)
            ]);

            // Supprimer le token utilise
            $passwordReset->delete();

            return response()->json([
                'message' => 'Mot de passe reinitialise avec succes'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Donnees invalides',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Erreur resetPassword PasswordResetController: ' . $e->getMessage());
            return response()->json([
                'message' => 'Erreur lors de la reinitialisation'
            ], 500);
        }
    }
}
