<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    use HasFactory;

    protected $fillable = [
        'farmer_id',
        'message',
        'is_read',
    ];

    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }
}