<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    //
    protected $fillable = [
        'address',
        'NRC'
    ];
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function attendances() {
        return $this->hasMany(Attendance::class, 'employee_id');
    }

    public function team() {
        return $this->belongsTo(Team::class);
    }

    public function timesheets() {
        return $this->hasMany(Timesheet::class);
    }
}
