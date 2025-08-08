<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'industry_type',
        'code',
        'email',
        'phone',
        'address',
        'team_id',
        'is_active',
    ];

    /**
     * Get the timesheets for the client.
     */
    public function timesheets()
    {
        return $this->hasMany(Timesheet::class);
    }
    public function team() {
        return $this->belongsTo(Team::class);
    }
    /**
     * Get the client's display name (code + name)
     */
    public function getDisplayNameAttribute()
    {
        return "{$this->code} - {$this->name}";
    }
}