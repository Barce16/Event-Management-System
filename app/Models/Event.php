<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'customer_id',

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
}
