<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;
use Nette\Utils\Image;

class OrderController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $balance = Auth::user()->balance;

            $items = Product::all();
            return view('order.index', ['balance' => $balance]);
        }

        abort(404);
    }

    public function create()
    {
        return view('product.create');
    }

    public function store(User $user)
    {
        if (Auth::check()) {
            $balance = Auth::user()->balance;
            $product = Product::findOrFail(request()->id);

            if ($balance >= $product->price) {
                $balance = Auth::user()->balance - $product->price;
                $address = '#' . Auth::user()->id . ' | @' . Auth::user()->username . '<br><br>';
                if ($product->name !== 'Оплата мобильной связи РФ (10р)') {
                    $address .= request()->phone . '<br>' . request()->name1 . ' ' . request()->name2 . ' ' . request()->name3 . '<br>' . request()->address;
                } else {
                    $address .= request()->phone;
                }

                User::where('id', Auth::user()->id)->update([
                    'balance' => Auth::user()->balance - $product->price,
                ]);

                Log::create([
                    'user_id' => Auth::user()->id,
                    'coin_id' => 1,
                    'type_mining' => 'order',
                    'type_source_id' => $product->id,
                    'sum' => $product->price,
                ]);

                Order::create([
                    'user_id' => Auth::user()->id,
                    'product_id' => $product->id,
                    'price' => $product->price,
                    'address' => $address,
                    'status' => 0,
                ]);

                return view('order.index', ['balance' => $balance, 'error' => false]);
            }
            return view('order.index', ['balance' => $balance, 'error' => true]);
        }

        abort(404);
    }

    public function show(Product $product)
    {
        dd($product);
    }

    public function edit(Product $product)
    {
//        dd($product->name);
        return view('product.edit', ['product' => $product]);
    }

    public function update(Product $product)
    {
        $data = request()->validate([
            'name' => 'string',
            'image' => 'string',
            'price' => 'string',
            'description' => 'string',
            'limit' => 'string',
        ]);

        $product->update($data);

        return redirect()->route('product.index');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('product.index');
    }
}
