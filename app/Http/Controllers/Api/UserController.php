<?php

namespace App\Http\Controllers\Api;

use App\Events\UserActionEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
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
     */
    public function register(RegisterRequest $request)
    {
        try {
            $validated = $request->validated();
            $validated['password'] = Hash::make($validated['password']);

            $user = User::create($validated);
            $user->assignRole('custom');
            $token = $user->createToken('API Token')->plainTextToken;
            Auth::login($user);

            UserActionEvent::dispatch(Auth::user(), [
                "type" => "Bienvue",
                "message" => "Bienvue dans notre plateforme $user->name"
            ]);
            return response()->json([
                'message' => 'Utilisateur créé et connecté avec succès',
                'user' => new UserResource($user),
                'access_token' => $token
            ], 201);
        } catch (\Exception $e) {
            Log::error('Erreur register UserController: ' . $e->getMessage());
            return response()->json(['message' => 'Erreur serveur lors de la création utilisateur'], 500);
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

            UserActionEvent::dispatch(Auth::user(), [
                "type" => "Connexion",
                "message" => "Nous sommes ocntent de vous revoir $user->name"
            ]);
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
    public function store(StoreUserRequest $request)
    {
        try {
            $validated = $request->validated();
            $validated['password'] = Hash::make($validated['password']);

            $user = User::create($validated);
            $user->assignRole($validated['role']);

            return response()->json([
                'message' => 'Utilisateur créé avec succès',
                'data' => new UserResource($user)
            ], 201);
        } catch (\Exception $e) {
            Log::error('Erreur register UserController: ' . $e->getMessage());
            return response()->json(['message' => 'Erreur serveur lors de la création utilisateur'], 500);
        }
    }
    /**
     * Mise à jour d’un utilisateur
     */
    public function update(UpdateUserRequest $request, $id)
    {
        try {
            $user = User::find($id);
            $validated = $request->validated();

            if (!empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
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
            return response()->json(['message' => 'Utilisateur non trouvé ckckc'], 404);
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
        $user->tokens()->delete();
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
            $user['numberVisaRequestPending'] = $user->visaRequests()->where('status', 'pending')->count();
            $user['numberMessageUnRead'] = $user->messages()->where('status', 'sent')->count();
            Log::info('hsdbjksd', [$user]);

            Log::info('gvfcvgj', [$user]);
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
     * Suppression d’un utilisateur
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json(['message' => 'Utilisateur supprimé avec succès']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        } catch (\Exception $e) {
            Log::error('Erreur destroy UserController: ' . $e->getMessage());
            return response()->json(['message' => 'Erreur serveur lors de la suppression'], 500);
        }
    }
}
