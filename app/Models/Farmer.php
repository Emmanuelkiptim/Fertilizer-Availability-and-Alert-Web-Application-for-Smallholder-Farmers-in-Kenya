<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Farmer extends Model
{
    //
    protected $fillable =[
        'user_id',
        'farmer_phonenumber',
        'location_latitude',
        'location_longitude',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
