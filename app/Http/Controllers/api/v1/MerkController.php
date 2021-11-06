<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Merk;
use App\Model\Produk;
use DB;
use App\Utils\FuncValidation;
use App\Utils\FuncUUID;

class MerkController extends Controller
{
    use FuncValidation, FuncUUID;

    
    public function show(Request $request){
        try{

            $page = ($request->has('page')) ? $request->page : '';
            $limit = ($request->has('limit')) ? $request->limit : '10';

            $page = ($page > 1) ? ($page * $limit) - $limit : 0;

            $merk = Merk::orderBy('created_at','DESC')->offset($page)->limit($limit)->get();
            $merkAll = Merk::get();

            $total = $merkAll->count();
            $pages = ceil($total/$limit); 


            return response()->json([
                'code' => 200,
                'total_page' => $pages,
                'total' => $total,
                'page' => $page,
                'limit' => $limit,
                'data' => $merk
            ],200);

        }catch(Exception $e){
            
            // save to Log


            // return json error
            return response()->json([
                'code' => 500,
                'message' => 'Terjadi kesalahan pada server.'
            ],500);
        }
    }

    public function search(Request $request){
        try{

            $search = ($request->has('search')) ? $request->search : '';
            if(!empty($search)){
                $merk = Merk::select('nama_merk','uuid')->where('nama_merk','like','%'.$search.'%')->orderBy('created_at','DESC')->get();
            }else{
                $merk = Merk::select('nama_merk','uuid')->orderBy('created_at','DESC')->get();
            }


            return response()->json([
                'code' => 200,
                'data' => $merk
            ],200);

        }catch(Exception $e){
            
            // save to Log


            // return json error
            return response()->json([
                'code' => 500,
                'message' => 'Terjadi kesalahan pada server.'
            ],500);
        }
    }

    public function detail($uuid){
        try{
            $merk = Merk::findByUUID($uuid);


            return response()->json([
                'code' => 200,
                'data' => $merk
            ],200);

        }catch(Exception $e){
            
            // save to Log


            // return json error
            return response()->json([
                'code' => 500,
                'message' => 'Terjadi kesalahan pada server.'
            ],500);
        }
    }

    public function add(Request $request){

        $rules = [
            'nama_merk' => 'required|unique:merk',
            'url_merk' => 'required|unique:merk',
        ];

        $errors = $this->validation($request->all(), $rules);
        if($errors != null){
            return response()->json([
                'code' => 422,
                'errors' => $errors
            ],422);
        }
        
        DB::beginTransaction();
        try{

            $data['nama_merk'] = $request->nama_merk;
            $data['url_merk'] = $request->url_merk;
            $data['created_at'] = date("Y-m-d H:i:s");
            $data['uuid'] = $this->uuid();
            $data['id'] = $this->uuid_short();
            
            $create = Merk::firstOrNew($data);
            DB::commit();
            if(!$create->save()){
                return response()->json([
                    'code' => 400,
                    'message' => 'Gagal menyimpan data.'
                ],400);
            }

            return response()->json([
                'code' => 201,
                'message' => 'Berhasil menambahkan data.',
                'data' => array(
                    'nama_merk' => $request->nama_merk,
                    'url_merk' => $request->url_merk,
                )
            ],201);

        }catch(Exception $e){
            DB::rollback();
            // save to Log


            // return json error
            return response()->json([
                'code' => 500,
                'message' => 'Terjadi kesalahan pada server.'
            ],500);
        }
    }

    public function edit(Request $request,$uuid){

        $merk = Merk::findByUUID($uuid);
        
        $uniqueUrl = ($merk->url_merk == $request->url_merk) ? '' : '|unique:merk';
        $uniqueNama = ($merk->nama_merk == $request->nama_merk) ? '' : '|unique:merk';


        $rules = [
            'nama_merk' => 'required'.$uniqueNama,
            'url_merk' => 'required'.$uniqueUrl,
        ];

        $errors = $this->validation($request->all(), $rules);
        if($errors != null){
            return response()->json([
                'code' => 422,
                'errors' => $errors
            ],422);
        }
        
        DB::beginTransaction();
        try{

            $data['nama_merk'] = $request->nama_merk;
            $data['url_merk'] = $request->url_merk;
            $data['updated_at'] = date("Y-m-d H:i:s");
            
            $update = $merk->fill($data);
            DB::commit();
            if($update->isClean()){
                return response()->json([
                    'code' => 400,
                    'message' => 'Data tidak ada yang berubah.'
                ],400);
            }

            if(!$update->save()){
                return response()->json([
                    'code' => 400,
                    'message' => 'Gagal mengubah data.'
                ],400);
            }

            return response()->json([
                'code' => 200,
                'message' => 'Berhasil mengubah data.'
            ],200);

        }catch(Exception $e){
            DB::rollback();
            // save to Log


            // return json error
            return response()->json([
                'code' => 500,
                'message' => 'Terjadi kesalahan pada server.'
            ],500);
        }
    }

    public function delete($uuid){

        try{

            $merk = Merk::findByUUID($uuid);
            $produk = Produk::where('merk_id',$kategori->id)->get();

            if($produk->count() > 0){
                return response()->json([
                    'code' => 400,
                    'message' => 'Gagal menghapus data, terdapat data produk yang terhubung.',
                ],400);
            }

            $delete          = $merk->delete();

            if(!$delete){
                return response()->json([
                    'code' => 400,
                    'message' => 'Gagal menghapus data.',
                ],400);
            }

            return response()->json([
                'code' => 200,
                'message' => 'Berhasil menghapus data.',
            ],200);

        }catch(Exception $e){
             // save to Log

             // return json error
             return response()->json([
                'code' => 500,
                'message' => 'Terjadi kesalahan pada server.'
            ],500);
        }
    }
}
