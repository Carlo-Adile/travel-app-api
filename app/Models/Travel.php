<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\BelongsTo;

class Travel extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'start_date', 'end_date', 'user_id', 'cover_image'];

    // Specifica il nome della tabella se diverso dalla convenzione plurale
    protected $table = 'travels';

    /**
     * Get all of the steps for the Travel
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function steps(): HasMany
    {
        return $this->hasMany(Step::class)->orderBy('day', 'asc')->orderBy('time', 'asc');
    }
    /**
     * Get the user that owns the Travel
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
