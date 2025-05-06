<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    protected $fillable = [
        'post_id',
        'claimer_name',
        'claimer_contact',
        'claim_type',
        'notes',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}