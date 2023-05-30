<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\Warehouse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function create($warehouseId, Request $req): JsonResponse
    {
        $validated = $req->validate([
            "drink_id" => "required|integer|exists:drink,id",
            "quantity" => "required|integer",
        ]);

        $warehouse = Warehouse::find($warehouseId);
        if(!$warehouse){
            return response()->json([
                "message" => "Warehouse #".$warehouseId." not found",
            ],404);
        }

        $stock = $warehouse->stock()->create([
            "warehouse_id" => $warehouseId,
            ...$validated
        ]);
        return response()->json($stock);
    }

    public function findAll(): JsonResponse
    {
        $stocks = Stock::all();
        return response()->json([
            "stocks" => $stocks,
            "count" => $stocks->count()
        ]);
    }

    public function findOne($id): JsonResponse
    {
        $stock = Stock::find($id);
        if(!$stock){
            return response()->json([
                "message" => "Stock #".$id." not found",
            ],404);
        }

        return response()->json($stock);
    }

    public function findByWharehouse($warehouseId): JsonResponse
    {
        $stocks = Stock::where("warehouse_id", $warehouseId)->get();

        return response()->json([
            "stocks" => $stocks,
            "count" => $stocks->count()
        ]);
    }

    public function patch($id, Request $req): JsonResponse
    {
        $validated = $req->validate([
            "quantity" => "integer"
        ]);

        $stock = Stock::find($id);
        if(!$stock){
            return response()->json([
                "message" => "Stock #".$id." not found",
            ],404);
        }

        $stock->fill($validated);
        $stock->save();
        return response()->json($stock);
    }

    public function delete($id): JsonResponse
    {

        $stock = Stock::find($id);
        if(!$stock){
            return response()->json([
                "message" => "Stock #".$id." not found",
            ],404);
        }

        $stock->delete();
        return response()->json([
            "message" => "Stock #".$id." deleted successfully",
            "stock" => $stock
        ]);
    }
}
