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
    public function detail($id)
    {
        $products = Product::select('id', 'kategori', 'quantity', 'nama')->where('id', $id)->first();
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

        $validate = $this->_validate($request);

        if ($validate->fails()) {
            return $this->validationErrors($validate);
        }

        $product = new Product;
        $product->nama = $request->nama;
        $product->kategori = $request->kategori;
        $product->quantity = $request->quantity;
        $save = $product->save();
        if ($save == null) :
            return $this->respondError('Gagal Menyimpan Data Produk', 201);
        else :
            return $this->respondSuccess('Disimpan');
        endif;
    }
    public function update(Request $request, $id)
    {
        $validate = $this->_validate($request);
        if ($validate->fails()) {
            return $this->validationErrors($validate);
        }
        $product = Product::findOrFail($id);
        $product->nama = $request->nama;
        $product->kategori = $request->kategori;
        $product->quantity = $request->quantity;
        $save = $product->save();
        if ($save == null) :
            return $this->respondError('Gagal Mengubah Data Produk', 201);
        else :
            return $this->respondSuccess('Disimpan');
        endif;
    }
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $result = $product->delete();
        if ($result == null) :
            return $this->respondError('Gagal Menghapus Data Produk', 201);
        else :
            return $this->respondSuccess('Dihapus');
        endif;
    }

    protected function _validate($request)
    {
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
        return $validate;
    }
}
