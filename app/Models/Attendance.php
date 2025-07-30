<?php

namespace App\Models;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    //
    protected $fillable = ['employee_id', 'name', 'status', 'approved_by'];
    
    public function employee() {
        return $this->belongsTo(Employee::class);
    }
    public function approvedBy() {
        return $this->belongsTo(Employee::class , 'approved_by');
    }
}
