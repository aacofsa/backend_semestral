<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
    use HasFactory;

    protected $table = 'warehouse';
    protected $fillable = ["name","city", "description"];
    protected $hidden = ["updated_at", "created_at"];

    public function stock(): HasMany{
        return $this->hasMany(Stock::class, "warehouse_id", "id");
    } 
}
