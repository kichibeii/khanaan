<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\InvoicePayment;

class Invoice extends Model
{
    protected $guarded = [
        'id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function products()
    {
        return $this->hasMany('App\InvoiceProduct');
    }

    public function product_bookeds()
    {
        return $this->hasMany('App\InvoiceProductBooked');
    }

    public function billing()
    {
        return $this->hasOne('App\InvoiceBilling');
    }

    public static function generateInvoiceNumber()
    {
        $dateNow = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        $idOrder = 1;
        $lastOrder = Invoice::select('sort_order')
            ->where('status_invoice', '>=', 0)
            ->whereYear('invoice_period', date('Y', strtotime($dateNow)));

        $lastOrder = $lastOrder->orderBy('sort_order', 'desc')->first();

        if ($lastOrder) {
            $idOrder = $lastOrder->sort_order + 1;
        }

        return [
            'invoice_number' => date('y', strtotime($dateNow)).generate_numbers($idOrder, 1, 5),
            'sort_order' => $idOrder
        ];
    }


    public static function generateUniqueCode()
    {
        $maxUniqueNumber = 999;

        $uniqueId = self::select('unique_code')->where('status_invoice', '>=', 0);
        $uniqueData = $uniqueId->orderBy('id', 'DESC')->first();

        $unique = 1;
        if ($uniqueData){
            $unique = $uniqueData->unique_code + 1;
        }

        if ($unique > $maxUniqueNumber){
            $unique = 1;
        }
        $unique = 0;
        return $unique;
    }

    public function resiRajaOngkir(){
        if($this->nomor_resi):
            $res = RajaOngkir::waybill($this->nomor_resi, $this->courier);
            return $res;
        else:
            return [];
        endif;
    }

    public function paymentProcess($utils)
    {

        $approve_at = $utils['approve_at'];
        $approve_from = $utils['approve_from'];
        $amount = $utils['amount'];
        $userId = isset($utils['userId']) ? $utils['userId'] : null;
        $status = $utils['status'];
        $payment_method = $utils['payment_method'];
        $merchant_name = $utils['merchant_name'];
        $bank_code = $utils['bank_code'];
        $description = $utils['description'];
        $payment_channel = $utils['payment_channel'];

        //paymentProcess($paid_status, $kekurangan, $request->approve_at, $request->approve_from, $amount, $userLoged->id, $client, $kelebihan)

        $this->status_payment = 1;
        $this->save();

        InvoicePayment::create([
            'invoice_id' => $this->id,
            'payment_date' => date('Y-m-d H:i:s', strtotime($approve_at)),
            'method' => $approve_from, // xendit
            'total' => $amount,
            'user_id' => $userId,
            'status' => $status,
            'payment_method' => $payment_method,
            'merchant_name' => $merchant_name,
            'bank_code' => $bank_code,
            'description' => $description,
            'payment_channel' => $payment_channel
        ]);

    }
}
