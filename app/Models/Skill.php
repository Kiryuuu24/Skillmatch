<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_skill',
        'kategori_skill',
    ];

    /**
     * Get the user skills for this skill.
     */
    public function userSkills(): HasMany
    {
        return $this->hasMany(UserSkill::class);
    }

    /**
     * Get the post skill requirements for this skill.
     */
    public function postSkillReqs(): HasMany
    {
        return $this->hasMany(PostSkillReq::class);
    }
}
