<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Status extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'order',
    ];

    /* Start Relationships */
    
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'status_id');
    }

    /* End Relationships */
}
