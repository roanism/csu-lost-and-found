<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'type',
        'item_name',
        'description',
        'category',
        'location',
        'contact_name',
        'contact_info',
        'image_path',
        'reference_number',
        'status',
    ];

    public function claims()
    {
        return $this->hasMany(Claim::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}