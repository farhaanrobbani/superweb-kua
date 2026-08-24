<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Fillable(['title', 'slug', 'excerpt', 'content', 'thumbnail', 'video_url', 'author_id', 'published_at', 'active'])]
class Video extends Model
{
    use HasFactory;

    public function thumbnailUrl(): ?string
    {
        return $this->thumbnail && Storage::disk('public')->exists($this->thumbnail)
            ? Storage::disk('public')->url($this->thumbnail)
            : null;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function booted(): void
    {
        static::creating(function (Video $video) {
            if (! $video->slug) {
                $video->slug = Str::slug((string) $video->title) ?: 'video';
            }
        });
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
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
