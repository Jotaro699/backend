<?php

namespace App\Http\Controllers;

use App\Models\Cours;
use Illuminate\Http\Request;

class CoursController extends Controller
{
    public function index()
    {
        return Cours::with(['matiere', 'enseignant.user'])->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'matiere_id' => 'required|exists:matieres,id',
            'enseignant_id' => 'required|exists:enseignants,id',
        ]);

        $cours = Cours::create($validated);
        return response()->json($cours, 201);
    }

    public function update(Request $request, Cours $cour)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'matiere_id' => 'required|exists:matieres,id',
            'enseignant_id' => 'required|exists:enseignants,id',
        ]);

        $cour->update($validated);
        return response()->json($cour);
    }

    public function destroy(Cours $cour)
    {
        $cour->delete();
        return response()->json(['message' => 'Cours supprimé']);
    }
}

