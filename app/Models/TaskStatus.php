<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskStatus extends Model
{
    /** @use HasFactory<\Database\Factories\TaskStatusFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    protected $dates = [
        'created_at'
    ];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'status_id');
    }
}
