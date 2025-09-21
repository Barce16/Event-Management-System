<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Package extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'is_active',
        'event_styling',
        'coordination',

    ];


    public function vendors()
    {
        return $this->belongsToMany(Vendor::class)
            ->withPivot(['price_override'])
            ->withTimestamps();
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function inclusions()
    {
        return $this->belongsToMany(\App\Models\Inclusion::class, 'package_inclusion')
            ->withPivot(['notes'])
            ->withTimestamps();
    }

    protected $casts = [
        'is_active'     => 'boolean',
        'event_styling' => 'array',
    ];
}
