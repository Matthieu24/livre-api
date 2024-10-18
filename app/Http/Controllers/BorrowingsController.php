<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BorrowingsController extends Controller
{
    public function store(Request $request)
    {
        $user = User::find($request->user_id);

        $borrowingsThisWeek = $user->books()
            ->wherePivot('borrowing_at', '>=', Carbon::now()->startOfWeek())
            ->wherePivot('borrowing_at', '<', Carbon::now()->endOfWeek())
            ->count();

        if ($borrowingsThisWeek >= 2) {
            return response()->json(['message' => 'Vous avez déjà emprunté 2 livres cette semaine.'], 400);
        }

        $book = Book::find($request->book_id);

        if (!$book) {
            return response()->json(['message' => 'Livre non trouvé.'], 404);
        }

        if ($book->is_borrowed) {
            return response()->json(['message' => 'Livre déjà emprunter.'], 400);
        }

        $user->books()->attach($book->id, [
            'borrowing_at' => Carbon::now(),
            'return_at' => null,
        ]);

        return response()->json(['message' => 'Livre emprunté avec succès.'], 201);
    }

    public function returnBook(Request $request, $bookId)
    {
        $user = User::find($request->user_id);

        $borrowing = $user->books()
            ->wherePivot('book_id', $bookId)
            ->wherePivot('return_at', null)
            ->first();

        if (!$borrowing) {
            return response()->json(['message' => 'Emprunt non trouvé ou livre déjà rendu.'], 404);
        }

        $user->books()->updateExistingPivot($bookId, [
            'return_at' => Carbon::now(),
        ]);

        return response()->json(['message' => 'Livre rendu avec succès.'], 200);
    }
}