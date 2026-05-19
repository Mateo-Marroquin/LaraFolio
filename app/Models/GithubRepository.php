<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class GithubRepository extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'github_repo_id',
        'name',
        'full_name',
        'html_url',
        'description',
        'primary_language',
        'stars_count',
        'forks_count',
        'is_fork',
        'github_updated_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return [
            'is_fork' => 'boolean',
            'github_updated_at' => 'timestamp',
        ];
    }
}
