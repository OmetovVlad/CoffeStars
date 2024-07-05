<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;
use Nette\Utils\Image;

class AdminOrdersController extends Controller
{
    public function index()
    {
        if (in_array(Auth::user()->id, explode(',', env('ADMIN_LIST')) )) {
            $items = Order::all()->where('status', 0);
            $products = Product::all();

            return view('admin.order.index', ['items' => $items, 'products' => $products]);
        }

        abort(404);
    }

    public function update(Order $order)
    {
        if (in_array(Auth::user()->id, explode(',', env('ADMIN_LIST')) )) {
            $order->update([
                'status' => 1
            ]);

            return redirect()->route('admin.order.index');
        }

        abort(404);
    }

    public function destroy(Product $product)
    {
        if (in_array(Auth::user()->id, explode(',', env('ADMIN_LIST')) )) {
            $product->delete();
            return redirect()->route('admin.product.index');
        }

        abort(404);
    }
}
