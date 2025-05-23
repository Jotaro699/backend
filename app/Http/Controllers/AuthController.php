<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Etudiant;
use App\Models\Enseignant;
use App\Models\ParentModel;
use App\Models\User;

class AuthController extends Controller
{  
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid login'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;


            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                // 'user' => $user, // 👈 AJOUT ICI
                'user' => $user->load('enseignant'),

]);

    }

//     public function register(Request $request)
// {
//     // Validation des données
//     $validator = Validator::make($request->all(), [
//         'name' => 'required|string|max:255',
//         'email' => 'required|string|email|max:255|unique:users',
//         'password' => 'required|string|min:6',
//         'role' => 'required|string|in:etudiant,enseignant,parent,admin',
//     ]);

//     // Retourner les erreurs de validation si elles existent
//     if ($validator->fails()) {
//         return response()->json(['errors' => $validator->errors()], 422);
//     }

//     try {
//         // Création de l'utilisateur
//         $user = User::create([
//             'name' => $request->name,
//             'email' => $request->email,
//             'password' => Hash::make($request->password),
//             'role' => $request->role,
//         ]);

//         $token = $user->createToken('auth_token')->plainTextToken;

//         return response()->json([
//             'token' => $token,
//             'user' => $user,
//         ], 201);
//     } catch (\Exception $e) {
//         // Log l'erreur pour le débogage
//         \Log::error('Registration Error: ' . $e->getMessage());
//         return response()->json(['error' => 'Something went wrong'], 500);
//     }
// }

//correct
// public function register(Request $request)
// {
//     $validated = $request->validate([
//         'prenom' => 'nullable|string|max:255',
//         'nom' => 'nullable|string|max:255',
//         'email' => 'required|string|email|max:255|unique:users',
//         'password' => 'required|string|min:6',
//         'date_naissance' => 'nullable|date',
//         'genre' => 'nullable|in:homme,femme,autre',
//         'telephone' => 'nullable|string|max:20',
//         'adresse' => 'nullable|string|max:255',
//         'role' => 'required|in:admin,enseignant,etudiant,parent',
//     ]);

//     $user = User::create([
//         'prenom' => $validated['prenom'],
//         'nom' => $validated['nom'],
//         'email' => $validated['email'],
//         'password' => Hash::make($validated['password']),
//         'date_naissance' => $validated['date_naissance'],
//         'genre' => $validated['genre'],
//         'telephone' => $validated['telephone'],
//         'adresse' => $validated['adresse'],
//         'role' => $validated['role'],
//     ]);

//     $token = $user->createToken('auth_token')->plainTextToken;

//     return response()->json([
//         'token' => $token,
//         'user' => $user,
//     ]);
// }

public function register(Request $request)
{
    $validator = Validator::make($request->all(), [
        'prenom' => 'nullable|string|max:255',
        'nom' => 'nullable|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email',
        'password' => 'required|string|min:6',
        'date_naissance' => 'nullable|date',
        'genre' => 'nullable|in:homme,femme,autre',
        'telephone' => 'nullable|string|max:20',
        'adresse' => 'nullable|string|max:255',
        'role' => 'required|in:admin,enseignant,etudiant,parent',

        // Champs spécifiques
        'cne' => 'nullable|string|unique:etudiants,cne',
        'classe' => 'nullable|string',
        'acte_naissance' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
        'photo_identite' => 'nullable|file|mimes:jpg,jpeg,png',
        'parent_id' => 'nullable|exists:parents,id',

        'specialite' => 'nullable|string',
        'cv' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
        'grade' => 'nullable|string',
        'cin' => [
            'nullable',
            'string',
            Rule::unique('enseignants', 'cin'),
            Rule::unique('parents', 'cin'),
        ],

        'profession' => 'nullable|string',
        'etudiant_cne' => 'nullable|string|exists:etudiants,cne',
    ], [
        'email.unique' => 'L\'email est déjà utilisé.',
        'cne.unique' => 'Le CNE est déjà utilisé.',
        'cin.unique' => 'Le CIN est déjà utilisé.',
        'etudiant_cne.exists' => 'Ce CNE n\'existe pas.',
        'parent_id.exists' => 'Le parent spécifié n\'existe pas.',
        'acte_naissance.mimes' => 'Le fichier de l\'acte de naissance doit être un PDF ou une image.',
        'photo_identite.mimes' => 'La photo d\'identité doit être une image.',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $validated = $validator->validated();
    $cvPath = $request->file('cv')?->store('uploads/cv', 'public');

    $user = User::create([
        'prenom' => $validated['prenom'],
        'nom' => $validated['nom'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'date_naissance' => $validated['date_naissance'],
        'genre' => $validated['genre'],
        'telephone' => $validated['telephone'],
        'adresse' => $validated['adresse'],
        'role' => $validated['role'],
    ]);

    // Traitement spécifique selon le rôle
    switch ($user->role) {
        case 'etudiant':
            $actePath = $request->file('acte_naissance')?->store('uploads/actes', 'public');
            $photoPath = $request->file('photo_identite')?->store('uploads/photos', 'public');

            Etudiant::create([
                'user_id' => $user->id,
                'cne' => $validated['cne'],
                'classe' => $validated['classe'],
                'acte_naissance' => $actePath,
                'photo_identite' => $photoPath,
                'parent_id' => $validated['parent_id'] ?? null,
            ]);
            break;

        case 'enseignant':
            Enseignant::create([
                'user_id' => $user->id,
                'specialite' => $validated['specialite'],
                'grade' => $validated['grade'],
                'cin' => $validated['cin'],
                'cv' => $cvPath,
                'status' => 'en attente'
            ]);
            break;

case 'parent':
    $parent = ParentModel::create([
        'user_id' => $user->id,
        'profession' => $validated['profession'],
        'cin' => $validated['cin'],
        'etudiant_cne' => $validated['etudiant_cne'] ?? null,
    ]);

    // ✅ تحديث etudiant.parent_id تلقائياً
    if (!empty($validated['etudiant_cne'])) {
        $etudiant = Etudiant::where('cne', $validated['etudiant_cne'])->first();
        if ($etudiant) {
            $etudiant->update(['parent_id' => $parent->id]);
        }
    }
            break;
    }

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'token' => $token,
        'user' => $user,
    ], 201);
}
// public function destroyEnseignant($id)
// {
//     // نلقاو الأستاذ من table users
//     $user = User::where('id', $id)->where('role', 'enseignant')->first();

//     if (!$user) {
//         return response()->json(['message' => 'Enseignant non trouvé.'], 404);
//     }

//     // نحيدو الأستاذ واللي مرتبط به
//     $user->delete(); // cascade suppression normalement kayhayed men `enseignants` par foreign key

//     return response()->json(['message' => 'Enseignant supprimé avec succès.']);
// }
public function getEnfants(Request $request)
{
    $user = Auth::user();

    // ✅ Vérification si l'utilisateur est null
    if (!$user) {
        return response()->json(['message' => 'Utilisateur non connecté'], 401);
    }

    if ($user->role !== 'parent') {
        return response()->json(['message' => 'Accès refusé'], 403);
    }

    $parent = ParentModel::where('user_id', $user->id)->first();

    if (!$parent) {
        return response()->json(['message' => 'Parent introuvable'], 404);
    }

    $enfants = Etudiant::with('user')
        ->where('parent_id', $parent->id)
        ->get();

    return response()->json($enfants);
}


}
