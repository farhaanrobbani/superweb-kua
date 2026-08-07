<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

#[Fillable(['title', 'description', 'category', 'file', 'external_url', 'active', 'sort_order'])]
class DownloadItem extends Model
{
    use HasFactory;

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('active', true)
            ->orderBy('sort_order')
            ->orderByDesc('id');
    }

    public function isExternal(): bool
    {
        return blank($this->file) && ! blank($this->external_url);
    }

    public function fileName(): ?string
    {
        return $this->file ? basename($this->file) : null;
    }

    public function fileSize(): ?string
    {
        if (! $this->file || ! Storage::disk('public')->exists($this->file)) {
            return null;
        }

        $bytes = Storage::disk('public')->size($this->file);

        return match (true) {
            $bytes >= 1073741824 => number_format($bytes / 1073741824, 2).' GB',
            $bytes >= 1048576 => number_format($bytes / 1048576, 1).' MB',
            $bytes >= 1024 => number_format($bytes / 1024, 0).' KB',
            default => $bytes.' B',
        };
    }

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }
}
