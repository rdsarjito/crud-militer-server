<?php

namespace App\Http\Controllers\Api;

use App\Models\Militaries;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
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
            'gambar' => 'required|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);


        if($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }else{
            $imageName = Str::random(32).".".$request->gambar->getClientOriginalExtension();     
            $militaries = Militaries::create([
                'nama' => $request->nama,
                'jenis' => $request->jenis,
                'type' => $request->type,
                'kondisi' => $request->kondisi,
                'tahun_produksi' => $request->tahun_produksi,
                'tanggal_perolehan' => $request->tanggal_perolehan,
                'matra' => $request->matra,
                'gambar' => $imageName,
            ]);
            Storage::disk('public')->put($imageName, file_get_contents($request->gambar));
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
        $military = Militaries::findOrFail($id);
    
        $imageData = $request->gambar;
    
        if($imageData) {
            $exploded = explode(',', $imageData);
    
            $extension = explode('/', mime_content_type($imageData))[1];
    
            $imageName = Str::random(32) . '.' . $extension;
    
            Storage::disk('public')->put($imageName, base64_decode($exploded[1]));
    
            if ($military->gambar) {
                Storage::disk('public')->delete($military->gambar);
            }
    
            $military->gambar = $imageName;
        }
    
        $military->nama = $request->nama;
        $military->jenis = $request->jenis;
        $military->type = $request->type;
        $military->kondisi = $request->kondisi;
        $military->tahun_produksi = $request->tahun_produksi;
        $military->tanggal_perolehan = $request->tanggal_perolehan;
        $military->matra = $request->matra;
    
        $military->save();
    
        return response()->json([
            'status' => 200,
            'message' => "Data Berhasil Diperbarui"
        ], 200);
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
