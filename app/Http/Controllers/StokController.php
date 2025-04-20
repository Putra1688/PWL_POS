<?php

namespace App\Http\Controllers;

use App\Models\StokModel;
use App\Models\SupplierModel;
use App\Models\BarangModel;
use App\Models\UserModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StokController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Data Stok Barang',
            'list' => ['Home', 'Stok']
        ];

        $page = (object) [
            'title' => 'Data Stok Barang Masuk'
        ];

        $activeMenu = 'stok';

        $barang = BarangModel::select('barang_id', 'barang_nama')->get();
        $supplier = SupplierModel::select('supplier_id', 'supplier_nama')->get();
        $user = UserModel::select('user_id', 'nama')->get();

        return view('stok.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'barang' => $barang,
            'supplier' => $supplier,
            'user' => $user,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list(Request $request)
    {
        $stok = StokModel::with(['barang', 'supplier', 'user'])->select(
            'stok_id', 'supplier_id', 'barang_id', 'user_id', 'stok_tanggal', 'stok_jumlah'
        );

        return DataTables::of($stok)
            ->addIndexColumn()
            ->addColumn('barang_nama', fn($s) => $s->barang->barang_nama ?? '-')
            ->addColumn('supplier_nama', fn($s) => $s->supplier->supplier_nama ?? '-')
            ->addColumn('nama', fn($s) => $s->user->nama ?? '-') // asumsi nama user di kolom 'name'
            ->addColumn('aksi', function ($s) {
                $btn = '<button onclick="modalAction(\''.url('/stok/' . $s->stok_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/stok/' . $s->stok_id . '/confirm_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create_ajax() {
        $supplier = SupplierModel::select('supplier_id', 'supplier_nama')->get();
        $barang = BarangModel::select('barang_id', 'barang_nama')->get();
        $user = UserModel::select('user_id', 'nama')->get();

        return view('stok.create_ajax', compact('supplier', 'barang', 'user'));
    }

    public function store_ajax(Request $request)
    {
        // Cek apakah request berupa ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'supplier_id' => 'required|integer|exists:m_supplier,supplier_id',
                'barang_id'   => 'required|integer|exists:m_barang,barang_id',
                'user_id'     => 'required|integer|exists:m_user,user_id',
                'stok_tanggal'=> 'required|date',
                'stok_jumlah' => 'required|integer|min:1',
            ];

            // use Illuminate\Support\Facades\Validator;
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            \App\Models\StokModel::create([
                'supplier_id'  => $request->supplier_id,
                'barang_id'    => $request->barang_id,
                'user_id'      => $request->user_id,
                'stok_tanggal' => $request->stok_tanggal,
                'stok_jumlah'  => $request->stok_jumlah,
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Data stok berhasil disimpan'
            ]);
        }

        return redirect('/');
    }

    public function confirm_ajax(string $id) {
        $stok = StokModel::find($id);
        return view('stok.confirm_ajax',['stok' =>$stok]);
    }

    public function delete_ajax(Request $request, $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $stok = StokModel::find($id);
            if ($stok) {
                $stok->delete();
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
