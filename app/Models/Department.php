<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';

    protected $fillable = ['name', 'code'];

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

    public function institutions(): HasMany
    {
        return $this->hasMany(Institution::class);
    }
}