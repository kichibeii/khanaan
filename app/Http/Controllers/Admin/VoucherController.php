<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Voucher;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Validator;

class VoucherController extends Controller
{
    private $controller = 'voucher';
    private $description = '';
    private $icon = 'flaticon-more';

    public function __construct()
    {
        $this->middleware('auth');
    }

    private function title()
    {
        return __('main.'.$this->controller);
    }

    private function arrNiceName()
    {
        return array(
            'total_print' => 'Jumlah Cetak',
            'nominal' => 'Nominal',
            'start_date' => 'Tanggal Mulai Berlaku',
            'end_date' => 'Tanggal Berakhir Berlaku',
        );
    }

    private function arrValidate($action="create")
    {
        $arrValidates = [
            'total_print' => 'required',
            'nominal' => 'required',
            'start_date' => 'required|date_format:d-m-Y',
            'end_date' => 'required|date_format:d-m-Y',
        ];

        return $arrValidates;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-index')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        return view('admin.'.$this->controller.'.index')->with(array('controller' => $this->controller, 'title' => $this->title(), 'description' => $this->description, 'icon' => $this->icon));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-create')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        $utils['action'] = __('main.add_new');
        
        return view('admin.'.$this->controller.'.create', compact('utils'))->with(array('controller' => $this->controller, 'title' => $this->title(), 'description' => $this->description, 'icon' => $this->icon));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-create')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        $arrValidates = $this->arrValidate();

        Validator::make($request->all(), $arrValidates, [], $this->arrNiceName())->validate();

        $total_print = str_replace('.', '', $request->total_print);
        for ($x=1; $x<=$total_print; $x++) {
            $code = substr(strtoupper(md5(uniqid(rand(), true))), 0, 10);

            $arrSave['Voucher'][] = array(
                'code'          => $code,
                'nominal'       => str_replace('.', '', $request->nominal),
                'start_date'    => date('Y-m-d', strtotime($request->get('start_date'))),
                'end_date'      => date('Y-m-d', strtotime($request->get('end_date')))

            );
        }

        Voucher::insert($arrSave['Voucher']);

        return redirect()->route($this->controller.'.index')->with('status', __( 'main.data_has_been_added', ['page' => $this->title()] ) );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Voucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function show(Voucher $voucher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Voucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function edit(Voucher $voucher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Voucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Voucher $voucher)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Voucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function destroy(Voucher $voucher)
    {
        //
    }

    public function getData(Request $request)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-index')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        $where = [];

        $rows = Voucher::select([
            'vouchers.id',
            'vouchers.code',
            'vouchers.nominal',
            'vouchers.start_date',
            'vouchers.end_date',
            'vouchers.activated_date',
            'users.name as user_name'
        ])
        ->leftJoin('users', 'users.id','=','vouchers.user_id')
        ->where($where);

        return Datatables::of($rows)
            ->addIndexColumn()
            ->addColumn('start_date', function ($row) {
                return date('d/m/Y', strtotime($row->start_date));
            })
            ->addColumn('end_date', function ($row) {
                return date('d/m/Y', strtotime($row->end_date));
            })
            ->addColumn('activated_date', function ($row) {
                return !is_null($row->activated_date) ? date('d/m/Y', strtotime($row->activated_date)) : '';
            })
            ->make(true);
    }
}
