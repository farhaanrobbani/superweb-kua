<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['code', 'name', 'description', 'fields', 'active'])]
class LetterType extends Model
{
    use HasFactory;
    public function templates(): HasMany
    {
        return $this->hasMany(LetterTemplate::class);
    }

    public function letters(): HasMany
    {
        return $this->hasMany(Letter::class);
    }

    protected function casts(): array
    {
        return [
            'fields' => 'array',
            'active' => 'boolean',
        ];
    }
}
