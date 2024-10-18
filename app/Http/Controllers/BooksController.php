<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    // Retourne la liste de tous les livres avec leur statut d'emprunt.
    public function index()
    {
        return Book::all()->map(function ($book) {
            return [
                'id' => $book->id,
                'name' => $book->name,
                'author' => $book->author,
                'is_borrowed' => $book->is_borrowed,
            ];
        });
    }

    // Retourne les informations d'un livre spécifique et son statut d'emprunt.
    public function show($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['message' => 'Livre non trouvé.'], 404);
        }

        return [
            'id' => $book->id,
            'name' => $book->name,
            'author' => $book->author,
            'is_borrowed' => $book->is_borrowed,
        ];
    }

    // Crée un nouveau livre.
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'author' => 'required',
        ]);

        Book::create($request->all());

        return response()->json(['message' => 'Livre créé avec succès.'], 201);
    }

    // Met à jour un livre existant.
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'author' => 'required',
        ]);

        $book = Book::find($id);

        if (!$book) {
            return response()->json(['message' => 'Livre non trouvé.'], 404);
        }

        $book->update($request->all());

        return response()->json(['message' => 'Livre mis à jour avec succès.']);
    }

    // Supprime un livre.
    public function destroy($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['message' => 'Livre non trouvé.'], 404);
        }

        $book->delete();

        return response()->json(['message' => 'Livre supprimé avec succès.']);
    }
}