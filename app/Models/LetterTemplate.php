<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['letter_type_id', 'name', 'body', 'active'])]
class LetterTemplate extends Model
{
    use HasFactory;
    public function letterType(): BelongsTo
    {
        return $this->belongsTo(LetterType::class);
    }

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }
}
