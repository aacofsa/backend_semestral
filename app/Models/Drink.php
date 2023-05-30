<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drink extends Model
{
    use HasFactory;

    protected $table = 'drink';
    protected $fillable = ["name","volume", "description"];
    protected $hidden = ["updated_at", "created_at"];
}
