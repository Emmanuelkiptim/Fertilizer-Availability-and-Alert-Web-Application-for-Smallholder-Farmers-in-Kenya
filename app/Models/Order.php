<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $primaryKey = 'order_id';
    protected $fillable = [
        'farmer_id',
        'fertilizer_id',
        'agrovet_id',
        'quantity',
        'total_price',
        'status',
    ];
    public function farmer()
    {
        return $this->belongsTo(Farmer::class, 'farmer_id');
    }

    public function fertilizer()
    {
        return $this->belongsTo(Fertilizer::class, 'fertilizer_id');
    }

    public function agrovet()
    {
        return $this->belongsTo(Agrovet::class, 'agrovet_id');
    }
}
