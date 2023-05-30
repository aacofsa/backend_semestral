<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function create(Request $req): JsonResponse
    {
        $validated = $req->validate([
            "name" => "required|string",
            "city" => "required|string",
            "description" => "string"
        ]);

        $warehouse = new Warehouse($validated);
        $warehouse->save();
        return response()->json($warehouse);
    }

    public function findAll(): JsonResponse
    {
        $warehouses = Warehouse::all();
        return response()->json([
            "warehouses" => $warehouses,
            "count" => $warehouses->count()
        ]);
    }

    public function findOne($id): JsonResponse
    {
        $warehouse = Warehouse::find($id);
        if(!$warehouse){
            return response()->json([
                "message" => "Warehouse #".$id." not found",
            ],404);
        }

        return response()->json($warehouse);
    }

    public function patch($id, Request $req): JsonResponse
    {
        $validated = $req->validate([
            "name" => "string",
            "city" => "string",
            "description" => "string"
        ]);

        $warehouse = Warehouse::find($id);
        if(!$warehouse){
            return response()->json([
                "message" => "Warehouse #".$id." not found",
            ],404);
        }

        $warehouse->fill($validated);
        $warehouse->save();
        return response()->json($warehouse);
    }
}
