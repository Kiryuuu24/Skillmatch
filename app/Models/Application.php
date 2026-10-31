<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'pelamar_id',
        'pesan_lamaran',
        'status_pengajuan',
    ];

    /**
     * Get the post.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the applicant (pelamar).
     */
    public function pelamar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pelamar_id');
    }

    /**
     * Check if application is pending.
     */
    public function isPending(): bool
    {
        return $this->status_pengajuan === 'menunggu';
    }

    /**
     * Check if application is accepted.
     */
    public function isAccepted(): bool
    {
        return $this->status_pengajuan === 'diterima';
    }

    /**
     * Check if application is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status_pengajuan === 'ditolak';
    }
}
