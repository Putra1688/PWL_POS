<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use App\Models\PenjualanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;

class PenjualanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Transaksi',
            'list' => ['Home', 'Transaksi']
        ];
        
        $page = (object) [
            'title' => 'Daftar transaksi yang terdaftar dalam sistem'
        ];
        
        $activeMenu = 'penjualan'; // set menu yang sedang aktif
        
        $user = UserModel::all();

        return view('penjualan.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'user' => $user,
            'activeMenu' => $activeMenu
        ]);   
    }

    public function list(Request $request)  
    {  
        $penjualan = PenjualanModel::select('penjualan_id', 'user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal')  
                    ->with('user'); 
        
        // Filter data user berdasarkan level_id 
        if ($request->user_id){ 
            $penjualan->where('level_id',$request->user_id); 
        } 
    
        return DataTables::of($penjualan)  
            ->addIndexColumn()  // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)  
            ->addColumn('aksi', function ($penjualan) {  // menambahkan kolom aksi  
                /* $btn  = '<a href="'.url('/user/' . $user->user_id).'" class="btn btn-info btn sm">Detail</a> ';  
                $btn .= '<a href="'.url('/user/' . $user->user_id . '/edit').'" class="btn btn warning btn-sm">Edit</a> ';  
                $btn .= '<form class="d-inline-block" method="POST" action="'. url('/user/'.$user>user_id).'">'  
                        . csrf_field() . method_field('DELETE') .   
                        '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';*/ 
                $btn  = '<button onclick="modalAction(\''.url('/penjualan/' . $penjualan->penjualan_id. '/show_detail').'\')" class="btn btn-info btn-sm">Detail</button> '; 
    
                return $btn;  
            })  
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html  
            ->make(true);  
    }

    public function show_ajax(string $id)
    {
        $penjualan = PenjualanModel::with('user')->find($id);

        return view('penjualan.detail', compact('penjualan'));
    }
}
