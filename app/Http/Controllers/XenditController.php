<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Invoice;
use App\InvoiceTracking;

use Log;
use Carbon\Carbon;

class XenditController extends Controller
{
    public function index(Request $request)
    {
        $dt_now = Carbon::now('Asia/Jakarta')->format("Y-m-d");
        //$api_token = Setting::getValue('mutasibank_api_token');
        //$req = json_decode(file_get_contents('php://input'), true);
        $req = json_decode($request->getContent(), true);

        Log::info($req);

        $invoice = Invoice::where('invoice_number', $req['external_id'])
            ->where('status_invoice', 1)
            ->where('status_payment', 0)
            ->first();

        if ($invoice){

            $invoice->paymentProcess([
                'approve_at' => $req['paid_at'],
                'approve_from' => 'xendit',
                'amount' => $req['paid_amount'],
                'status' => $req['status'],
                'payment_method' => $req['payment_method'],
                'merchant_name' => $req['merchant_name'],
                'bank_code' => $req['payment_method'] == 'BANK_TRANSFER' ? $req['bank_code'] : $req['payment_channel'],
                'description' => $req['description'],
                'payment_channel' => $req['payment_channel']
            ]);
            echo 'Transaksi berhasil';
            Log::info("Transaksi berhasil");

            $invoice->tracking = 3;
            $invoice->save();

            // simpan invoice tracking
            InvoiceTracking::create([
                'invoice_id' => $invoice->id,
                'tracking' => 3,
                'activity_date' => Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s')
            ]);

            $invoice->product_bookeds()->delete();
            exit;
        } else {
            echo 'Invoice tidak ditemukan';
            Log::error("Invoice tidak ditemukan");
            exit;
        }


    }
}
