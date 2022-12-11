<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = ['task_id', 'assignee_id', 'is_approved'];

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class,'assignee_id');
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class,'task_id');
    }
}
