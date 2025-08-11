<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    protected $guarded = [];

    public function roomTypes()
    {
        return $this->hasMany(RoomType::class);
    }
}
