<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['code', 'name', 'description', 'permohonan_body', 'fields', 'active', 'publik'])]
class LetterType extends Model
{
    use HasFactory;

    public function scopePublik($query)
    {
        return $query->where('publik', true);
    }

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
            'publik' => 'boolean',
        ];
    }
}
