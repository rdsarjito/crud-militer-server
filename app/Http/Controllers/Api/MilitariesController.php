<?php

namespace App\Http\Controllers\Api;

use App\Models\Militaries;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

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
                'errors' => $validator->errors()->all()
            ], 422);
        } else {
            $militaries = Militaries::find($id);
    
            if (!$militaries) {
                return response()->json([
                    'status' => 404,
                    'message' => "Data tidak ditemukan"
                ], 404);
            }
    
            $militaries->nama = $request->nama;
            $militaries->jenis = $request->jenis;
            $militaries->type = $request->type;
            $militaries->kondisi = $request->kondisi;
            $militaries->tahun_produksi = $request->tahun_produksi;
            $militaries->tanggal_perolehan = $request->tanggal_perolehan;
            $militaries->matra = $request->matra;
    
            if ($request->hasFile('gambar')) {
                $imageName = Str::random(32).".".$request->gambar->getClientOriginalExtension();     
                Storage::disk('public')->put($imageName, file_get_contents($request->gambar));
                $militaries->gambar = $imageName;
            }
    
            if ($militaries->save()) {
                return response()->json([
                    'status' => 200,
                    'message' => "Data berhasil diperbarui"
                ], 200);
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => "Ada kesalahan dalam memperbarui data"
                ], 500);
            }
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

    public function getImage($imageName) {
        $path = storage_path('app/public/' . $imageName);
        
        if (!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        $response = response($file, 200)->header('Content-Type', $type);

        return $response;
    }
}
