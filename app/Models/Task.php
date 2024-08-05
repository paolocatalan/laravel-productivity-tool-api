<?php

namespace App\Models;

use App\Models\v1\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'project_id', 'name', 'description', 'due_date', 'priority', 'stage'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function subtask()
    {
        return $this->hasMany(Subtask::class);
    }
}
