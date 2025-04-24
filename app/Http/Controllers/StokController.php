<?php

namespace App\Http\Controllers;

use App\Models\StokModel;
use App\Models\SupplierModel;
use App\Models\BarangModel;
use App\Models\UserModel;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory; 
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

use Illuminate\Support\Facades\DB;

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

        // Tambahkan filter berdasarkan supplier_id
        if ($request->has('filter_supplier') && $request->filter_supplier != '') {
            $stok->where('supplier_id', $request->filter_supplier);
        }

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

    public function import() 
    { 
        return view('stok.import'); 
    }
    public function import_ajax(Request $request) 
    { 
        if($request->ajax() || $request->wantsJson()){ 
            $rules = [ 
                // validasi file harus xls atau xlsx, max 1MB 
                'file_stok' => ['required', 'mimes:xlsx', 'max:1024'] 
            ]; 
 
            $validator = Validator::make($request->all(), $rules); 
            if($validator->fails()){ 
                return response()->json([ 
                    'status' => false, 
                    'message' => 'Validasi Gagal', 
                    'msgField' => $validator->errors() 
                ]); 
            } 
 
            $file = $request->file('file_stok');  // ambil file dari request 
 
            $reader = IOFactory::createReader('Xlsx');  // load reader file excel 
            $reader->setReadDataOnly(true);             // hanya membaca data 
            $spreadsheet = $reader->load($file->getRealPath()); // load file excel 
            $sheet = $spreadsheet->getActiveSheet();    // ambil sheet yang aktif 
 
            $data = $sheet->toArray(null, false, true, true);   // ambil data excel 
 
            $insert = []; 
            if(count($data) > 1){ // jika data lebih dari 1 baris 
                foreach ($data as $baris => $value) { 
                    if($baris > 1){ // baris ke 1 adalah header, maka lewati 
                        if($baris > 1){ 
                            $supplier = SupplierModel::where('supplier_nama', $value['B'])->first();
                            $barang   = BarangModel::where('barang_nama', $value['C'])->first();
                            $user     = UserModel::where('nama', $value['E'])->first();
        
                            if ($supplier && $barang && $user) {
                                $insert[] = [ 
                                    'supplier_id' => $supplier->supplier_id, 
                                    'barang_id' => $barang->barang_id, 
                                    'user_id' => $user->user_id,
                                    'stok_tanggal' => $value['A'], 
                                    'stok_jumlah' => $value['D'], 
                                    'created_at' => now(), 
                                ]; 
                            }
                        }
                    } 
                } 
 
                if(count($insert) > 0){ 
                    // insert data ke database, jika data sudah ada, maka diabaikan 
                    StokModel::insertOrIgnore($insert);    
                } 
 
                return response()->json([ 
                    'status' => true, 
                    'message' => 'Data berhasil diimport' 
                ]); 
            }else{ 
                return response()->json([ 
                    'status' => false, 
                    'message' => 'Tidak ada data yang diimport' 
                ]); 
            } 
        } 
        return redirect('/stok'); 
    } 

    public function export_excel() {
        $stok = StokModel::select('stok_id', 'supplier_nama', 'barang_nama', 'nama', 'harga_beli', 'harga_jual')
            ->orderBy('supplier_i')
            ->with('supplier')
            ->get();
        
            // load library excel
            $spreadsheet =new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setCellValue('A1', 'No');
            $sheet->setCellValue('B1', 'Nama Supplier');
            $sheet->setCellValue('C1', 'Nama Barang');
            $sheet->setCellValue('D1', 'Nama User');
            $sheet->setCellValue('E1', 'Harga Beli');
            $sheet->setCellValue('F1', 'Harga Jual');

            $sheet->getStyle('A1:F1')->getFont()->setBold(true);

            $no = 1;
            $baris = 2;
            foreach ($stok as $key => $value) {
                $sheet->setCellValue('A' .$baris, $no);
                $sheet->setCellValue('B' .$baris, $value->supplier->supplier_nama);
                $sheet->setCellValue('C' .$baris, $value->barang->barang_nama);
                $sheet->setCellValue('D' .$baris, $value->user->nama);
                $sheet->setCellValue('E' .$baris, $value->harga_beli);
                $sheet->setCellValue('F' .$baris, $value->harga_jual);
                $baris++;
                $no++;
            }
        
            foreach (range('A', 'F') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }

            $sheet->setTitle('Data Barang'); // set title sheet

            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $filename = 'Data Stok ' . date('Y-m-d H:i:s') . '.xlsx';

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            header('Cache-Control: max-age=1');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
            header('Cache-Control: cache, must-revalidate');
            header('Pragma: public');

            $writer->save('php://output');
            exit;
    }

    public function export_pdf()
    {
        $barang = BarangModel::select('supplier_id', 'barang_id', 'user_id', 'harga_beli', 'harga_jual')
            ->orderBy('supplier_id')
            ->orderBy('barang_id')
            ->with('supplier')
            ->get();

        // use Barryvdh\DomPDF\Facade\Pdf;
        $pdf = Pdf::loadView('barang.export_pdf', ['barang' => $barang]);
        $pdf->setPaper('a4', 'portrait'); // set ukuran kertas dan orientasi
        $pdf->setOption('isRemoteEnabled', true); // set true jika ada gambar dari url
        $pdf->render();

        return $pdf->stream('Data Stok ' . date('Y-m-d H:i:s') . '.pdf');
    }

    public function rekapIndex()
    {
        $breadcrumb = (object) [
            'title' => 'Rekap Stok Barang',
            'list' => ['Home', 'Stok', 'Rekap']
        ];

        $page = (object) [
            'title' => 'Rekap Jumlah Stok per Barang'
        ];

        $activeMenu = 'rekap-stok'; // bisa disesuaikan dengan highlight sidebar

        return view('welcome', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function rekapList(Request $request)
    {
        $stok = StokModel::join('m_barang', 't_stok.barang_id', '=', 'm_barang.barang_id')
            ->select(
                'm_barang.barang_nama',
                DB::raw('SUM(t_stok.stok_jumlah) as total_stok')
            )
            ->groupBy('t_stok.barang_id', 'm_barang.barang_nama');

        return DataTables::of($stok)
            ->addIndexColumn()
            ->addColumn('barang_nama', function ($s) {
                return $s->barang_nama;
            })
            ->addColumn('stok_jumlah', function ($s) {
                return $s->total_stok;
            })
            ->make(true);
    }

}
