<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlayerResource;
use App\Models\Player;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort(404);
//        return PlayerResource::collection(Player::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $created_player = Player::create($request -> all());
        return new PlayerResource($created_player);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Player::findOrFail($id)::whereNull('invited')->with('invitedUser')->get();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Player $player)
    {
        $player->update($request -> all());

        return new PlayerResource($player);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Player $player)
    {
        $player->delete();

        return response(null, 204);
    }
}
