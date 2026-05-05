<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'images' => 'array',
    ];

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'repo_url',
        'date',
        'status',
        'images',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected function casts()
    {
        return [
            'date' => 'date',
            'images' => 'array',
        ];
    }
}
