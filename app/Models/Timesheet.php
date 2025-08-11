<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Timesheet extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'client_id',
        'description',
        'from',
        'to',
    ];

    protected $casts = [
        'from' => 'datetime',
        'to' => 'datetime',
    ];
    
    public function approveBy()
    {
        return $this->belongsTo(Employee::class, 'approved_by');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // Accessors & Mutators
    public function getDurationAttribute()
    {
        $from = Carbon::parse($this->from);
        $to = Carbon::parse($this->to);
        return $to->diff($from);
    }

    public function getDurationInMinutesAttribute()
    {
        $from = Carbon::parse($this->from);
        $to = Carbon::parse($this->to);
        return $to->diffInMinutes($from);
    }

    public function getDurationInHoursAttribute()
    {
        return round($this->duration_in_minutes / 60, 2);
    }

    // Scopes
    public function scopeForEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    public function scopeForClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    public function scopeInDateRange($query, $from, $to)
    {
        return $query->whereBetween('from', [$from, $to]);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('from', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('from', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereBetween('from', [
            now()->startOfMonth(),
            now()->endOfMonth()
        ]);
    }
}