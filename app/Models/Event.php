<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'customer_id',
        'package_id',
        'name',
        'event_date',
        'venue',
        'theme',
        'budget',
        'guest_count',
        'status',
        'notes'
    ];

    protected $casts = [
        'event_date' => 'date',
        'budget' => 'decimal:2',
        'guest_count' => 'integer',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
    public function vendors()
    {
        return $this->belongsToMany(Vendor::class)
            ->withTimestamps();
    }

    public function staffs()
    {
        return $this->belongsToMany(Staff::class, 'event_staff', 'event_id', 'staff_id')
            ->withPivot(['assignment_role', 'pay_rate', 'pay_status'])
            ->withTimestamps();
    }

    public function guests()
    {
        return $this->hasMany(Guest::class);
    }

    public function inclusions()
    {
        return $this->belongsToMany(Inclusion::class)
            ->withPivot(['price'])
            ->withTimestamps();
    }
}
