<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GithubActivity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'github_event_id',
        'username',
        'type',
        'date',
        'is_private'
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'is_private' => 'boolean'
        ];
    }
}
