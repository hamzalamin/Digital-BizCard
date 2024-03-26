<?php

namespace App\Http\Controllers;

use App\Models\card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class cardController extends Controller
{

    public function getAllBusinessCards()
    {
        $user = Auth::user();
        $businessCards = $user->businessCards()->get();

        return response()->json(['business_cards' => $businessCards]);
    }


    public function createBusinessCard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'company' => 'required|string',
            'title' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();

        $businessCard = new card([
            'name' => $request->name,
            'company' => $request->company,
            'title' => $request->title,
            'user_id' => $user->id,
        ]);
        $businessCard->save();


        return response()->json(['message' => 'Business card created successfully', 'card' => $businessCard]);
    }



    public function updateBusinessCard(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'company' => 'string',
            'title' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();

        $businessCard = card::find($id);

        if (!$businessCard) {
            return response()->json(['message' => 'Business card not found'], 404);
        }

        // if ($user->id !== $businessCard->user_id) {
        //     return response()->json(['message' => 'Unauthorized'], 403);
        // }

        // police service

        if ($request->user()->cannot('create', card::class)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $businessCard->update($request->all());

        return response()->json(['message' => 'Business card updated successfully', 'card' => $businessCard]);
    }





    public function deleteBusinessCard($id)
    {
        $user = Auth::user();

        $businessCard = card::find($id);


        if (!$businessCard) {
            return response()->json(['message' => 'Business card not found'], 404);
        }


        // police service
        if ($user->cannot('delete', $businessCard)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }



        $businessCard->delete();

        return response()->json(['message' => 'Business card deleted successfully']);
    }
}
