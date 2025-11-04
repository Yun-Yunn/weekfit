<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'muscle_id', 'equipment_id'
    ];

    public function muscle()
    {
        return $this->belongsTo(Muscle::class);
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function images()
    {
        return $this->hasMany(ExerciseImage::class);
    }
}
