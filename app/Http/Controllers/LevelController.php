<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LevelModel;
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
                $btn  = '<button onclick="modalAction(\''.url('/level/' . $level->level_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> '; 
                $btn .= '<button onclick="modalAction(\''.url('/level/' . $level->level_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> '; 
                $btn .= '<button onclick="modalAction(\''.url('/level/' . $level->level_id . '/delete_ajax').'\')"  class="btn btn-danger btn-sm">Hapus</button> '; 
    
                return $btn;  
            })  
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html  
            ->make(true);  
    }

    public function create() {
        $breadcrumb = (object) [
            'title' => 'Tambah level',
            'list' => ['Home', 'Level', 'Tambah']
        ];
        
        $page = (object) [
            'title' => 'Tambah level baru'
        ];

        $level = LevelModel::all();
        $activeMenu ='level';

        return view('level.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    // Menyimpan data user baru
    public function store(Request $request)
    {
        $request->validate([
            // username harus diisi, berupa string, minimal 3 karakter, dan bernilai unik di tabel m_level kolom level_kode
            'level_kode' => 'required|string|min:3|unique:m_level,level_kode',
            'level_nama'     => 'required|string|max:100', // nama harus diisi, berupa string, dan maksimal 100 karakter
        ]);

        LevelModel::create([
            'level_kode' => $request->level_kode,
            'level_nama' => $request->level_nama,
        ]);

        return redirect('/level')->with('success', 'Data Level berhasil disimpan');
    }

    

}
