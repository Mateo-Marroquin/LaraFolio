<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class GithubProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'github_id',
        'username',
        'name',
        'avatar_url',
        'bio',
        'location',
        'public_repos',
        'followers',
        'user_id',
        'email',
        'company',
        'blog',
        'twitter_username',
        'hireable'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
