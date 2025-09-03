<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vendor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'contact_person', 'price', 'category', 'email', 'phone', 'notes', 'is_active'];

    public function packages()
    {
        return $this->belongsToMany(Package::class)
            ->withPivot(['included_price', 'notes'])
            ->withTimestamps();
    }

    public function events()
    {
        return $this->belongsToMany(Event::class)
            ->withTimestamps();
    }
}
