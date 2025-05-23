<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Etudiant;


class EtudiantController extends Controller
{
    // Afficher tous les utilisateurs avec le rôle "etudiant"
    // public function index()
    // {
    //     $etudiants = User::where('role', 'etudiant')->get();
    //     return response()->json($etudiants);
    // }
    public function index()
{
    $etudiants = Etudiant::with(['user', 'parent.user'])->get();
    return response()->json($etudiants);
}


    // Supprimer un étudiant
    public function destroy($id)
    {
        // $etudiant = User::where('role', 'etudiant')->findOrFail($id);
        // $etudiant->delete();

        // return response()->json(['message' => 'Étudiant supprimé avec succès.']);
        $user = User::where('role', 'etudiant')->findOrFail($id);

    // Supprimer aussi l'enregistrement Etudiant lié
    Etudiant::where('user_id', $user->id)->delete();

    $user->delete();

    return response()->json(['message' => 'Étudiant supprimé avec succès.']);
    }

    // Modifier un étudiant
    // public function update(Request $request, $id)
    // {
    //     $etudiant = User::where('role', 'etudiant')->findOrFail($id);

    //     $validated = $request->validate([
    //         'prenom' => 'nullable|string|max:255',
    //         'nom' => 'nullable|string|max:255',
    //         'email' => 'nullable|email|unique:users,email,' . $id,
    //         'date_naissance' => 'nullable|date',
    //         'genre' => 'nullable|in:homme,femme,autre',
    //         'telephone' => 'nullable|string|max:20',
    //         'adresse' => 'nullable|string|max:255',
    //     ]);

    //     $etudiant->update($validated);

    //     return response()->json($etudiant);
    // }
    public function update(Request $request, $id)
{
    $user = User::where('role', 'etudiant')->findOrFail($id);
    $etudiant = Etudiant::where('user_id', $user->id)->firstOrFail();

    // ✅ validation
    $validatedUser = $request->validate([
        'prenom' => 'nullable|string|max:255',
        'nom' => 'nullable|string|max:255',
        'email' => 'nullable|email|unique:users,email,' . $user->id,
        'date_naissance' => 'nullable|date',
        'genre' => 'nullable|in:homme,femme,autre',
        'telephone' => 'nullable|string|max:20',
        'adresse' => 'nullable|string|max:255',
    ]);

    $validatedEtudiant = $request->validate([
        'cne' => 'nullable|string|unique:etudiants,cne,' . $etudiant->id,
        'classe' => 'nullable|string|max:255',
        'parent_id' => 'nullable|exists:parent_models,id',
        'acte_naissance' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'photo_identite' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
    ]);

    // ✅ mise à jour des fichiers si fournis
    if ($request->hasFile('acte_naissance')) {
        $validatedEtudiant['acte_naissance'] = $request->file('acte_naissance')->store('actes');
    }

    if ($request->hasFile('photo_identite')) {
        $validatedEtudiant['photo_identite'] = $request->file('photo_identite')->store('photos');
    }

    // ✅ mise à jour des modèles
    $user->update($validatedUser);
    $etudiant->update($validatedEtudiant);

    return response()->json(['message' => 'Étudiant mis à jour avec succès']);
}

//     public function store(Request $request)
// {
//     // ✅ validation des champs utilisateur
//     $validatedUser = $request->validate([
//         'nom' => 'required|string|max:255',
//         'prenom' => 'required|string|max:255',
//         'email' => 'required|email|unique:users,email',
//         'password' => 'required|string|min:6',
//         'telephone' => 'nullable|string|max:20',
//         'date_naissance' => 'nullable|date',
//         'genre' => 'nullable|in:homme,femme,autre',
//     ]);

//     // ✅ validation des champs étudiant
//     $validatedEtudiant = $request->validate([
//         'cne' => 'required|string|unique:etudiants,cne',
//         'classe' => 'required|string|max:255',
//         'parent_id' => 'nullable|exists:parent_models,id',
//     ]);

//     // ✅ création de l'utilisateur
//     // $user = User::create([
//     //     ...$validatedUser,
//     //     'role' => 'etudiant',
//     //     'password' => bcrypt($validatedUser['password']),
//     // ]);
//     $user = User::create(array_merge(
//     $validatedUser,
//     [
//         'role' => 'etudiant',
//         'password' => bcrypt($validatedUser['password']),
//     ]
// ));


//     // ✅ création de l'étudiant
//     // $etudiant = Etudiant::create([
//     //     ...$validatedEtudiant,
//     //     'user_id' => $user->id,
//     //     'acte_naissance' => null, // ✅ ضروري باش ما يطيحش الخطأ
//     //     'photo_identite' => null
        
//     // ]);
//     $etudiant=Etudiant::create(array_merge(
//     $validatedEtudiant,
//     [
//         'user_id' => $user->id,
//         'acte_naissance' => '  ',
//         'photo_identite' => '  ',
//     ]
// ));


//     return response()->json($etudiant->load('user', 'parent.user'));
// }

public function store(Request $request)
{
    $validatedUser = $request->validate([
        'nom' => 'required|string|max:255',
        'prenom' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6',
        'telephone' => 'nullable|string|max:20',
        'date_naissance' => 'nullable|date',
        'genre' => 'nullable|in:homme,femme,autre',
    ]);

    $validatedEtudiant = $request->validate([
        'cne' => 'required|string|unique:etudiants,cne',
        'classe' => 'required|string|max:255',
        'parent_id' => 'nullable|exists:parent_models,id',
        'acte_naissance' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'photo_identite' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
    ]);

    $user = User::create(array_merge($validatedUser, [
        'role' => 'etudiant',
        'password' => bcrypt($validatedUser['password']),
    ]));

    // 📂 Upload fichiers
    $acte = $request->file('acte_naissance')?->store('actes');
    $photo = $request->file('photo_identite')?->store('photos');

    $etudiant = Etudiant::create(array_merge($validatedEtudiant, [
        'user_id' => $user->id,
        'acte_naissance' => $acte,
        'photo_identite' => $photo,
    ]));

    return response()->json(['message' => 'Étudiant créé avec succès']);
}
 // EtudiantController.php
public function getByClasse($classe)
{
    return Etudiant::with('user')->where('classe', $classe)->get();
}
public function getEtudiantByUser($userId)
{
    $etudiant = Etudiant::where('user_id', $userId)->with('user')->first();

    if (!$etudiant) {
        return response()->json(['message' => 'Etudiant non trouvé'], 404);
    }

    return response()->json($etudiant);
}

    
}
