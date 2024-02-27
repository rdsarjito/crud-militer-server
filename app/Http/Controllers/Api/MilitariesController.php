<?php

namespace App\Http\Controllers\Api;

use App\Models\Militaries;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class MilitariesController extends Controller
{
    public function index() {
        $militaries = Militaries::all();
        if($militaries->count() > 0) {
            return response()->json([
                'status' => 200,
                'militaries' => $militaries
            ], 200);
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'Tidak ada data'
            ], 404);
        }
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|max:191',
            'jenis' => 'required|max:191',
            'type' => 'required|max:191',
            'kondisi' => 'required|max:191',
            'tahun_produksi' => 'required|date',
            'tanggal_perolehan' => 'required|date',
            'matra' => 'required|max:191',
            'gambar' => 'required|max:191',
        ]);


        if($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }else{
            $militaries = Militaries::create([
                'nama' => $request->nama,
                'jenis' => $request->jenis,
                'type' => $request->type,
                'kondisi' => $request->kondisi,
                'tahun_produksi' => $request->tahun_produksi,
                'tanggal_perolehan' => $request->tanggal_perolehan,
                'gambar' => $request->gambar,
                'matra' => $request->matra,
                
            ]);
        }

        if($militaries) {
            return response()->json([
                'status' => 200,
                'message' => "Data Berhasil Dibuat"
            ], 200);
        }else{
            return response()->json([
                'status' => 500,
                'message' => "Ada Kesalahan!"
            ], 500);
        }
    }

    public function show($id) {
        $militaries = Militaries::find($id);
        if($militaries) {
            return response()->json([
                'status' => 200,
                'militaries' => $militaries
            ], 200);
        }else{
            return response()->json([
                'status' => 404,
                'message' => "Tidak Ada Data Yang Ketemu!"
            ], 500);
        }
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|max:191',
            'jenis' => 'required|max:191',
            'type' => 'required|max:191',
            'kondisi' => 'required|max:191',
            'tahun_produksi' => 'required|date',
            'tanggal_perolehan' => 'required|date',
            'gambar' => 'required|max:191',
            'matra' => 'required|max:191',
            
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }else{
            $militaries = Militaries::find($id);
        }

        if($militaries) {
            $militaries->update([
                'nama' => $request->nama,
                'jenis' => $request->jenis,
                'type' => $request->type,
                'kondisi' => $request->kondisi,
                'tahun_produksi' => $request->tahun_produksi,
                'tanggal_perolehan' => $request->tanggal_perolehan,
                'matra' => $request->matra,
                'gambar' => $request->gambar,
            ]);
            return response()->json([
                'status' => 200,
                'message' => "Data Berhasil Diupdate"
            ], 200);
        }else{
            return response()->json([
                'status' => 404,
                'message' => "Tidak Ada Data Yang Ketemu!"
            ], 404);
        }
    }

    public function delete($id) {
        $militaries = Militaries::find($id);
        if($militaries) {
            $militaries->delete();
            return response()->json([
                'status' => 200,
                'message' => "Data Berhasil Dihapus"
            ], 404);
        }else{
            return response()->json([
                'status' => 404,
                'message' => "Tidak Ada Data Yang Ketemu!"
            ], 404);
        }
    }
}
