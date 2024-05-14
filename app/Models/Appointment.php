<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'healthcare_professional_id','appointment_start_time','appointment_end_time','status'];
    protected $casts = [
        'appointment_start_time' => 'datetime',
        'appointment_end_time' => 'datetime',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function healthcareProfessional()
    {
        return $this->belongsTo(HealthcareProfessional::class);
    }
}
