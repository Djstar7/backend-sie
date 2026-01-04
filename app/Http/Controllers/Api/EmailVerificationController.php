<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EmailVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailVerificationController extends Controller
{
    /**
     * Envoyer le code OTP par email
     */
    public function sendCode(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|unique:users,email'
            ]);

            // Generer un code OTP a 6 chiffres
            $code = random_int(100000, 999999);

            // Creer ou mettre a jour la verification
            EmailVerification::updateOrCreate(
                ['email' => $request->email],
                [
                    'code' => Hash::make($code), // Hasher le code pour la securite
                    'expires_at' => now()->addMinutes(10),
                    'verified' => false
                ]
            );

            // Envoyer l'email avec le code
            Mail::raw(
                "Votre code de verification est : $code\n\nCe code expire dans 10 minutes.",
                function ($message) use ($request) {
                    $message->to($request->email)
                            ->subject('Code de verification - SIE');
                }
            );

            return response()->json([
                'message' => 'Code de verification envoye avec succes'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Cet email est deja utilise',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Erreur sendCode EmailVerificationController: ' . $e->getMessage());
            return response()->json([
                'message' => 'Erreur lors de l\'envoi du code'
            ], 500);
        }
    }

    /**
     * Renvoyer le code OTP
     */
    public function resendCode(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email'
            ]);

            // Verifier si l'email existe deja dans users
            if (User::where('email', $request->email)->exists()) {
                return response()->json([
                    'message' => 'Cet email est deja utilise'
                ], 422);
            }

            // Generer un nouveau code
            $code = random_int(100000, 999999);

            EmailVerification::updateOrCreate(
                ['email' => $request->email],
                [
                    'code' => Hash::make($code),
                    'expires_at' => now()->addMinutes(10),
                    'verified' => false
                ]
            );

            Mail::raw(
                "Votre nouveau code de verification est : $code\n\nCe code expire dans 10 minutes.",
                function ($message) use ($request) {
                    $message->to($request->email)
                            ->subject('Nouveau code de verification - SIE');
                }
            );

            return response()->json([
                'message' => 'Nouveau code envoye avec succes'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur resendCode EmailVerificationController: ' . $e->getMessage());
            return response()->json([
                'message' => 'Erreur lors du renvoi du code'
            ], 500);
        }
    }

    /**
     * Verifier le code OTP
     */
    public function verifyCode(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'code' => 'required|string|size:6'
            ]);

            $verification = EmailVerification::where('email', $request->email)
                ->where('expires_at', '>', now())
                ->first();

            if (!$verification) {
                return response()->json([
                    'message' => 'Code expire ou email non trouve'
                ], 422);
            }

            // Verifier le code hashe
            if (!Hash::check($request->code, $verification->code)) {
                return response()->json([
                    'message' => 'Code invalide'
                ], 422);
            }

            // Marquer comme verifie
            $verification->update(['verified' => true]);

            return response()->json([
                'message' => 'Email verifie avec succes',
                'verified' => true
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur verifyCode EmailVerificationController: ' . $e->getMessage());
            return response()->json([
                'message' => 'Erreur lors de la verification'
            ], 500);
        }
    }

    /**
     * Verifier si un email est deja verifie
     */
    public function checkVerified(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email'
            ]);

            $verification = EmailVerification::where('email', $request->email)
                ->where('verified', true)
                ->first();

            return response()->json([
                'verified' => $verification !== null
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur checkVerified EmailVerificationController: ' . $e->getMessage());
            return response()->json([
                'message' => 'Erreur lors de la verification'
            ], 500);
        }
    }
}
