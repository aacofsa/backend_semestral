<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Operation extends Model
{
    use HasFactory;

    protected $table = 'operation';
    protected $fillable = [
        "source_warehouse_id",
        "warehouse_id",
        "description",
        "shippment_order"
    ];
    protected $visible = [
        "id",
        "source_warehouse_id",
        "warehouse_id",
        "description",
        "shippment_order",
        "items"
    ];
    protected $hidden = ["updated_at", "created_at"];
    
    public function items(): HasMany{
        return $this->hasMany(OperationItem::class, "operation_id", "id");
    } 
}
