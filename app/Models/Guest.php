<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guest extends Model
{
    protected $primaryKey = 'guest_id';

    protected $fillable = ['guest_name', 'email', 'phone', 'gender', 'id_number', 'address'];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'guest_id', 'guest_id');
    }
}
