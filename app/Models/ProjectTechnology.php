<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectTechnology extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'technology_id',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function technology()
    {
        return $this->belongsTo(Technology::class);
    }
}
