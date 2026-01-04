<?php

namespace App\Http\Controllers\Api;

use App\Events\UserActionEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\EmailVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Str;

class UserController extends Controller
{
    // Le chemin où stocker les images de profil (comme défini précédemment)
    private $imagePath = 'profils';

    /**
     * Liste de tous les utilisateurs
     */
    public function index()
    {
        try {
            $users = User::all();

            if ($users->isEmpty()) {
                return response()->json(['message' => 'Aucun utilisateur trouvé'], 404);
            }

            return response()->json([
                'data' => UserResource::collection($users),
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur index UserController: ' . $e->getMessage());
            return response()->json(['message' => 'Erreur serveur'], 500);
        }
    }

    /**
     * Afficher les utilisateurs client
     */
    public function getCustom()
    {
        try {
            $customs = User::with(['roles', 'visaRequests'])
                ->whereHas('roles', function ($query) {
                    $query->where('name',  'custom');
                })
                ->whereHas('visaRequests', function ($q) {
                    $q->whereNotIn('status', ['pending', 'created']);
                })
                ->get();
            return response()->json([
                'data' => $customs,
            ]);
        } catch (\Throwable $e) {
            Log::error('Erreur getCustom UserController: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur serveur'
            ], 500);
        }
    }


    /**
     * Liste des utilisateurs ayant un rôle Spatie
     */
    public function getByRole($role)
    {
        try {
            $users = User::role($role)->with('country')->get();

            if ($users->isEmpty()) {
                return response()->json(['message' => "Aucun utilisateur avec le rôle {$role} trouvé"], 404);
            }

            return response()->json([
                'users' => UserResource::collection($users),
                'message' => "Liste des utilisateurs avec le rôle {$role} récupérée avec succès"
            ]);
        } catch (\Exception $e) {
            Log::error("Erreur getByRole UserController: " . $e->getMessage());
            return response()->json(['message' => 'Erreur serveur'], 500);
        }
    }

    /**
     * Inscription utilisateur (Session)
     * L'email doit etre verifie par OTP avant l'inscription
     */
    public function register(RegisterRequest $request)
    {
        try {
            $validated = $request->validated();

            // Verifier que l'email a ete valide par OTP
            $emailVerified = EmailVerification::where('email', $validated['email'])
                ->where('verified', true)
                ->first();

            if (!$emailVerified) {
                return response()->json([
                    'message' => 'Email non verifie. Veuillez d\'abord verifier votre email.'
                ], 403);
            }

            $validated['password'] = Hash::make($validated['password']);
            $validated['email_verified_at'] = now();

            $user = User::create($validated);
            $user->assignRole('custom');
            $token = $user->createToken('API Token')->plainTextToken;
            Auth::login($user);

            // Supprimer la verification email apres inscription reussie
            $emailVerified->delete();

            // Notification de bienvenue
            UserActionEvent::dispatch(Auth::user(), [
                "type" => "Bienvenue",
                "message" => "Bienvenue dans notre plateforme $user->name."
            ]);

            // Notifier les admins
            $admins = User::whereHas('roles', function ($q) {
                $q->where('name', 'admin');
            })->get();

            foreach ($admins as $admin) {
                UserActionEvent::dispatch(
                    $admin,
                    [
                        "type" => "Nouveau membre",
                        "message" => "Nouvel utilisateur inscrit: $user->name"
                    ]
                );
            }

            return response()->json([
                'message' => 'Inscription reussie.',
                'user' => new UserResource($user),
                'access_token' => $token,
                'email_verified' => true
            ], 201);
        } catch (\Exception $e) {
            Log::error('Erreur register UserController: ' . $e->getMessage());
            return response()->json(['message' => 'Erreur serveur lors de la creation utilisateur'], 500);
        }
    }

    /**
     * Connexion utilisateur (Session Sanctum)
     */
    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->validated();
            if (!Auth::attempt($credentials)) {
                return response()->json(['message' => 'Identifiants invalides'], 401);
            }
            $user = Auth::user();

            $token = $user->createToken('API Token')->plainTextToken;
            return response()->json([
                'message' => 'Connexion réussie',
                'user' => new UserResource($user),
                'access_token' => $token
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur login UserController: ' . $e->getMessage());
            return response()->json(['message' => 'Erreur serveur lors de la connexion'], 500);
        }
    }

    /**
     * Récupération des infos de l'utilisateur connecté
     */
    public function me(Request $request)
    {
        return response()->json([
            'user' => new UserResource($request->user())
        ]);
    }

    /**
     * Vérifier si un email existe déjà
     */
    public function checkEmailExists(Request $request)
    {
        try {
            $validated = $request->validate(['email' => 'required|email']);
            $exists = User::where('email', $validated['email'])->exists();

            return response()->json([
                'exists' => $exists,
                'message' => $exists ? 'Email déjà utilisé' : 'Email disponible'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur checkEmailExists UserController: ' . $e->getMessage());
            return response()->json(['message' => 'Erreur serveur'], 500);
        }
    }

    /**
     * Connexion/Inscription via Firebase OAuth
     */
    public function loginWithFirebase(Request $request)
    {
        try {
            $validated = $request->validate([
                'firebase_token' => 'required|string',
                'firebase_uid' => 'required|string',
                'email' => 'nullable|email',
                'name' => 'nullable|string',
                'photo_url' => 'nullable|string',
                'provider' => 'required|string',
            ]);

            // Chercher l'utilisateur par firebase_uid ou email
            $user = User::where('firebase_uid', $validated['firebase_uid'])
                ->orWhere('email', $validated['email'])
                ->first();

            if (!$user) {
                // Créer un nouvel utilisateur
                $user = User::create([
                    'name' => $validated['name'] ?? 'Utilisateur',
                    'email' => $validated['email'],
                    'firebase_uid' => $validated['firebase_uid'],
                    'image_path' => $validated['photo_url'],
                    'password' => Hash::make(Str::random(32)), // Mot de passe aléatoire
                    'email_verified_at' => now(), // OAuth = email vérifié
                ]);
                $user->assignRole('custom');
            } else {
                // Mettre à jour le firebase_uid si nécessaire
                if (!$user->firebase_uid) {
                    $user->update(['firebase_uid' => $validated['firebase_uid']]);
                }
                // Marquer l'email comme vérifié si ce n'est pas fait
                if (!$user->hasVerifiedEmail()) {
                    $user->markEmailAsVerified();
                }
            }

            Auth::login($user);
            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json([
                'message' => 'Connexion réussie',
                'user' => new UserResource($user),
                'access_token' => $token
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur loginWithFirebase UserController: ' . $e->getMessage());
            return response()->json(['message' => 'Erreur serveur lors de la connexion Firebase'], 500);
        }
    }

    /**
     * Création d’un nouvel utilisateur avec gestion d'image. (Mise à jour)
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $validated = $request->validated();
            $validated['password'] = Hash::make($validated['password']);

            // Initialisation de image_path à null
            $imageFilePath = null;

            // Traitement de l'image si elle est présente
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                // Génération d'un nom de fichier unique et lisible
                $filename = Str::slug($validated['name']) . '_' . time() . '.' . $file->getClientOriginalExtension();
                // Stockage du fichier dans 'storage/app/public/profils'
                $imageFilePath = $file->storeAs($this->imagePath, $filename, 'public');
            }

            // Assurez-vous que le champ existe dans le modèle User
            $validated['image_path'] = $imageFilePath;

            $user = User::create($validated);
            $user->assignRole($validated['role']);

            return response()->json([
                'message' => 'Utilisateur créé avec succès',
                'data' => new UserResource($user)
            ], 201);
        } catch (\Exception $e) {
            // NOTE: Le log d'erreur a été corrigé pour refléter la méthode 'store' au lieu de 'register'
            Log::error('Erreur store UserController: ' . $e->getMessage());
            return response()->json(['message' => 'Erreur serveur lors de la création utilisateur'], 500);
        }
    }

    /**
     * Mise à jour d’un utilisateur avec gestion d'image. (Mise à jour)
     */
    public function update(UpdateUserRequest $request, $id)
    {
        try {
            $user = User::findOrFail($id); // Utilisation de findOrFail pour une meilleure gestion d'erreur 404
            $validated = $request->validated();

            if (!empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                // S'assurer que le champ password n'est pas inclus s'il est vide
                unset($validated['password']);
            }

            // Traitement de l'image si elle est présente
            if ($request->hasFile('image')) {
                // 1. Suppression de l'ancienne image si elle existe
                if ($user->image_path) {
                    Storage::disk('public')->delete($user->image_path);
                }

                // 2. Upload de la nouvelle image
                $file = $request->file('image');
                $filename = Str::slug($user->name ?? 'user') . '_' . time() . '.' . $file->getClientOriginalExtension();
                $imageFilePath = $file->storeAs($this->imagePath, $filename, 'public');

                $validated['image_path'] = $imageFilePath;
            }

            if (!empty($validated['role'])) {
                $user->syncRoles([$validated['role']]);
            }

            $user->update($validated);

            return response()->json([
                'message' => 'Utilisateur mis à jour avec succès',
                'data' => new UserResource($user)
            ]);
        } catch (ModelNotFoundException $e) {
            // Le message d'erreur a été corrigé ('ckckc' retiré)
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        } catch (\Exception $e) {
            Log::error('Erreur update UserController: ' . $e->getMessage());
            return response()->json(['message' => 'Erreur serveur lors de la mise à jour utilisateur'], 500);
        }
    }

    /**
     * Déconnexion (Session)
     */
    public function logout()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouve'], 404);
        }
        $user->currentAccessToken()->delete();
        return response()->json(['message' => 'Utilisateur deconnecter avec success'], 200);
    }

    /**
     * Envoi d’un lien de réinitialisation du mot de passe
     */
    public function forgotPassword(Request $request)
    {
        try {
            $validated = $request->validate(['email' => 'required|email|exists:users,email']);
            $status = Password::sendResetLink($validated);

            return $status === Password::RESET_LINK_SENT
                ? response()->json(['message' => 'Email de réinitialisation envoyé'])
                : response()->json(['message' => 'Impossible d\'envoyer l\'email'], 500);
        } catch (\Exception $e) {
            Log::error('Erreur forgotPassword UserController: ' . $e->getMessage());
            return response()->json(['message' => 'Erreur serveur lors de l\'envoi du lien'], 500);
        }
    }

    /**
     * Réinitialisation du mot de passe
     */
    public function resetPassword(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email|exists:users,email',
                'password' => 'required|string|min:6|confirmed',
                'token' => 'required|string'
            ]);

            $status = Password::reset($validated, function ($user, $password) {
                $user->forceFill(['password' => Hash::make($password)])->save();
            });

            return $status === Password::PASSWORD_RESET
                ? response()->json(['message' => 'Mot de passe réinitialisé avec succès'])
                : response()->json(['message' => 'Erreur lors de la réinitialisation'], 500);
        } catch (\Exception $e) {
            Log::error('Erreur resetPassword UserController: ' . $e->getMessage());
            return response()->json(['message' => 'Erreur serveur lors de la réinitialisation'], 500);
        }
    }

    /**
     * Afficher un utilisateur
     */
    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json([
                'message' => 'Utilisateur récupéré avec succès',
                'data' => new UserResource($user)
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        } catch (\Exception $e) {
            Log::error('Erreur show UserController: ' . $e->getMessage());
            return response()->json(['message' => 'Erreur serveur'], 500);
        }
    }
    public function showByAgent($id)
    {
        try {
            $user = User::findOrFail($id);
            $user['numberVisaRequestPending'] = $user->visaRequests()->where('status', 'processing')->count();
            $user['numberMessageUnRead'] = $user->messages()->where('status', 'sent')->count();

            return response()->json([
                'message' => 'Utilisateur récupéré avec succès',
                'data' => $user
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        } catch (\Exception $e) {
            Log::error('Erreur show UserController: ' . $e->getMessage());
            return response()->json(['message' => 'Erreur serveur'], 500);
        }
    }

    /**
     * Suppression d’un utilisateur avec suppression de l'image associée. (Mise à jour)
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            // Suppression de l'image associée si elle existe
            if ($user->image_path) {
                Storage::disk('public')->delete($user->image_path);
            }

            $user->delete();

            return response()->json(['message' => 'Utilisateur supprimé avec succès']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        } catch (\Exception $e) {
            Log::error('Erreur destroy UserController: ' . $e->getMessage());
            return response()->json(['message' => 'Erreur serveur lors de la suppression'], 500);
        }
    }

    /**
     * Demande de suppression de compte (RGPD)
     * Enregistre la demande et envoie un email de confirmation
     */
    public function requestAccountDeletion(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users,email',
                'reason' => 'nullable|string|max:1000',
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json([
                    'message' => 'Aucun compte trouve avec cet email'
                ], 404);
            }

            // Envoyer un email de confirmation a l'administrateur
            \Illuminate\Support\Facades\Mail::raw(
                "Demande de suppression de compte\n\n" .
                "Email: {$request->email}\n" .
                "Nom: {$user->name}\n" .
                "Date: " . now()->format('d/m/Y H:i') . "\n" .
                "Raison: " . ($request->reason ?? 'Non specifiee') . "\n\n" .
                "Veuillez traiter cette demande dans un delai de 30 jours conformement au RGPD.",
                function ($message) {
                    $message->to(config('mail.from.address'))
                            ->subject('Demande de suppression de compte - SIE');
                }
            );

            // Envoyer un email de confirmation a l'utilisateur
            \Illuminate\Support\Facades\Mail::raw(
                "Bonjour {$user->name},\n\n" .
                "Nous avons bien recu votre demande de suppression de compte.\n\n" .
                "Votre demande sera traitee dans un delai maximum de 30 jours conformement au RGPD.\n" .
                "Vous recevrez une confirmation une fois la suppression effectuee.\n\n" .
                "Si vous n'etes pas a l'origine de cette demande, veuillez nous contacter immediatement.\n\n" .
                "Cordialement,\nL'equipe SIE",
                function ($message) use ($request) {
                    $message->to($request->email)
                            ->subject('Confirmation de demande de suppression - SIE');
                }
            );

            return response()->json([
                'message' => 'Demande de suppression enregistree avec succes'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Aucun compte trouve avec cet email',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Erreur requestAccountDeletion UserController: ' . $e->getMessage());
            return response()->json([
                'message' => 'Erreur lors de la demande de suppression'
            ], 500);
        }
    }
}
