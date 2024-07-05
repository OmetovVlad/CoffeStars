<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    public function index()
    {
        abort(404);
//        return Log::all();
    }

    public function store(Request $request)
    {
        abort(404);
//        return true;
    }

    public function show(string $id)
    {
        return Log::where('user_id', $id)->get();
    }
}
