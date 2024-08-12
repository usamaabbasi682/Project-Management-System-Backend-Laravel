<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'name',
        'prefix',
        'color',
        'budget',
        'budget_type',
        'currency',
        'description',
        'status',
        'status_color',
    ];

    /* Relationships */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_users');
    }

    public function client(): BelongsTo 
    {
        return $this->belongsTo(User::class,'client_id');
    }

    public function files(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }
    /* End Relationships */
    
}
