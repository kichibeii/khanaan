<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use App\Invoice;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    private $controller = 'purchase';
    private $description = '';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = auth()->user();

        $period = $request->get('period');
        $urutan = $request->get('urutan');
        $name   = $request->get('name');

        if(!$period) $period = date('01/m/Y').'-'.date('d/m/Y');
        if(!$urutan) $urutan = 'terbaru';

        //check period max 60 days
        $temp_period = explode('-',$period);
        $start_date = date("Y-m-d",strtotime(str_replace('/','-',$temp_period[0])));
        $end_date   = date("Y-m-d",strtotime(str_replace('/','-',$temp_period[1])));
        $days = 0;
        if($period):
            $datetime1 = new DateTime($start_date);
            $datetime2 = new DateTime($end_date);
            $interval = $datetime1->diff($datetime2);
            $days = $interval->format('%a') + 1;//now do whatever you like with $days
        endif;

        //if($days<60):
            /*
            $where[] = ['invoices.invoice_date', '>=', $start_date];
            $where[] = ['invoices.invoice_date', '<=', $end_date];
            */
            $where[] = ['invoices.status_invoice', 1];
            $where[] = ['invoices.user_id', '=', $user->id];

            //$invoice_id = 0;
            $data = Invoice::select([
                    'invoices.id',
                    'invoices.invoice_date',
                    'invoices.grand_total',
                    'invoices.invoice_number',
                    'invoices.status_payment',
                    'invoices.status_invoice',
                    'invoices.idr_rate',
                    'invoices.currency',

                    'products.id as product_id',
                    'products.title as product_name',
                    'products.image as product_image',
                    'products.slug as product_slug',
                    'i_product.qty',
                    'i_product.price',

                    'colors.title as color_name',
                    'size.title as size_name',
                ])
                ->join('invoice_products as i_product','i_product.invoice_id', '=', 'invoices.id')
                ->join('products', 'products.id', '=', 'i_product.product_id')
                ->join('colors', 'colors.id', '=', 'i_product.color_id')
                ->join('dropdown_items as size', 'size.id', '=', 'i_product.size_id')
                ->where($where)
                ->groupBy('invoices.id');

            if($urutan == 'terbaru'):
                $data->orderBy('invoices.id','desc');
            else:
                $data->orderBy('invoices.grand_total','desc');
            endif;
            if($name):
                $data->where('products.title','like','%'.$name.'%');
            endif;
            $utils['data'] = $data->paginate(5);
        //endif;

        $utils['urutan'] = ['terbaru' => 'Terbaru','nilai' => 'Nilai Transaksi'];
        $utils['period'] = $period;
        $utils['urutan_selected'] = $urutan;
        $utils['name'] = $name;
        $utils['days'] = $days;
        return view($this->controller.'.index',compact('utils'));
    }

    public function detail(Invoice $invoice){
        $user = auth()->user();

        if(!$invoice || $invoice->user_id != $user->id):
            echo '<h1 class="text-center">Data tidak ditemukan</h1>';
        else:
            $utils['member'] = $invoice->user;
            $utils['billing'] = $invoice->billing;
            $utils['billing_rajaongkir'] = $utils['billing']->rajaOngkir();
            $utils['resi_rajaongkir'] = $invoice->resiRajaOngkir();

            $utils['banks'] = \App\Bank::whereStatus(1)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
            $utils['arrSizes'] = \App\Dropdown::getOptions('size');
            $utils['arrColors'] = \App\Color::whereStatus(1)->pluck('title', 'id')->toArray();
            echo $view = view($this->controller.'.detail',compact('invoice','utils'))->render();
        endif;
    }
}
