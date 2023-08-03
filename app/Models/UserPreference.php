<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'email_notification',
        'sms_notification',
        'push_notification',
        'email_marketing',
    ];

    protected $casts = [
        'email_notification' => 'boolean',
        'sms_notification' => 'boolean',
        'push_notification' => 'boolean',
        'email_marketing' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
