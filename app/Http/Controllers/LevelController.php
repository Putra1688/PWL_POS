<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LevelModel;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class LevelController extends Controller
{
    public function index() {
        // ------------ DB FACADE & QUERY BUILDER ------------
        // DB::insert('insert into m_level(level_kode, level_nama, created_at) values(?, ?, ?)', ['cus', 'Pelanggan', now()]);
        // return 'Insert data baru berhasil';

        // $row =DB::update('update m-level set level_nama = ? where level_kode=?', ['Custmoer', 'cus']);
        // return 'update data berhasil. Jumlah data yang diupdate: ' .$row. ' baris';
        
        // $row =DB::delete('delete fro m_level where level_kode = ?', ['cus']);
        // return 'delete data berhasil. Jumlah data yang dihapus: ' .$row. ' baris';

        // $data = DB::select('select * from m_level');
        // return view('level', ['data'=> $data]);
        // ------------ ------------ ------------ ------------ ------------
        $breadcrumb = (object) [
            'title' => 'Daftar Level',
            'list' => ['Home', 'Level']
        ];
        
        $page = (object) [
            'title' => 'Daftar level pengguna yang terdaftar dalam sistem'
        ];
        
        $activeMenu = 'level'; // set menu yang sedang aktif

        $level = LevelModel::all();

        return view('level.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'level' => $level,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list(Request $request)  
    {  
        $levels = LevelModel::select('level_id', 'level_kode', 'level_nama');
        
        return DataTables::of($levels)  
            ->addIndexColumn()  // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)  
            ->addColumn('aksi', function ($level) {  // menambahkan kolom aksi  
                /* $btn  = '<a href="'.url('/user/' . $user->user_id).'" class="btn btn-info btn sm">Detail</a> ';  
                $btn .= '<a href="'.url('/user/' . $user->user_id . '/edit').'" class="btn btn warning btn-sm">Edit</a> ';  
                $btn .= '<form class="d-inline-block" method="POST" action="'. url('/user/'.$user>user_id).'">'  
                        . csrf_field() . method_field('DELETE') .   
                        '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';*/ 
                $btn  = '<button onclick="modalAction(\''.url('/level/' . $level->level_id . '/show_level').'\')" class="btn btn-info btn-sm">Detail</button> '; 
                $btn .= '<button onclick="modalAction(\''.url('/level/' . $level->level_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> '; 
                $btn .= '<button onclick="modalAction(\''.url('/level/' . $level->level_id . '/delete_level').'\')"  class="btn btn-danger btn-sm">Hapus</button> '; 
    
                return $btn;  
            })  
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html  
            ->make(true);  
    }

    public function create_ajax() {
        $level = levelModel::select('level_id', 'level_nama')->get();
        return view('level.create_ajax')
        ->with('level', $level);
    }

    public function store_ajax (Request $request) {
        // cek apakah request berupa ajax
        if($request->ajax() || $request->wantsJson()){
            $rules = [

                'level_kode' => 'required|string|min:3|unique:m_level,level_nama',
                'level_nama' => 'required|string|max:100',
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
    
            LevelModel::create([
                
                'level_kode' => $request->level_kode,
                'level_nama'     => $request->level_nama
            ]);
    
            return response()->json([
                'status' => true,
                'message' => 'Data level berhasil disimpan'
            ]);
        }
        redirect('/');
    }

    public function show_ajax(string $id)
    {
        $level = LevelModel::find($id);
        return view('level.show_ajax', compact('level'));
    }

    // Menampilkan halaman form edit user ajax
    public function edit_ajax(string $id)
    {
        $level = LevelModel::find($id);


        return view('level.edit_ajax', ['level' => $level]);
    }

    public function update_ajax(Request $request, $id){ 
        // cek apakah request dari ajax 
        if ($request->ajax() || $request->wantsJson()) { 
            $rules = [ 
                'level_id' => 'required|integer', 
                'level_kode' => 'required|max:20|unique:m_level,level_nama,'.$id.',level_id', 
                'level_nama'     => 'required|max:100'
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
 
        $check = LevelModel::find($id); 
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
        $level = LevelModel::find($id);
        return view('level.confirm_ajax',['level' =>$level]);
    }

    public function delete_ajax(Request $request, $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $level = LevelModel::find($id);
            if ($level) {
                $level->delete();
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
