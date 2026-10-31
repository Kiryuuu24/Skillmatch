<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'npm',
        'prodi',
        'angkatan',
        'pengalaman_lomba',
        'minat_bidang_lomba',
        'link_portofolio',
        'cv',
        'link_sosmed',
        'is_open_to_join',
        'is_portfolio_public',
        'is_sosmed_public',
    ];

    protected function casts(): array
    {
        return [
            'is_open_to_join' => 'boolean',
            'is_portfolio_public' => 'boolean',
            'is_sosmed_public' => 'boolean',
        ];
    }

    /**
     * Get the user that owns the profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
