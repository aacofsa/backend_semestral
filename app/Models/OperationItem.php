<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationItem extends Model
{
    use HasFactory;

    protected $table = 'operation_item';
    protected $fillable = ["operation_id", "drink_id","quantity"];
    protected $hidden = ["updated_at", "created_at"];
}
