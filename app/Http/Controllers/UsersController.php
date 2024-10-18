<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    // Retourne la liste de tous les utilisateurs avec leurs livres actuellement empruntés.
    public function index()
    {
        return User::all()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'borrowed_books' => $user->borrowed_books->map(function ($book) {
                    return [
                        'id' => $book->id,
                        'name' => $book->name,
                        'author' => $book->author,
                    ];
                }),
            ];
        });
    }

    // Retourne les informations d'un utilisateur spécifique et ses livres empruntés.
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé.'], 404);
        }

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'borrowed_books' => $user->borrowed_books->map(function ($book) {
                return [
                    'id' => $book->id,
                    'name' => $book->name,
                    'author' => $book->author,
                ];
            }),
        ];
    }

    // Crée un nouvel utilisateur.
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ]);

        User::create($request->all());

        return response()->json(['message' => 'Utilisateur créé avec succès.'], 201);
    }

    // Met à jour un utilisateur existant.
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'required',
        ]);

        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé.'], 404);
        }

        $user->update($request->all());

        return response()->json(['message' => 'Utilisateur mis à jour avec succès.']);
    }

    // Supprime un utilisateur.
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé.'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'Utilisateur supprimé avec succès.']);
    }
}