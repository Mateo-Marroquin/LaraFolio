<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
    ];
}
