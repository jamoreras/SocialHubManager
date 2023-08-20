<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkedInCredential extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id', 'client_id', 'client_secret', 'access_token'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getByUserId($userId)
    {
        return self::where('user_id', $userId)->first();
    }
    
}
