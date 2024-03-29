<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;

class bukuController extends Controller
{
    public function createBuku(Request $request){
        $header = $request->header('codeBased64');
        $image=$request-> foto;

        // clients send -> "data:image/jpeg;base64," as a header with Key codeBased64
        $file = base64_decode(str_replace($header, '', $image));
        $png_url = "buku-".time().".png";
        // the image will be store in public folder
        $path = public_path().'/'.$png_url;
        $success = file_put_contents($path,$file);


        try{
            Buku::create([
                'nama' => $request->nama,
                'foto' => $path,
            ]);
            return response()->json([
                'status'=>'berhasil tambah data',

            ],200);
        }catch(Exception $e){
            return response()->json([
                'error'=>$e->print,
            ],400);
        }
    }

    public function getBuku($id){
        $udangan=Buku::find($id);

        return response()->json([
            'name'=>$udangan->nama,
            'foto'=>$udangan->foto,
        ],200);

    }

    public function uploadFoto(Request $request) {
        $image = $request->file('foto');

        $png_url = $image->getClientOriginalName();
        $path = public_path() . '/fotoBuku/' . $png_url;
        Buku::create([
            'nama' => $request->nama,
            'foto' => $path
        ]);

        $foto->move(public_path().'/fotoBuku', $png_url);

        return response()->json([
            'status' => 'berhasil tambah data buku dengan foto',
            'nama' => $request->nama,
            'foto' => $path
        ], 200);
    }
}
