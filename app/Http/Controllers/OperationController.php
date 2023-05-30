<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Operation;
use App\Models\Stock;
use App\Models\Warehouse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OperationController extends Controller
{
    public function create($warehouseId, Request $req): JsonResponse
    {
        $validated = $req->validate([
            "description" => "string",
            "shippment_order" => "string",
            "source_warehouse_id" => "integer|exists:warehouse,id",
            "items" => "required|array",
            "items.*.drink_id" => "required|integer",
            "items.*.quantity" => "required|integer"
        ]);

        $warehouse = Warehouse::find($warehouseId);
        if(!$warehouse){
            return response()->json([
                "message" => "Warehouse #".$warehouseId." not found",
            ],404);
        }
        $items = $validated["items"];
        $sourceWharehouseId = $req->source_warehouse_id;
        if($sourceWharehouseId){
            $errors = $this->reduceStockFromWarehouse($sourceWharehouseId, $items);
            if($errors){
                return response()->json([
                    "message" => "Warehouse #".$sourceWharehouseId." don't have enough stock",
                    "error" => $errors
                ],400);
            }
        }
        $this->incrementWarehouseStock($warehouseId, $items);

        $operation = new Operation();
        $operation->description = $req["description"];
        $operation->shippment_order = $req->shippment_order;
        $operation->source_warehouse_id = $req->source_warehouse_id;
        $operation->warehouse_id = $warehouseId;
        $operation->save();
        
        foreach($req->items as $item){
            $operation->items()->create($item);
        }
    
        return response()->json($operation);
    }

    public function findAll(): JsonResponse
    {
        $operations = Operation::all();
        return response()->json([
            "operations" => $operations,
            "count" => $operations->count()
        ]);
    }

    public function findOne($id): JsonResponse
    {
        $operation = Operation::with("items")->find($id);
        if(!$operation){
            return response()->json([
                "message" => "Operation #".$id." not found",
            ],404);
        }

        return response()->json($operation);
    }

    public function findByWharehouse($warehouseId): JsonResponse
    {
        $operations = Operation::with("items")->where("warehouse_id", $warehouseId)->get();

        return response()->json([
            "operations" => $operations,
            "count" => $operations->count()
        ]);
    }

    private function reduceStockFromWarehouse($warehouseId, $items){
        $errors = [];
        $stocks = [];
        foreach($items as $item){
            $drinkId = $item["drink_id"];
            $quantity = $item["quantity"];
            $stock = Stock::where("warehouse_id", $warehouseId)
                ->where("drink_id", $drinkId)
                ->first();
            if(!$stock || $stock->quantity < $quantity){
                array_push($errors, "Not enough drinks #".$drinkId);
                continue;
            }else{
                $stock->quantity -= $quantity;
                array_push($stocks,$stock);
            }
        }
        if(empty(!$errors)){
            return $errors;
        }
        foreach($stocks as $stock){
            $stock->save();
        }
    }

    private function incrementWarehouseStock($warehouseId, $items){
        foreach($items as $item){
            $drinkId = $item["drink_id"];
            $quantity = $item["quantity"];
            $stock = Stock::where("warehouse_id", $warehouseId)
                ->where("drink_id", $drinkId)
                ->first();
            if(!$stock){
                $stock = new Stock();
                $stock->warehouse_id = $warehouseId;
                $stock->drink_id = $drinkId;
                $stock->quantity = $quantity;
                error_log($stock);
            }else{
                $stock->quantity += $quantity;
            }
            $stock->save();
        }
    }
}
