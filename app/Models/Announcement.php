<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

#[Fillable(['title', 'content', 'image', 'published_at', 'active'])]
class Announcement extends Model
{
    use HasFactory;

    public function imageUrl(): ?string
    {
        return $this->image && Storage::disk('public')->exists($this->image)
            ? Storage::disk('public')->url($this->image)
            : null;
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('active', true)
            ->where(fn (Builder $q) => $q->whereNull('published_at')->orWhere('published_at', '<=', now()))
            ->orderByDesc('published_at')
            ->orderByDesc('id');
    }

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'published_at' => 'datetime',
        ];
    }
}
