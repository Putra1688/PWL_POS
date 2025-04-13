<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\KategoriModel;
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
}
