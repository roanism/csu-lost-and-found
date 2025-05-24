<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'post_id',
        'reporter_name',
        'reporter_contact',
        'reason',
        'resolved'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}