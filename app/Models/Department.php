<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'color',
    ];

    /* Relationships */

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'department_id');
    }
    
    /* End Relationships */
}
