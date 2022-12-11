<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['assignor_id', 'title', 'description', 'deadline', 'status'];

    protected $casts = ['deadline' => 'immutable_datetime'];

    public function assignor(): BelongsTo
    {
        return $this->belongsTo(User::class,'assignor_id');
    }

    public function assignment(): HasOne
    {
        return $this->hasOne(Assignment::class);
    }
}
