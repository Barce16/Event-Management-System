<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['customer_id', 'event_name', 'date', 'status'];
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
