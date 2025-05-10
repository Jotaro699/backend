<?php

namespace App\Http\Controllers;

use App\Models\Matiere;
use Illuminate\Http\Request;

class MatiereController extends Controller
{
    public function index()
    {
        return Matiere::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        return Matiere::create($request->all());
    }

    public function show($id)
    {
        return Matiere::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $matiere = Matiere::findOrFail($id);
        $matiere->update($request->all());
        return $matiere;
    }

    public function destroy($id)
    {
        Matiere::destroy($id);
        return response()->json(['message' => 'Supprimé avec succès']);
    }
}
