<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\BarangModel;
use Yajra\DataTables\Facades\DataTables;

class BarangController extends Controller
{
    public function index () {
        $breadcrumb = (object) [
            'title' => 'Data Barang',
            'list' => ['Home', 'Data Barang']
        ];
        
        $page = (object) [
            'title' => 'Data Barang yang terdaftar dalam sistem'
        ];
        
        $activeMenu = 'barang'; // set menu yang sedang aktif

        $barang = BarangModel::all();

        return view('barang.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'barang' => $barang,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list(Request $request)
    {
        $barang = BarangModel::with('kategori');

        return DataTables::of($barang)
            ->addIndexColumn()
            ->addColumn('kategori_nama', function ($b) {
                return $b->kategori->kategori_nama ?? '-';
            })
            ->addColumn('aksi', function ($b) {
                $btn  = '<button onclick="modalAction(\''.url('/barang/' . $b->barang_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/barang/' . $b->barang_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/barang/' . $b->barang_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
}
