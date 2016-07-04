<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class bundle extends Model
{
    protected $fillable = ['title', 'description'];

    public function books()
    {
        return $this->belongsToMany(\App\Book::class);
    }
}
