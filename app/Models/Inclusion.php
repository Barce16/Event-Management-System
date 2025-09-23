<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inclusion extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'category',
        'price',
        'is_active',
        'contact_person',
        'contact_email',
        'contact_phone',
        'notes'
    ];

    public function packages()
    {
        return $this->belongsToMany(Package::class, 'package_inclusion')
            ->withPivot('notes')
            ->withTimestamps();
    }
    public function events()
    {
        return $this->belongsToMany(Event::class)
            ->withPivot(['price'])
            ->withTimestamps();
    }
}
