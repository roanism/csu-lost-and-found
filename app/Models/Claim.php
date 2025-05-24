<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    use HasFactory;

    protected $fillable = [
        'claimer_name',
        'claimer_email',
        'claimer_phone',
        'claim_reason',
        'post_id'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}