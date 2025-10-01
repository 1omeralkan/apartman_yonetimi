<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apartment extends Model
{
    use HasFactory;

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function block()
    {
        return $this->belongsTo(Block::class);
    }

    public function floors()
    {
        return $this->hasMany(Floor::class);
    }

    public function flats()
    {
        return $this->hasManyThrough(Flat::class, Floor::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }
}
