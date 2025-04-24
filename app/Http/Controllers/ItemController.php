<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; // panggil library validator bawaan laravel
use App\Models\Item; // kita panggil model Item

class ItemController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Item',
            'url_json' => url('/items/get_data'),
            'url' => url('/items'),
        ];
        return view('item', $data);
    }

    public function getData()
    {
        return response()->json([
            'status' => true,
            'data' => Item::all(),
            'message' => 'data berhasil ditemukan',
        ])->header('Content-Type', 'application/json')->setStatusCode(200);
    }

    public function store(Request $request)
    {
        $data = $request->only(['item_name', 'status']);

        $validator = Validator::make($data, [
            'item_name' => 'required|unique:items,item_name|min:3|max:255',
            'status' => 'required|in:aktif,tidak aktif',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ])->header('Content-Type', 'application/json')->setStatusCode(400);
        }

        Item::create($data);

        return response()->json([
            'status' => true,
            'message' => 'data berhasil disimpan'
        ])->header('Content-Type', 'application/json')->setStatusCode(200);
    }

    public function getDataById($idItem)
    {
        $item = Item::where('id', $idItem)->first();

        if (!$item) {
            return response()->json([
                'status' => false,
                'message' => 'data tidak ditemukan'
            ])->header('Content-Type', 'application/json')->setStatusCode(404);
        }

        return response()->json([
            'status' => true,
            'data' => $item,
            'message' => 'data berhasil ditemukan'
        ])->header('Content-Type', 'application/json')->setStatusCode(200);
    }

    public function updateData(Request $request, $idItem)
    {
        $item = Item::where('id', $idItem)->first();

        if (!$item) {
            return response()->json([
                'status' => false,
                'message' => 'data tidak ditemukan'
            ])->header('Content-Type', 'application/json')->setStatusCode(404);
        }

        $data = $request->only(['item_name', 'status']);

        $validator = Validator::make($data, [
            'item_name' => 'required|min:3|max:255|unique:items,item_name,' . $item->id,
            'status' => 'required|in:aktif,tidak aktif',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ])->header('Content-Type', 'application/json')->setStatusCode(400);
        }

        $item->update($data);

        return response()->json([
            'status' => true,
            'message' => 'data berhasil diubah'
        ])->header('Content-Type', 'application/json')->setStatusCode(200);
    }

    public function destroyData($idItem)
    {
        $item = Item::where('id', $idItem)->first();

        if (!$item) {
            return response()->json([
                'status' => false,
                'message' => 'data tidak ditemukan',
            ])->header('Content-Type', 'application/json')->setStatusCode(404);
        }

        $item->delete();

        return response()->json([
            'status' => true,
            'message' => 'data berhasil dihapus',
        ])->header('Content-Type', 'application/json')->setStatusCode(200);
    }
}