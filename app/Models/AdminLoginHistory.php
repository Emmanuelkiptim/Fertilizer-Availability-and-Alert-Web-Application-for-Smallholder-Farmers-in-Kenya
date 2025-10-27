<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminLoginHistory extends Model
{
    protected $fillable = [
        'user_id', 'session_id', 'ip_address', 'status', 'logged_in_at', 'logged_out_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
