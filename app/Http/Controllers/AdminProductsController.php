<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;
use Nette\Utils\Image;

class AdminProductsController extends Controller
{
    public function index()
    {
        if (in_array(Auth::user()->id, explode(',', env('ADMIN_LIST')) )) {
            $items = Product::all();
            return view('admin.product.index', ['items' => $items]);
        }

        abort(404);
    }

    public function create()
    {
        if (in_array(Auth::user()->id, explode(',', env('ADMIN_LIST')) )) {
            return view('admin.product.create');
        }
        abort(404);
    }

    public function store()
    {
        if (in_array(Auth::user()->id, explode(',', env('ADMIN_LIST')) )) {
            $data = request()->validate([
                'name' => 'string',
                'image' => 'string',
                'price' => 'string',
                'description' => 'string',
                'limit' => 'string',
            ]);

            Product::create($data);
            return redirect()->route('admin.product.index');
        }
        abort(404);
    }

    public function show(Product $product)
    {
        if (in_array(Auth::user()->id, explode(',', env('ADMIN_LIST')) )) {
            dd($product);
        }
        abort(404);
    }

    public function edit(Product $product)
    {
        if (in_array(Auth::user()->id, explode(',', env('ADMIN_LIST')) )) {
            return view('admin.product.edit', ['product' => $product]);
        }
        abort(404);
    }

    public function update(Product $product)
    {
        if (in_array(Auth::user()->id, explode(',', env('ADMIN_LIST')) )) {
            $data = request()->validate([
                'name' => 'string',
                'image' => 'string',
                'price' => 'string',
                'description' => 'string',
                'limit' => 'string',
            ]);

            $product->update($data);

            return redirect()->route('admin.product.index');
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
