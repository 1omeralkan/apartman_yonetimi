<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Apartment extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'site_id' => 'integer',
        'block_id' => 'integer',
        'total_floors' => 'integer',
        'total_flats' => 'integer',
        'flats_per_floor' => 'integer',
        'has_elevator' => 'boolean',
        'has_parking' => 'boolean',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function block()
    {
        return $this->belongsTo(Block::class);
    }

    public function flats()
    {
        return $this->hasMany(Flat::class);
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
