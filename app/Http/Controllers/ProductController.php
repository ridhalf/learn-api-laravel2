<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Routing\Controller as BaseController;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::select('id', 'kategori', 'quantity', 'nama')->get();
        if ($products == null) {
            return $this->respondError('Product belum ada', 201);
        }
        return $this->respondSuccess($products);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $nama = trim($request->nama);
        $kategori = trim($request->kategori);
        $quantity = trim($request->quantity);

        $validate = Validator::make($request->all(), [
            'nama' => 'required|min:1|max:100',
            'kategori' => 'required',
            'quantity' => 'required|numeric'
        ], [
            'required' => ':attribute belum diisi',
            'min' => ':attribute terlalu pendek',
            'max' => ':attribute terlalu panjang',
            'numeric' => ':attribute harus diisi angka',
        ]);
        $validate->setAttributeNames([
            'nama' => 'Nama',
            'kategori' => 'Kategori',
            'quantity' => 'Kuantitas'
        ]);
        if ($validate->fails()) {
            return $this->validationErrors($validate);
        }

        $product = new Product;
        $product->nama = $request->nama;
        $product->kategori = $request->kategori;
        $product->quantity = $request->quantity;
        $save = $product->save();
        if ($save == null) :
            return $this->respondError('Gagal Menyimpan Data Peserta Baru', 201);
        else :
            return $this->respondSuccess('Disimpan');
        endif;
    }
}
