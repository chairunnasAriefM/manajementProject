<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'project_id',
        'title',
        'description',
        'assigned_to',
        'due_date',
        'status',
    ];

    // Relasi ke Project
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
