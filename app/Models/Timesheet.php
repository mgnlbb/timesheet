<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'date',
        'start_time',
        'end_time',
        'activity',
        'remarks',
        'total_hours',
    ];

    public function user()
{
    return $this->belongsTo(User::class);
}
}


