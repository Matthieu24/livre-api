<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'author',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'borrowings', 'user_id', 'book_id')->withPivot('borrowing_at', 'return_at');
    }

    public function getIsBorrowedAttribute(): bool
    {
        $lastBorrowing = Borrowing::where('book_id', $this->id)
            ->orderBy('borrowing_at', 'desc')
            ->first();
        return $lastBorrowing && is_null($lastBorrowing->return_at);
    }
}