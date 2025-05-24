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

    protected $casts = [
        'status' => 'string'
    ];

    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = (string) $value;
    }

    public function claims()
    {
        return $this->hasMany(Claim::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}