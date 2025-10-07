<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FlatResident extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'flat_residents';
    protected $guarded = [];

    protected $casts = [
        'flat_id' => 'integer',
        'user_id' => 'integer',
        'rent_amount' => 'decimal:2',
        'move_in_date' => 'date',
        'move_out_date' => 'date',
    ];

    public function flat()
    {
        return $this->belongsTo(Flat::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
