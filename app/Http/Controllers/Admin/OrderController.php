<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Invoice;
use App\InvoiceTracking;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Validator;
use Illuminate\Support\Facades\Hash;
use App\ConfirmPayment;
use Carbon\Carbon;

class OrderController extends Controller
{
    private $controller = 'order';
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

    public function index()
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-index')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );
        }

        return view('admin.'.$this->controller.'.index')->with(array('controller' => $this->controller, 'title' => $this->title(), 'description' => $this->description, 'icon' => $this->icon));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Invoice $order)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-update')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );
        }

        if ($order->status_invoice === 99){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );
        }

        $confirmPayment = false;
        $utils['action'] = __('main.view');
        $utils['member'] = $order->user;
        $utils['billing'] = $order->billing;
        $utils['billing_rajaongkir'] = $utils['billing']->rajaOngkir();

        $utils['banks'] = \App\Bank::whereStatus(1)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
        $utils['arrSizes'] = \App\Dropdown::getOptions('size');
        $utils['arrColors'] = \App\Color::whereStatus(1)->pluck('title', 'id')->toArray();

        return view('admin.'.$this->controller.'.show', compact('confirmPayment', 'order', 'utils'))->with(array('controller' => $this->controller, 'title' => $this->title(), 'description' => $this->description, 'icon' => $this->icon, 'root' => $this->controller));
    }

    public function approve(Request $request, Invoice $order)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-update')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );
        }

        if ($order->status_invoice != 1){
            return redirect()->route($this->controller.'.show', $order->id)->with('error', 'Invoice tidak bisa diapprove pembayarannya. Karena sudah expired' );
        }

        if ($order->status_payment == 1){
            return redirect()->route($this->controller.'.show', $order->id)->with('error', 'Invoice Sudah dibayar' );
        }

        $confirmPayment = ConfirmPayment::where('invoice_id', $order->id)->first();
        if ($confirmPayment){
            $confirmPayment->transfer_date = date('Y-m-d', strtotime($request->date));
            $confirmPayment->bank_id = $request->get('bank_id');
            $confirmPayment->amount = str_replace('.', '', $request->get('amount'));
            $confirmPayment->approved_by = $userLoged->id;
            $confirmPayment->approved_at = Carbon::now('Asia/Jakarta')->format("Y-m-d H:i:s");
            $confirmPayment->status_approved = 1;
            $confirmPayment->save();
        } else {
            $arrSaved = [
                'invoice_id' => $order->id,
                'transfer_date' => date('Y-m-d', strtotime($request->date)),
                'amount' => str_replace('.', '', $request->get('amount')),
                'bank_id' => $request->get('bank_id'),
                'approved_by' => $userLoged->id,
                'approved_at' => Carbon::now('Asia/Jakarta')->format("Y-m-d H:i:s"),
                'status_approved' => 1
            ];

            $confirmPayment = ConfirmPayment::create($arrSaved);
        }

        $order->tracking = 3;
        $order->status_payment = 1;
        $order->save();

        // simpan invoice tracking
        InvoiceTracking::create([
            'invoice_id' => $order->id,
            'tracking' => 3,
            'activity_date' => $confirmPayment->transfer_date
        ]);

        $order->product_bookeds()->delete();

        return redirect()->route($request->root.'.index')->with('status', __( 'main.data_has_been_approved', ['page' => $order->invoice_number] ) );
    }

    public function updateResi(Request $request, Invoice $order)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-update')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );
        }

        if ($order->status_invoice != 1){
            return redirect()->route($this->controller.'.show', $order->id)->with('error', 'Invoice tidak bisa diisi nomor resi. Karena sudah expired' );
        }

        if ($order->status_payment != 1){
            return redirect()->route($this->controller.'.show', $order->id)->with('error', 'Invoice Belum dibayar' );
        }

        $order->tracking = 4;
        $order->nomor_resi = $request->nomor_resi;
        $order->date_shipped = date('Y-m-d', strtotime($request->date));
        $order->save();

        // simpan/update invoice tracking
        InvoiceTracking::updateOrCreate(
            ['invoice_id' => $order->id, 'tracking' => 4],
            ['activity_date' => $order->date_shipped]
        );

        return redirect()->route($this->controller.'.index')->with('status', __( 'main.data_has_been_updated', ['page' => $order->invoice_number] ) );
    }

    public function getData(Request $request)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-index')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );
        }

        $where = [];
        $where[] = ['status_invoice', '<>', 99];

        $rows = Invoice::select([
            'invoices.id',
            'invoice_number',
            'invoice_date',
            'users.name as name',
            'grand_total',
            'status_payment',
            'status_invoice',
            'idr_rate',
            'invoices.currency'
        ])
        ->leftJoin('users', 'users.id','=','invoices.user_id')
        ->where($where)
        ;

        return Datatables::of($rows)
            ->addIndexColumn()
            ->editColumn('grand_total',function($row){
                $grand_total = getPrice($row->currency, $row->idr_rate, $row->grand_total);
                return displayPrice($row->currency, $grand_total);
            })
            ->addColumn('statusText', function ($row) {
                return arrStatusInvoice()[$row->status_invoice];
            })
            ->addColumn('statusClass', function ($row) {
                return arrStatusActiveClass()[$row->status_invoice];
            })
            ->addColumn('statusTextPayment', function ($row) {
                return arrStatusPayment()[$row->status_payment];
            })
            ->addColumn('statusClassPayment', function ($row) {
                return arrStatusActiveClass()[$row->status_payment];
            })
            ->addColumn('viewUrl', function ($row) {
                return route($this->controller.'.show', $row->id);
            })
            ->addColumn('canEdit', function () {
                return auth()->user()->can($this->controller.'-update');
            })
            ->addColumn('editUrl', function ($row) {
                return route($this->controller.'.edit', $row->id);
            })
            ->make(true);
    }
}
