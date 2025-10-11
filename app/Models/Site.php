<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Site extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'total_blocks' => 'integer',
        'total_apartments' => 'integer',
        'total_floors' => 'integer',
        'apartments_per_block' => 'integer',
        'floors_per_apartment' => 'integer',
        'flats_per_floor' => 'integer',
    ];

    public function blocks()
    {
        return $this->hasMany(Block::class);
    }

    public function apartments()
    {
        return $this->hasMany(Apartment::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'active' => 'Aktif',
            'inactive' => 'Pasif',
            'maintenance' => 'Bakımda',
            default => (string) $this->status,
        };
    }
}
