<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;
use Nette\Utils\Image;

class AdminController extends Controller
{
    public function index()
    {
        if (in_array(Auth::user()->id, explode(',', env('ADMIN_LIST')) )) {

            $logs = DB::table('logs')->where([
                ['type_mining', '=', 'mining'],
                ['created_at', '>=',  '2024-03-29'],
                ['created_at', '<=',  '2024-03-31'],
            ])->get();

//            dd($logs);

            $concurs_result = array();

            foreach ($logs as $log) {
                if (isset($concurs_result[$log->user_id])) {
                    $concurs_result[$log->user_id] += $log->sum;
                } else {
                    $concurs_result[$log->user_id] = $log->sum;
                }
            }

            arsort($concurs_result);
            return view('admin.main', ['concurs_result' => $concurs_result]);
        }

        abort(404);
    }
}
