<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inclusion extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'category', 'is_active'];

    public function packages()
    {
        return $this->belongsToMany(\App\Models\Package::class, 'package_inclusion')
            ->withPivot(['notes'])
            ->withTimestamps();
    }
}
