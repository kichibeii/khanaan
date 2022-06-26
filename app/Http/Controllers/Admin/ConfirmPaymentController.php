<?php

namespace App\Http\Controllers\Admin;

use App\ConfirmPayment;
use App\Invoice;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Validator;

class ConfirmPaymentController extends Controller
{
    private $controller = 'confirm_payment';
    private $description = '';
    private $icon = 'flaticon-shopping-cart-1';

    public function __construct()
    {
        $this->middleware('auth');
    }

    private function title()
    {
        return __('main.'.$this->controller);
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ConfirmPayment  $confirmPayment
     * @return \Illuminate\Http\Response
     */
    public function show(ConfirmPayment $confirmPayment)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-update')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        $order = $confirmPayment->invoice;

        $utils['action'] = __('main.view');
        $utils['member'] = $order->user;
        $utils['billing'] = $order->billing;
        $utils['billing_rajaongkir'] = $utils['billing']->rajaOngkir();

        $utils['banks'] = \App\Bank::whereStatus(1)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
        $utils['arrSizes'] = \App\Dropdown::getOptions('size');
        $utils['arrColors'] = \App\Color::whereStatus(1)->pluck('title', 'id')->toArray();
        
        return view('admin.order.show', compact('confirmPayment', 'order', 'utils'))->with(array('controller' => 'order', 'title' => 'Konfirmasi<br>Pembayaran', 'description' => $this->description, 'icon' => $this->icon, 'root' => $this->controller));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ConfirmPayment  $confirmPayment
     * @return \Illuminate\Http\Response
     */
    public function edit(ConfirmPayment $confirmPayment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ConfirmPayment  $confirmPayment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ConfirmPayment $confirmPayment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ConfirmPayment  $confirmPayment
     * @return \Illuminate\Http\Response
     */
    public function destroy(ConfirmPayment $confirmPayment)
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

        $rows = ConfirmPayment::select([
            'invoices.invoice_number AS invoice_invoice_number',
            'banks.name AS bank_name',
            'confirm_payments.id',
            'confirm_payments.transfer_date',
            'confirm_payments.amount',
            'confirm_payments.status_approved'
        ])
        ->leftJoin('invoices', 'invoices.id','=','confirm_payments.invoice_id')
        ->leftJoin('banks', 'banks.id','=','confirm_payments.bank_id')
        ->where($where)
        ;

        return Datatables::of($rows)
            ->addIndexColumn()
            ->addColumn('statusText', function ($row) {
                return arrStatusApproval()[$row->status_approved];
            })
            ->addColumn('statusClass', function ($row) {
                return arrStatusActiveClass()[$row->status_approved];
            })
            ->addColumn('viewUrl', function ($row) {
                return route($this->controller.'.show', $row->id);
            })
            ->make(true);
    }
}
