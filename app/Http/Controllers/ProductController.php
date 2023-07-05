<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Laravel\Lumen\Routing\Controller as BaseController;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::select('id', 'kategori', 'nama')->get();
        if ($products == null) {
            return $this->respondError('Product belum ada', 201);
        }
        return $this->respondSuccess($products);
    }
}
