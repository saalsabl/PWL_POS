<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\StokModel;
use App\Models\SupplierModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class StokController extends Controller
{
    public function index()
    {

        $breadcrumb = (object) [
            'title' => 'Daftar stok',
            'list' => ['Home', 'stok'],
        ];

        $page = (object) [
            'title' => 'Daftar stok yang terdaftar dalam sistem',
        ];

        $barang = BarangModel::all(); //ambil data stok untuk filter stok
        $supplier = SupplierModel::all();

        $activeMenu = 'stok'; //set menu yang sedang active

        return view('stok.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'barang' => $barang,
            'supplier' => $supplier,
            'activeMenu' => $activeMenu]);

    }

    public function list(Request $request)
    {
        $stoks = StokModel::select('stok_id', 'supplier_id', 'barang_id', 'user_id', 'stok_tanggal', 'stok_jumlah')
            ->with(['supplier', 'barang', 'user']);

        //Filter data user berdasarkan level_id
        if ($request->barang_id) {
            $stoks->where('barang_id', $request->barang_id);
        }

        //Filter data user berdasarkan level_id
        if ($request->supplier_id) {
            $stoks->where('supplier_id', $request->supplier_id);
        }

        // Return data untuk DataTables
        return DataTables::of($stoks)
            ->addIndexColumn() // menambahkan kolom index / nomor urut
            ->addColumn('aksi', function ($stok) {
                // Menambahkan kolom aksi untuk edit, detail, dan hapus
                // $btn = '<a href="' . url('/stok/' . $stok->stok_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                // $btn .= '<a href="' . url('/stok/' . $stok->stok_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                // $btn .= '<form class="d-inline-block" method="POST" action="' . url('/stok/' . $stok->stok_id) . '">'
                //     . csrf_field() . method_field('DELETE') .
                //     '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                // return $btn;

                $btn = '<button onclick="modalAction(\'' . url('/stok/' . $stok->stok_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button>';
                $btn .= '<button onclick="modalAction(\'' . url('/stok/' . $stok->stok_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/stok/' . $stok->stok_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';

                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi berisi HTML
            ->make(true);
    }

    public function show_ajax($id)
    {
        // Mencari data stok berdasarkan ID
        $stok = StokModel::with(['supplier', 'barang', 'user']) // Pastikan relasi ini ada di model Stok
                    ->find($id);

        // Mengembalikan tampilan dengan data stok
        if ($stok) {
            return view('stok.show_ajax', compact('stok'));
        } else {
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }
    }


    public function create_ajax()
    {
        $barang = BarangModel::select('barang_id', 'barang_nama')->get();
        $supplier = SupplierModel::select('supplier_id', 'supplier_nama')->get();
        $user = UserModel::select('user_id', 'nama')->get();

        return view('stok.create_ajax')->with([
            'barang' => $barang,
            'supplier' => $supplier,
            'user' => $user,
        ]);
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'supplier_id' => ['required', 'integer'],
                'barang_id' => ['required', 'integer'],
                'user_id' => ['required', 'integer'],
                'stok_tanggal' => ['required', 'date'],
                'stok_jumlah' => ['required', 'integer'],
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            StokModel::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil disimpan',
            ]);
        }
        redirect('/');
    }

    public function edit_ajax($id)
    {
        $stok = StokModel::find($id);
        $barang = BarangModel::select('barang_id', 'barang_nama')->get();
        $supplier = SupplierModel::select('supplier_id', 'supplier_nama')->get();
        $user = UserModel::select('user_id', 'nama')->get();

        return view('stok.edit_ajax')->with([
            'stok' => $stok,
            'barang' => $barang,
            'supplier' => $supplier,
            'user' => $user,
        ]);

    }

    public function update_ajax(Request $request, $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [

                'supplier_id' => ['required', 'integer', 'exists:m_supplier,supplier_id'],
                'barang_id' => ['required', 'integer', 'exists:m_barang,barang_id'],
                'user_id' => ['required', 'integer', 'exists:m_user,user_id'],
                'stok_tanggal' => ['required', 'date'],
                'stok_jumlah' => ['required', 'integer'],
            ];

            // use Illuminate\Support\Facades\Validator;
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false, // respon json, true: berhasil, false: gagal
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors(), // menunjukkan field mana yang error
                ]);
            }

            $check = StokModel::find($id);
            if ($check) {
                $check->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan',
                ]);
            }
        }
        return redirect('/');
    }

    public function confirm_ajax($id)
    {
        $stok = StokModel::find($id);
        return view('stok.confirm_ajax', ['stok' => $stok]);
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $stok = StokModel::find($id);
            if ($stok) { // jika sudah ditemuikan
                $stok->delete(); // stok di hapus
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan',
                ]);
            }
        }
        return redirect('/');
    }

    public function destroy($id)
    {
        // Assuming you have a Stock model
        $stok = StokModel::find($id); // Find the stock record by ID
        if (!$stok) {
            return response()->json(['status' => false, 'message' => 'Data yang anda cari tidak ditemukan.']);
        }

        // Proceed to delete the stock record
        $stok->delete();
        return response()->json(['status' => true, 'message' => 'Data stok berhasil dihapus.']);
    }



    // Menampilkan detail stok
    public function show(string $id)
    {
        $stok = StokModel::with('supplier')->find($id);
        $breadcrumb = (object) ['title' => 'Detail stok', 'list' => ['Home', 'stok', 'Detail']];
        $page = (object) ['title' => 'Detail stok'];
        $activeMenu = 'stok'; // set menu yang sedang aktif
        return view('stok.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'stok' => $stok, 'activeMenu' => $activeMenu]);
    }

    public function import()
    {
        return view('stok.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_stok' => ['required', 'mimes:xlsx', 'max:1024'], // Pastikan nama sesuai dengan input
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_stok'); // Ambil file dari request
            $reader = IOFactory::createReader('Xlsx'); // Load reader file excel
            $reader->setReadDataOnly(true); // Membaca data
            $spreadsheet = $reader->load($file->getRealPath()); // Load file excel
            $sheet = $spreadsheet->getActiveSheet(); // Mengambil sheet aktif
            $data = $sheet->toArray(null, false, true, true); // Ambil data excel

            $insert = [];
            if (count($data) > 1) {
                foreach ($data as $baris => $value) {
                    if ($baris > 1) { // Mulai dari baris kedua untuk menghindari header
                        $insert[] = [
                            'stok_id' => $value['A'],
                            'supplier_id'     => $value['B'],
                            'barang_id'       => $value['C'],
                            'user_id' => $value['D'],
                            'stok_tanggal'    => $value['E'], // Contoh jika tanggal di kolom C
                            'stok_jumlah'     => $value['F'], // Pastikan sesuai dengan kolom
                            'created_at'      => now(),
                        ];
                    }
                }
            }

            if (count($insert) > 0) {
                StokModel::insertOrIgnore($insert);
            }

            return response()->json([
                'status'  => true,
                'message' => 'Data berhasil diimport'
            ]);
        } else {
            return response()->json([
                'status'  => false,
                'message' => 'Tidak ada data yang diimport'
            ]);
        }
    }

    public function export_excel()
    {
        // Ambil data stok barang yang akan diexport
        $stokBarang = StokModel::select('stok_id', 'supplier_id', 'barang_id', 'user_id', 'stok_tanggal', 'stok_jumlah')
            ->get();

        // Load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); // Ambil sheet yang aktif
        $sheet->setCellValue('A1', 'ID Stok');
        $sheet->setCellValue('B1', 'ID Supplier');
        $sheet->setCellValue('C1', 'ID Barang');
        $sheet->setCellValue('D1', 'ID User');
        $sheet->setCellValue('E1', 'Tanggal Stok');
        $sheet->setCellValue('F1', 'Jumlah Stok');

        $sheet->getStyle('A1:F1')->getFont()->setBold(true); // Bold header

        $baris = 2; // Baris data dimulai dari baris ke 2
        foreach ($stokBarang as $key => $value) {
            $sheet->setCellValue('A' . $baris, $value->stok_id);
            $sheet->setCellValue('B' . $baris, $value->supplier_id);
            $sheet->setCellValue('C' . $baris, $value->barang_id);
            $sheet->setCellValue('D' . $baris, $value->user_id);
            $sheet->setCellValue('E' . $baris, $value->stok_tanggal); // Format tanggal
            $sheet->setCellValue('F' . $baris, $value->stok_jumlah);
            $baris++;
        }

        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true); // Set auto size untuk kolom
        }

        $sheet->setTitle('Data Stok Barang'); // Set title sheet
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Stok Barang ' . date('Y-m-d H:i:s') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified:' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $writer->save('php://output');
        exit;
    }

    public function export_pdf()
    {
        // Mengambil data stok barang beserta relasi supplier, barang, dan user
        $stokBarang = StokModel::select('stok_id', 'supplier_id', 'barang_id', 'user_id', 'stok_tanggal', 'stok_jumlah')
            ->orderBy('stok_id')
            // ->orderBy('barang_id')
            // ->orderBy('user_id')
            ->with('supplier', 'barang', 'user')
            ->get();

        // Menggunakan Barryvdh\DomPDF\Facade\Pdf untuk membuat PDF
        $pdf = Pdf::loadView('stok.export_pdf', ['stokBarang' => $stokBarang]);
        $pdf->setPaper('a4', 'portrait'); // Set ukuran kertas dan orientasi
        $pdf->setOption("isRemoteEnabled", true); // Set true jika ada gambar dari URL
        $pdf->render();

        // Mengembalikan hasil PDF dalam bentuk stream (langsung ditampilkan)
        return $pdf->stream('Data Stok Barang ' . date('Y-m-d H:i:s') . '.pdf');
    }

}