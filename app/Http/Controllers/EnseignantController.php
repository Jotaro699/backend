<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Enseignant;

class EnseignantController extends Controller
{
    // Lister tous les enseignants
    public function index()
    {
        // $enseignants = User::where('role', 'enseignant')->get();
        $enseignants = User::where('role', 'enseignant')->with('enseignant')->get();
        return response()->json($enseignants);
    }

    // Supprimer un enseignant
    public function destroy($id)
    {
        $user = User::where('id', $id)->where('role', 'enseignant')->first();

        if (!$user) {
            return response()->json(['message' => 'Enseignant non trouvé.'], 404);
        }

        $user->delete();
        return response()->json(['message' => 'Enseignant supprimé avec succès.']);
    }

    // Mettre à jour un enseignant
    public function update(Request $request, $id)
    {
        $user = User::where('id', $id)->where('role', 'enseignant')->first();

        if (!$user) {
            return response()->json(['message' => 'Enseignant non trouvé.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'prenom' => 'nullable|string|max:255',
            'nom' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $id,
            'date_naissance' => 'nullable|date',
            'genre' => 'nullable|in:homme,femme,autre',
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user->update($validator->validated());

        return response()->json(['message' => 'Enseignant mis à jour avec succès.', 'user' => $user]);
    }
    public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:accepted,rejected,en attente',
    ]);

    $enseignant = Enseignant::where('user_id', $id)->first();
    if (!$enseignant) return response()->json(['error' => 'Enseignant non trouvé'], 404);

    $enseignant->update(['status' => $request->status]);

    return response()->json(['message' => 'Status mis à jour avec succès.']);
}

}
