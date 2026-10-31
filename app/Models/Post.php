<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'ketua_id',
        'nama_lomba',
        'kategori_lomba',
        'jumlah_dibutuhkan',
        'jumlah_terisi',
        'deskripsi_syarat',
        'deadline_daftar',
        'link_sosmed',
        'status_postingan',
    ];

    protected function casts(): array
    {
        return [
            'deadline_daftar' => 'date',
            'jumlah_dibutuhkan' => 'integer',
            'jumlah_terisi' => 'integer',
        ];
    }

    /**
     * Get the ketua (team leader) of this post.
     */
    public function ketua(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ketua_id');
    }

    /**
     * Get the skill requirements for this post.
     */
    public function skillRequirements(): HasMany
    {
        return $this->hasMany(PostSkillReq::class);
    }

    /**
     * Get the applications for this post.
     */
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    /**
     * Check if the post is full.
     */
    public function isFull(): bool
    {
        return $this->jumlah_terisi >= $this->jumlah_dibutuhkan;
    }

    /**
     * Check if the post deadline has passed.
     */
    public function isPastDeadline(): bool
    {
        return $this->deadline_daftar->isPast();
    }

    /**
     * Check if the post is still open for applications.
     */
    public function isOpenForApplications(): bool
    {
        return $this->status_postingan === 'aktif'
            && ! $this->isFull()
            && ! $this->isPastDeadline();
    }
}
