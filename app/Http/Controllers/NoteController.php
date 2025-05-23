<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Matiere;
use App\Models\Etudiant;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    // 📌 Récupérer les étudiants d’une matière (via classe)
    public function getEtudiants($matiere_id)
    {
        $matiere = Matiere::findOrFail($matiere_id);

        // On récupère les étudiants de la même classe que la matière
        // $etudiants = Etudiant::where('classe', $matiere->classe)->get();
        $etudiants = Etudiant::with('user')->where('classe', $matiere->classe)->get();


        return response()->json($etudiants);
    }

    // 📌 Enregistrer une note
    public function store(Request $request)
    {
        $validated = $request->validate([
            'etudiant_id' => 'required|exists:etudiants,id',
            'matiere_id' => 'required|exists:matieres,id',
            'enseignant_id' => 'required|exists:users,id',
            // 'enseignant_id' => 'required|exists:enseignants,id',

            'note' => 'required|numeric|min:0|max:20',
            'numero' => 'required|integer|min:1',
        ]);

        $note = Note::create($validated);

        return response()->json([
            'message' => 'Note enregistrée avec succès',
            'note' => $note
        ]);
    }

    // 📌 (Optionnel) Afficher toutes les notes d’un étudiant pour une matière
    public function getNotesByEtudiant($etudiant_id, $matiere_id)
    {
        $notes = Note::where('etudiant_id', $etudiant_id)
                     ->where('matiere_id', $matiere_id)
                     ->get();

        return response()->json($notes);
    }

    // Récupérer toutes les notes d'une matière
// public function getNotesByMatiere($matiere_id)
// {
//     return Note::where('matiere_id', $matiere_id)->get();
// }
// public function getNotesByMatiere($matiere_id)
// {
//     return Note::where('matiere_id', $matiere_id)
//         ->select('etudiant_id', 'note', 'numero') // ضروري ترجع فقط هاد القيم
//         ->get();
// }
public function getNotesByMatiere($matiere_id)
{
    return Note::query()
        ->where('matiere_id', $matiere_id)
        ->select('etudiant_id', 'note', 'numero')
        ->get();
}



// Store or Update
public function storeOrUpdate(Request $request)
{
    $validated = $request->validate([
        'etudiant_id' => 'required|exists:etudiants,id',
        'matiere_id' => 'required|exists:matieres,id',
        'enseignant_id' => 'required|exists:users,id',
        'note' => 'required|numeric|min:0|max:20',
        'numero' => 'required|integer|min:1|max:4',
    ]);

    $note = Note::updateOrCreate(
        [
            'etudiant_id' => $validated['etudiant_id'],
            'matiere_id' => $validated['matiere_id'],
            'numero' => $validated['numero'],
        ],
        [
            'enseignant_id' => $validated['enseignant_id'],
            'note' => $validated['note'],
        ]
    );

    return response()->json(['message' => 'Note enregistrée ou mise à jour', 'note' => $note]);
}
public function getNotesByEtudiantSimple($etudiantId)
{
    $notes = Note::where('etudiant_id', $etudiantId)
        ->with('matiere') // باش نرجعو حتى اسم المادة
        ->get();

    return response()->json($notes);
}

 
}
