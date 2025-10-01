<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Block extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'site_id' => 'integer',
        'total_apartments' => 'integer',
        'total_floors' => 'integer',
        'flats_per_floor' => 'integer',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function apartments()
    {
        return $this->hasMany(Apartment::class);
    }
}
