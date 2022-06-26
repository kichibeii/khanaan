<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cart;
use App\Product;
use App\ProductDiscount;
use App\ProductStokActivity;
use App\ProductSizeQty;
use App\Dropdown;
use App\Color;
use App\Voucher;
use App\UserBilling;
use App\Invoice;
use App\InvoiceBilling;
use App\InvoiceProduct;
use App\InvoiceProductBooked;
use App\InvoiceTracking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Validator;
use Session;
use Mail;
use App\Mail\InvoiceMail;
use App\ConfirmPayment;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware(['userauth','verified', 'cartauth']);
    }







    private function arrNiceName()
    {
        return array(
            'name' => __('main.name'),
            'country_id' => __('main.country'),
            'province_id' => __('main.province'),
            'city_id' => __('main.city'),
            'subdistrict_id' => __('main.sub_district'),
            'address' => __('main.address'),
            'postcode' => __('main.postal_code'),
            'handphone' => __('main.phone_number'),
        );
    }

    public function storeBilling(Request $request)
    {
        $arrValidates = [
            'name' => 'required',
            'country_id' => 'required',
            'address' => 'required',
            'postcode' => 'required|max:6',
            'handphone' => 'required|max:20',
        ];

        if ($request->country_id == '0'){
            $arrValidates['province_id'] = 'required';
            $arrValidates['city_id'] = 'required';
            $arrValidates['subdistrict_id'] = 'required';
        }

        Validator::make($request->all(), $arrValidates, [], $this->arrNiceName())->validate();
        $isMain = !count(auth()->user()->billings) ? 1 : ($request->get('is_main') == 'on' ? 1 : 0);
        $arrSaved = [
            'user_id' => auth()->user()->id,
            'name' => $request->get('name'),
            'country_id' => $request->get('country_id') == 0 ? null : $request->get('country_id'),
            'country_name' => $request->get('country_id') == 0 ? 'Indonesia' : null,
            'province_id' => $request->get('province_id'),
            'city_id' => $request->get('city_id'),
            'subdistrict_id' => $request->get('subdistrict_id'),
            'address' => $request->get('address'),
            'postcode' => $request->get('postcode'),
            'handphone' => $request->get('handphone'),
            'is_main' => !count(auth()->user()->billings) ? 1 : ($request->get('is_main') == 'on' ? 1 : 0),
        ];

        if ($isMain){
            $mainBilling = auth()->user()->billings()->where('is_main', 1)->first();
            if ($mainBilling){
                $mainBilling->is_main = 0;
                $mainBilling->save();
            }
        }

        $userBilling = UserBilling::create($arrSaved);

        return redirect()->route('checkout', ['billing_id'=>$userBilling])->with('success', __( 'main.data_has_been_added', ['page' => $userBilling->name] ) );
    }

    

    public function confirmPayment()
    {
        $utils = [];
        $utils['banks'] = \App\Bank::whereStatus(1)->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();

        return view('confirm-payment', compact('utils'));
    }

    private function arrNiceNameConfirm()
    {
        return array(
            'invoice_number' => 'Order ID',
            'bank_id' => 'Bank',
            'amount_transfer' => 'Jumlah Transfer',
            'transfer_date' => 'Tanggal Transfer'
        );
    }

    public function storeConfirmPayment(Request $request)
    {
        $arrValidates = [
            'invoice_number' => 'required',
            'bank_id' => 'required',
            'amount_transfer' => 'required',
            'transfer_date' => 'required'
        ];

        Validator::make($request->all(), $arrValidates, [], $this->arrNiceNameConfirm())->validate();

        $invoice = Invoice::where('invoice_number', $request->invoice_number)->first();

        if (!$invoice){
            return redirect()->route('confirm-payment')->with( ['error' => 'Order ID tidak ditemukan'])->withInput($request->input());
        }

        if ($invoice->status_payment == 1){
            return redirect()->route('confirm-payment')->with( ['error' => 'Order sudah dibayar'])->withInput($request->input());
        }

        if ($invoice->status_invoice == 0){
            return redirect()->route('confirm-payment')->with( ['error' => 'Order sudah expired. Silahkan info ke admin. kemudian lakukan order ulang'])->withInput($request->input());
        }

        $confirmPayment = ConfirmPayment::where('invoice_id', $invoice->id)->first();
        if ($confirmPayment){
            return redirect()->route('confirm-payment')->with( ['error' => 'Anda sudah pernah melakukan konfirmasi pembayaran. Silahkan tunggu proses selanjutnya dari admin'])->withInput($request->input());
        }

        $arrSaved = [
            'invoice_id' => $invoice->id,
            'transfer_date' => date('Y-m-d', strtotime($request->transfer_date)),
            'amount' => str_replace('.', '', $request->get('amount_transfer')),
            'bank_id' => $request->get('bank_id')
        ];

        $confirmPayment = ConfirmPayment::create($arrSaved);
        $invoice->tracking = 2;
        $invoice->save();

        // simpan invoice tracking
        InvoiceTracking::create([
            'invoice_id' => $invoice->id,
            'tracking' => 2,
            'activity_date' => $confirmPayment->transfer_date
        ]);

        return redirect()->route('confirm-payment')->with( ['success' => 'Terimakasih sudah melakukan konfirmasi pembayaran. Silahkan tunggu proses selanjutnya dari admin']);
    }

    public function successTest()
    {
        $invoice = Invoice::whereId(16)->firstOrfail();
        return view('cart.success-test', compact('invoice'));
    }
}
