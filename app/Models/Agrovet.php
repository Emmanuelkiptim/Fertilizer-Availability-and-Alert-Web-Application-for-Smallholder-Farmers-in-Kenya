<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agrovet extends Model
{
    //
    protected $fillable =[
        'user_id',
        'shopname',
        'agrovet_phonenumber',
        'location_latitude',
        'location_longitude',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function fertilizers(){
        return $this->hasMany(Fertilizer::class, 'agrovet_id', 'id');
    }
}
