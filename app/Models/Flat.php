<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flat extends Model
{
    use HasFactory;

    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }

    public function residents()
    {
        return $this->hasMany(FlatResident::class);
    }

    public function dues()
    {
        return $this->hasMany(Dues::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
