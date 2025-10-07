<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flat extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'apartment_id' => 'integer',
        'floor_number' => 'integer',
        'flat_number' => 'integer',
        'area' => 'decimal:2',
        'monthly_dues' => 'decimal:2',
        'net_area' => 'decimal:2',
        'gross_area' => 'decimal:2',
        'has_balcony' => 'boolean',
    ];

    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }

    public function residents()
    {
        return $this->hasMany(FlatResident::class);
    }

    public function scopeWithActiveStatusSynced($query)
    {
        return $query->withCount(['residents as active_residents_count' => function($q){
            $q->where('status','active');
        }]);
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
