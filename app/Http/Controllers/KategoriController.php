<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\KategoriModel;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class KategoriController extends Controller
{
    public function index() {
    // ------------ DB FACADE & QUERY BUILDER ------------
    /* $data = [
            'kategori_kode' => 'SNK',
            'kategori_nama' => 'Snack/Makanan Ringan',
            'created_at' => now()
        ];

        DB::table('m_kategori') -> insert($data);
        return 'Insert data baru berhasil'; */

        // $row = DB::table('m_kategori') -> where ('kategori_kode', 'SNK') -> update(['kategori_nama' => 'Camilan']);
        // return 'Update data berhasil. Jumlah data yang diupdate: ' .$row. ' baris';
       
        // $row = DB::table('m_kategori') -> where ('kategori_kode', 'SNK') -> delete(['kategori_nama' => 'Camilan']);
        // return 'Delete data berhasil. Jumlah data yang dihapus: ' .$row. ' baris';
        
        // $data = DB::table('m_kategori') -> get();
        // return view('kategori', ['data' => $data]);
    // ------------ ------------ ------------ ------------ ------------

    $breadcrumb = (object) [
        'title' => 'Daftar kategori',
        'list' => ['Home', 'kategori']
    ];
    
    $page = (object) [
        'title' => 'Daftar kategori pengguna yang terdaftar dalam sistem'
    ];
    
    $activeMenu = 'kategori'; // set menu yang sedang aktif

    $kategori = KategoriModel::all();

    return view('kategori.index', [
        'breadcrumb' => $breadcrumb,
        'page' => $page,
        'kategori' => $kategori,
        'activeMenu' => $activeMenu
    ]);
    }
    public function list(Request $request)  
    {  
        $kategoris = KategoriModel::select('kategori_id', 'kategori_kode', 'kategori_nama');
        
        return DataTables::of($kategoris)  
            ->addIndexColumn()  // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)  
            ->addColumn('aksi', function ($kategori) {  // menambahkan kolom aksi  
                /* $btn  = '<a href="'.url('/user/' . $user->user_id).'" class="btn btn-info btn sm">Detail</a> ';  
                $btn .= '<a href="'.url('/user/' . $user->user_id . '/edit').'" class="btn btn warning btn-sm">Edit</a> ';  
                $btn .= '<form class="d-inline-block" method="POST" action="'. url('/user/'.$user>user_id).'">'  
                        . csrf_field() . method_field('DELETE') .   
                        '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';*/ 
                $btn  = '<button onclick="modalAction(\''.url('/kategori/' . $kategori->kategori_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> '; 
                $btn .= '<button onclick="modalAction(\''.url('/kategori/' . $kategori->kategori_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> '; 
                $btn .= '<button onclick="modalAction(\''.url('/kategori/' . $kategori->kategori_id . '/delete_ajax').'\')"  class="btn btn-danger btn-sm">Hapus</button> '; 
    
                return $btn;  
            })  
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html  
            ->make(true);  
    }

    public function create_ajax() {
        $kategori = KategoriModel::select('kategori_id', 'kategori_kode', 'kategori_nama')->get();
        return view('kategori.create_ajax')
        ->with('kategori', $kategori);
    }

    public function store_ajax (Request $request) {
        // cek apakah request berupa ajax
        if($request->ajax() || $request->wantsJson()){
            $rules = [

                'kategori_kode' => 'required|string|min:3|unique:m_user,username',
                'kategori_nama' => 'required|string|max:100',
            ];
    
            // use Illuminate\Support\Facades\Validator;
            $validator = Validator::make($request->all(), $rules);
    
            if($validator->fails()) {
                return response()->json([
                    'status' => false, // response status, false: error/gagal, true: berhasil
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(), // pesan error validasi
                ]);
            }
    
            KategoriModel::create([
                'kategori_id' => $request->kategori_id,
                'kategori_kode' => $request->kategori_kode,
                'kategori_nama'     => $request->kategori_nama
            ]);
    
            return response()->json([
                'status' => true,
                'message' => 'Data user berhasil disimpan'
            ]);
        }
        redirect('/');
    }

    public function show_ajax(string $id)
    {
        $kategori = KategoriModel::find($id);
        return view('kategori.show_ajax', compact('kategori'));
    }

    // Menampilkan halaman form edit user ajax
    public function edit_ajax(string $id)
    {
        $kategori = KategoriModel::find($id);


        return view('kategori.edit_ajax', ['kategori' => $kategori]);
    }

    public function update_ajax(Request $request, $id){ 
        // cek apakah request dari ajax 
        if ($request->ajax() || $request->wantsJson()) { 
            $rules = [ 
                'kategori_id' => 'required|integer', 
                'kategori_kode' => 'required|max:20|unique:m_kategori,kategori_nama,'.$id.',kategori_id', 
                'kategori_nama'     => 'required|max:100'
            ]; 
        // use Illuminate\Support\Facades\Validator; 
        $validator = Validator::make($request->all(), $rules); 
 
        if ($validator->fails()) { 
            return response()->json([ 
                'status'   => false,    // respon json, true: berhasil, false: gagal 
                'message'  => 'Validasi gagal.', 
                'msgField' => $validator->errors()  // menunjukkan field mana yang error 
            ]); 
        } 
 
        $check = KategoriModel::find($id); 
        if ($check) { 
            if(!$request->filled('password') ){ // jika password tidak diisi, maka hapus dari request 
                $request->request->remove('password'); 
            } 
             
            $check->update($request->all()); 
            return response()->json([ 
                'status'  => true, 
                'message' => 'Data berhasil diupdate' 
            ]); 
        } else{ 
            return response()->json([ 
                'status'  => false, 
                'message' => 'Data tidak ditemukan' 
            ]); 
        } 
    } 
    return redirect('/'); 
}     

    public function confirm_ajax(string $id) {
        $kategori = KategoriModel::find($id);
        return view('kategori.confirm_ajax',['kategori' =>$kategori]);
    }

    public function delete_ajax(Request $request, $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $kategori = KategoriModel::find($id);
            if ($kategori) {
                $kategori->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

}
