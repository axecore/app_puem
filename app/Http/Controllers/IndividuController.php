<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriKomoditas;
use App\Models\Kecamatan;
use App\Models\Pendidikan;
use App\Models\Individu;
use App\Models\BadanUsaha;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use App\Exports\IndividuExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use DB;

class IndividuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            'kecamatan' => Kecamatan::all(),
        ];
        return view('individu.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'kecamatan'   => Kecamatan::all(),
            'pendidikan'  => Pendidikan::all(),
            'badan_usaha' => BadanUsaha::all(),
            'kategori_komoditas' => KategoriKomoditas::all(),
        ];
        return view('individu.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validasi = [
            'nama_pemilik'          => 'required',
            'nik'                   => 'required',
            'jenis_kelamin'         => 'required',
            'no_hp'                 => 'required',
            'nama_usaha'            => 'required',
            'alamat_usaha'          => 'required',
            'id_kecamatan'          => 'required',
            'id_desa'               => 'required',
            'id_kategori_komoditas' => 'required',
            'id_komoditas'          => 'required',
            'id_sub_komoditas'      => 'required',
            'id_pendidikan'         => 'required',
            'id_badan_usaha'        => 'required',
            'tahun_berdiri'         => 'required',
            'status'                => 'required',
            'tanggal_simpan'        => 'required',
        ];

        $validator = Validator::make($request->all(), $validasi);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
        
        try{
            DB::beginTransaction();

            $input = $request->all();
            Individu::create($input);

            DB::commit();

        }catch(\Exception $e){
            DB::rollback();

            return response()->json([
                'status' => false,
                'msg' => $e->getMessage(),
            ]);
        }

        return response()->json([
            'status' => true,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = [
            'individu'    => Individu::find($id),
            'kecamatan'   => Kecamatan::all(),
            'pendidikan'  => Pendidikan::all(),
            'badan_usaha' => BadanUsaha::all(),
            'kategori_komoditas' => KategoriKomoditas::all(),
        ];
        return view('individu.form', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validasi = [
            'nama_pemilik'          => 'required',
            'nik'                   => 'required',
            'jenis_kelamin'         => 'required',
            'no_hp'                 => 'required',
            'nama_usaha'            => 'required',
            'alamat_usaha'          => 'required',
            'id_kecamatan'          => 'required',
            'id_desa'               => 'required',
            'id_kategori_komoditas' => 'required',
            'id_komoditas'          => 'required',
            'id_sub_komoditas'      => 'required',
            'id_pendidikan'         => 'required',
            'id_badan_usaha'        => 'required',
            'tahun_berdiri'         => 'required',
            'status'                => 'required',
            'tanggal_simpan'        => 'required',
        ];

        $validator = Validator::make($request->all(), $validasi);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
        
        try{
            DB::beginTransaction();

            $individu = Individu::find($id);
            $input = $request->all();
            $individu->update($input);

            DB::commit();

        }catch(\Exception $e){
            DB::rollback();

            return response()->json([
                'status' => false,
                'msg' => $e->getMessage(),
            ]);
        }

        return response()->json([
            'status' => true,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Individu::find($id)->delete();
        return response()->json([
            'status' => true,
        ]);
    }

    public function ajaxSearch(Request $request)
    {
        $data = [];
        if ($id_desa = $request->input('id_desa')) {
            $query = Individu::where('id_desa', $id_desa);

            if ($search = $request->input('search')) {
                $query->where('nama_pemilik','LIKE','%'.$search.'%');
            }

            $data = $query->get();
        }
        return response()->json($data);
    }

    public function export(Request $request)
	{
        $type = $request->get('type');
        $extension = $request->get('extension');
        $function  = '_rekap_'.$request->get('extension');
        return $this->{$function}($request);
    }

    function _rekap_excel(Request $request)
	{
        $id_kecamatan = $request->get('id_kecamatan');
        $id_desa      = $request->get('id_desa');
        $type         = $request->get('type');;
        return Excel::download(
            new IndividuExport($id_kecamatan, $id_desa, $type), 
            $type.'.xlsx'
        );
    }

    function _rekap_pdf(Request $request)
	{
        $report = Individu::where('id_kecamatan', $request->get('id_kecamatan'));

        if($request->get('type') == 'rekap_desa'){
            $report->where('id_desa', $request->get('id_desa'));
        }

        $data = [
            'kecamatan' => Kecamatan::find($request->get('id_kecamatan')),
            'data'      => $report->get(),
        ];
        // return view('individu.pdf.rekap', $data);
        $pdf = PDF::loadView('individu.pdf.rekap', $data)->setPaper('a4', 'landscape');
        return $pdf->stream($request->get('type').'.pdf');
    }

    public function getDataTables(Request $request)
    {
        $query = Individu::query();

        if ($id_kecamatan = $request->get('id_kecamatan')) {
            $query->where('id_kecamatan', $id_kecamatan);
        }

        if ($id_desa = $request->get('id_desa')) {
            $query->where('id_desa', $id_desa);
        }

        $query = $query->orderBy('id','DESC')->get();

        return Datatables::of($query)
            ->make(true);
    }
}
