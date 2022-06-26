<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use App\Invoice;
use Illuminate\Support\Facades\DB;

class TrackOrderController extends Controller
{
    private $controller = 'track_order';
    private $description = '';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {   
        $user = auth()->user();
        $utils['title'] = __('main.track_order');
        $utils['titcontrollerle'] = $this->controller;
        $utils['route'] = route('track-order');
        return view($this->controller.'.index',compact('utils'));
    }

    public function detail($invoice){
        $user = auth()->user();
        $invoice = Invoice::where('invoice_number',$invoice)->where('user_id',$user->id)->first();
        if(!$invoice):
            return '<div class="text-center"><h5>'.__('main.data_not_found').'</h5></div>';
        else:
            $utils['resi_rajaongkir'] = $invoice->resiRajaOngkir();
            return $view = view($this->controller.'.detail',compact('invoice','utils'))->render();
        endif;
    }

}