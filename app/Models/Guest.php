<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Guest extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'email', 'contact_number', 'party_size'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
