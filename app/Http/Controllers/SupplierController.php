<?php

namespace App\Http\Controllers;


use App\Models\SupplierModel; 
use Illuminate\Http\Request; 
use Yajra\DataTables\Facades\DataTables; 
use Barryvdh\DomPDF\Facade\Pdf;

class SupplierController extends Controller
{
    public function index () {
        $breadcrumb = (object) [
            'title' => 'Daftar Supplier',
            'list' => ['Home', 'Supplier']
        ];
        
        $page = (object) [
            'title' => 'Daftar Supplier yang terdaftar dalam sistem'
        ];
        
        $activeMenu = 'supplier'; // set menu yang sedang aktif
    
        $supplier = SupplierModel::all();
    
        return view('supplier.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'supplier' => $supplier,
            'activeMenu' => $activeMenu
        ]);
        }
        public function list(Request $request)  
        {  
            $suppliers = SupplierModel::select('supplier_id', 'supplier_kode', 'supplier_nama', 'supplier_alamat');
            
            return DataTables::of($suppliers)  
                ->addIndexColumn()  // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)  
                ->addColumn('aksi', function ($supplier) {  // menambahkan kolom aksi  
                    /* $btn  = '<a href="'.url('/user/' . $user->user_id).'" class="btn btn-info btn sm">Detail</a> ';  
                    $btn .= '<a href="'.url('/user/' . $user->user_id . '/edit').'" class="btn btn warning btn-sm">Edit</a> ';  
                    $btn .= '<form class="d-inline-block" method="POST" action="'. url('/user/'.$user>user_id).'">'  
                            . csrf_field() . method_field('DELETE') .   
                            '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';*/ 
                    $btn  = '<button onclick="modalAction(\''.url('/supplier/' . $supplier->kategori_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> '; 
                    $btn .= '<button onclick="modalAction(\''.url('/supplier/' . $supplier->kategori_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> '; 
                    $btn .= '<button onclick="modalAction(\''.url('/supplier/' . $supplier->kategori_id . '/delete_ajax').'\')"  class="btn btn-danger btn-sm">Hapus</button> '; 
        
                    return $btn;  
                })  
                ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html  
                ->make(true);  
        }
}
