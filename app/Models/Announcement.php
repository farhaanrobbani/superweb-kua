<?php

namespace App\Models;

use App\Enums\AnnouncementCategory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Fillable(['title', 'slug', 'content', 'excerpt', 'category', 'image', 'video_url', 'author_id', 'published_at', 'active'])]
class Announcement extends Model
{
    use HasFactory;

    public function imageUrl(): ?string
    {
        return $this->image && Storage::disk('public')->exists($this->image)
            ? Storage::disk('public')->url($this->image)
            : null;
    }

    public function excerpt(): string
    {
        return $this->excerpt
            ?: Str::limit(strip_tags((string) $this->content), 160);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function booted(): void
    {
        static::creating(function (Announcement $announcement) {
            if (! $announcement->slug) {
                $announcement->slug = Str::slug((string) $announcement->title) ?: 'pengumuman';
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
            'category' => AnnouncementCategory::class,
        ];
    }
}
