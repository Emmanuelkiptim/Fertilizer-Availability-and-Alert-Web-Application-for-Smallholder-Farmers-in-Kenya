<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Fertilizer extends Model
{
    //
    use HasFactory;

    protected $primaryKey = 'fertilizer_id';

    protected $fillable = [
        'agrovet_id', 'name', 'type', 'qty', 'price', 'availability'
    ];

    protected $casts = [
        'availability' => 'boolean',
        'price'        => 'decimal:2',
    ];

    public function agrovet(){
        return $this->belongsTo(Agrovet::class);
    }
    public function orders(){
        return $this->hasMany(Order::class, 'fertilizer_id');
    }
}
