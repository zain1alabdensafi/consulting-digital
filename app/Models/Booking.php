<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $table = "booking";
    protected $fillable = ["sission_time"];
    public $timestamps = false;
    public function expert()
    {
        return $this->belongsToMany(Expert::class, "Booking");
    }
}
