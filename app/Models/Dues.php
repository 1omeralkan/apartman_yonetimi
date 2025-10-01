<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dues extends Model
{
    use HasFactory;

    protected $table = 'dues';

    public function flat()
    {
        return $this->belongsTo(Flat::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'dues_id');
    }
}
