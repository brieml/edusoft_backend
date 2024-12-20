<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Specialty extends Model
{
    use HasFactory;

    protected $table = 'specialties';

    protected $fillable = ['code', 'name', 'description'];

    public function institutions(): HasMany
    {
        return $this->hasMany(Institution::class);
    }
}
