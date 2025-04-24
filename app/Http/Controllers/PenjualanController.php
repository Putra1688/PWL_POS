<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use App\Models\PenjualanModel;
use App\Models\BarangModel;
use App\Models\PenjualanDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Transaksi',
            'list' => ['Home', 'Transaksi']
        ];
        
        $page = (object) [
            'title' => 'Daftar transaksi penjualan yang terdaftar dalam sistem'
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
        $penjualan = PenjualanModel::select(
            't_penjualan.penjualan_id',
            't_penjualan.penjualan_kode',
            't_penjualan.pembeli',
            't_penjualan.penjualan_tanggal',
            't_penjualan.user_id',
        )->with('detail', 'user.level'); 
        
        if ($request->user_id){ 
            $penjualan->where('user_id',$request->user_id); 
        } 
    
        return DataTables::of($penjualan)  
        ->editColumn('penjualan_tanggal', function ($penjualan) {
            return \Carbon\Carbon::parse($penjualan->penjualan_tanggal)->format('d-m-Y');
        })

        ->addColumn('total_harga', function ($penjualan) {
            $totalHarga = $penjualan->detail->sum(function ($detail) {
                return $detail->harga * $detail->jumlah;
            });
            return 'Rp' . number_format($totalHarga, 0, ',', '.') . ',00';
        })

        ->addColumn('user', function ($penjualan) {
            $user = $penjualan->user;
            return $user->nama . " (" . $user->level->level_kode . ")";
        })
            ->addColumn('aksi', function ($penjualan) {  // menambahkan kolom aksi  
                $btn  = '<button onclick="modalAction(\''.url('/penjualan/' . $penjualan->penjualan_id. '/show_detail').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualan->penjualan_id . '/delete') . '\')" class="btn btn-danger btn-sm">Hapus</button> '; 
    
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

    public function create()
    {
        $barang = BarangModel::select('barang_id', 'barang_nama', 'barang_kode', 'harga_jual')
            ->with('stok') // ambil stok terbaru
            ->whereHas('stok')
            ->get();

        // dd($barang);
        return view('penjualan.create')->with([
            'barang' => $barang
        ]);
    }

    public function create_ajax() {
        $barang = BarangModel::select('barang_id', 'barang_nama', 'barang_kode', 'harga_jual')
        ->with('stok') // ambil stok terbaru
        ->whereHas('stok')
        ->get();

        return view('penjualan.create_penjualan', [
            'barang' => $barang
        ]);
    }

    public function store(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'pembeli' => 'required|string|max:100',
                'penjualan_kode' => 'required|string|max:5|unique:t_penjualan,penjualan_kode',
                'barang_id' => 'required|array',
                'barang_id.*' => 'required|integer|exists:m_barang,barang_id',
                'jumlah' => 'required|array',
                'jumlah.*' => 'required|integer',
            ];


            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $errorMessage = 'Validasi Gagal';
                if ($validator->errors()->has('penjualan_kode')) {
                    $errorMessage = 'Validasi Gagal (Kode Sudah Digunakan)';
                }

                return response()->json([
                    'status' => false,
                    'message' => $errorMessage,
                    'msgField' => $validator->errors(),
                ]);
            }

            $dataPenjualan['pembeli'] = $request['pembeli'];
            $dataPenjualan['penjualan_kode'] = $request['penjualan_kode'];
            $dataPenjualan['user_id'] = auth()->user()->user_id;
            $dataPenjualan['penjualan_tanggal'] = now();
            $dataPenjualan['created_at'] = now();
            $dataPenjualan['updated_at'] = now();

            // dd($dataPenjualan['pembeli']);
            $idPenjualan = 0;
            try {
                $idPenjualan = PenjualanModel::create($dataPenjualan)->penjualan_id;

            } catch (\Throwable $th) {
                return response()->json([
                    // 'status' => true,
                    'status' => false,
                    'message' => 'Gagal Disimpan'
                ]);
            }

            if ($idPenjualan == 0) {
                return response()->json([
                    // 'status' => true,
                    'status' => false,
                    'message' => 'Gagal Disimpan'
                ]);
            }

            $jumlahBarang = count($request->barang_id);

            for ($i = 0; $i < $jumlahBarang; $i++) {
                $dataDetail['penjualan_id'] = $idPenjualan;
                $dataDetail['barang_id'] = $request['barang_id'][$i];
                $dataDetail['harga'] = $request['harga'][$i];
                $dataDetail['jumlah'] = $request['jumlah'][$i];
                $dataDetail['created_at'] = now();
                $dataDetail['updated_at'] = now();

                // PenjualanDetailModel::create($dataDetail);

                try {
                    PenjualanDetail::create($dataDetail);
                } catch (\Throwable $th) {
                    return response()->json([
                        // 'status' => true,
                        'status' => false,
                        'message' => 'Gagal Disimpan'
                    ]);
                }
            }
            // dd($jumlahBarang);

            return response()->json([
                // 'status' => true,
                'status' => true,
                'message' => 'Data penjualan berhasil disimpan'
            ]);
        }

        return redirect('/');
    }

    public function store_ajax(Request $request)
{
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'pembeli' => 'required|string|max:100',
            'penjualan_kode' => 'required|string|max:7|unique:t_penjualan,penjualan_kode',
            'barang_id' => 'required|array',
            'barang_id.*' => 'required|integer|exists:m_barang,barang_id', // validasi barang yang dipilih
            'jumlah' => 'required|array', // pastikan jumlah adalah array
            'jumlah.*' => 'required|integer|min:1', // validasi jumlah
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errorMessage = $validator->errors()->has('penjualan_kode') 
                ? 'Validasi Gagal (Kode Sudah Digunakan)' 
                : 'Validasi Gagal';

            return response()->json([
                'status' => false,
                'message' => $errorMessage,
                'msgField' => $validator->errors(),
            ]);
        }

        // Memastikan jumlah barang sesuai dengan yang dikirimkan
        if (count($request->barang_id) !== count($request->jumlah)) {
            return response()->json([
                'status' => false,
                'message' => 'Data barang dan jumlah tidak konsisten',
            ]);
        }

        DB::beginTransaction();
        try {
            // Menyimpan data penjualan utama
            $dataPenjualan = $request->only('pembeli', 'penjualan_kode');
            $dataPenjualan['user_id'] = auth()->user()->user_id;
            $dataPenjualan['penjualan_tanggal'] = now();
            $dataPenjualan['created_at'] = now();
            $dataPenjualan['updated_at'] = now();

            $penjualan = PenjualanModel::create($dataPenjualan);

            // Menyimpan detail penjualan per barang tanpa harga
            for ($i = 0; $i < count($request->barang_id); $i++) {
                PenjualanDetail::create([
                    'penjualan_id' => $penjualan->penjualan_id,
                    'barang_id' => $request->barang_id[$i],
                    'jumlah' => $request->jumlah[$i],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data penjualan berhasil disimpan'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal Disimpan: ' . $th->getMessage()
            ]);
        }
    }

    return redirect('/');
}


    public function confirm_ajax(string $id) {
        $penjualan = PenjualanModel::find($id);
        return view('penjualan.confirm_ajax',['penjualan' =>$penjualan]);
    }

    public function delete_ajax(Request $request, $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $penjualan = PenjualanModel::find($id);
            if ($penjualan) {
                $penjualan->delete();
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
