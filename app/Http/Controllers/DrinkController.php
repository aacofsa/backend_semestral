<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Drink;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DrinkController extends Controller
{
    public function create(Request $req): JsonResponse
    {
        $validated = $req->validate([
            "name" => "required|string",
            "volume" => "required|string",
            "description" => "string"
        ]);

        $drink = new Drink($validated);
        $drink->save();
        return response()->json($drink);
    }

    public function findAll(): JsonResponse
    {
        $drinks = Drink::all();
        return response()->json([
            "drinks" => $drinks,
            "count" => $drinks->count()
        ]);
    }

    public function findOne($id): JsonResponse
    {
        $drink = Drink::find($id);
        if(!$drink){
            return response()->json([
                "message" => "Drink #".$id." not found",
            ],404);
        }

        return response()->json($drink);
    }

    public function patch($id, Request $req): JsonResponse
    {
        $validated = $req->validate([
            "name" => "string",
            "volume" => "string",
            "description" => "string"
        ]);

        $drink = Drink::find($id);
        if(!$drink){
            return response()->json([
                "message" => "Drink #".$id." not found",
            ],404);
        }

        $drink->fill($validated);
        $drink->save();
        return response()->json($drink);
    }
}
