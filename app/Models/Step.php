<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    use HasFactory;
    protected $fillable = ['travel_id', 'day', 'title', 'slug', 'description', 'time', 'tag', 'lat', 'lng'];
    protected $casts = [
        'images' => 'array',
    ];
    public function travel()
    {
        return $this->belongsTo(Travel::class);
    }
}
