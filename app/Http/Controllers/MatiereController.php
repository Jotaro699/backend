<?php

// namespace App\Http\Controllers;

// use App\Models\Matiere;
// use Illuminate\Http\Request;

// class MatiereController extends Controller
// {
//     public function index()
//     {
//         return Matiere::all();
//     }

//     public function store(Request $request)
//     {
//         $request->validate([
//             'nom' => 'required|string|max:255',
//             'description' => 'nullable|string',
//         ]);

//         return Matiere::create($request->all());
//     }

//     public function show($id)
//     {
//         return Matiere::findOrFail($id);
//     }

//     public function update(Request $request, $id)
//     {
//         $matiere = Matiere::findOrFail($id);
//         $matiere->update($request->all());
//         return $matiere;
//     }

//     public function destroy($id)
//     {
//         Matiere::destroy($id);
//         return response()->json(['message' => 'Supprimé avec succès']);
//     }
// }


namespace App\Http\Controllers;

use App\Models\Matiere;
use Illuminate\Http\Request;

class MatiereController extends Controller
{
    public function index()
    {
        // return Matiere::with('enseignant')->get(); // kayjib m3ah l'enseignant
        return Matiere::with('enseignant.user')->get(); 
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'coefficient' => 'required|integer|min:1',
            'niveau' => 'required|in:primaire,college,lycée',
            'classe' => 'required|string|max:255',
            'enseignant_id' => 'required|exists:enseignants,id',
            // 'enseignant_id' => 'required|exists:users,id',

        ]);

        return Matiere::create($request->all());
    }

    public function show($id)
    {
        return Matiere::with('enseignant')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $matiere = Matiere::findOrFail($id);

        $request->validate([
            'nom' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'coefficient' => 'sometimes|integer|min:1',
            'niveau' => 'sometimes|in:primaire,college,lycée',
            'classe' => 'sometimes|string|max:255',
            'enseignant_id' => 'sometimes|exists:enseignants,id',
        ]);

        $matiere->update($request->all());
        return $matiere;
    }

    public function destroy($id)
    {
        Matiere::destroy($id);
        return response()->json(['message' => 'Supprimé avec succès']);
    }
}
