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
}
