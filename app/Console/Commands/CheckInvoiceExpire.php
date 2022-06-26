<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Invoice;
use App\ProductSizeQty;
use App\Product;
use App\ProductStokActivity;
use Log;
use Mail;
use App\Mail\InvoiceExpiredMail;

class CheckInvoiceExpire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CheckInvoiceExpire:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengecek invoice yang akan melewati due date 1 jam lagi';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dt_now = Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s');
        //$dt_now = '2020-01-31 14:00:00';

        //Log::error($e->getMessage());

        $invoices = Invoice::where('status_invoice',1)
            ->where('status_payment', 0)
            ->where("invoice_due_date",'<=',$dt_now)
            ->get();

        if (count($invoices)){
            foreach ($invoices as $invoice){
                if (strtotime($dt_now) >= strtotime($invoice->invoice_due_date)){
                    $invoice->status_invoice = 0;
                    $invoice->save();

                    // kembalikan stok
                    $products = $invoice->products;

                    $productStokActivity = [];
                    foreach ($products as $product){
                        // update stock produk color size
                        $newProductColorSizeQty = ProductSizeQty::where('product_id', $product->product_id)->where('color_id', $product->color_id)->where('size_id', $product->size_id)->first();
                        $newProductColorSizeQty->qty = $newProductColorSizeQty->qty + $product->qty;
                        $newProductColorSizeQty->save();

                        // update stok produk
                        $newProduct = Product::whereId($product->product_id)->first();
                        $newProduct->qty = $newProduct->qty + $product->qty;
                        $newProduct->save();

                        // tambah kartu stok produk
                        $productStokActivity = [
                            'tanggal' => $invoice->invoice_date,
                            'product_id' => $product->product_id,
                            'color_id' => $product->color_id,
                            'size_id' => $product->size_id,
                            'qty' => $product->qty,
                            'jenis' => 4,
                            'id_terkait' => $invoice->id
                        ];
                    }

                    if ($productStokActivity){
                        ProductStokActivity::insert($productStokActivity);
                    }

                    $invoice->product_bookeds()->delete();

                    // send email
                    Mail::to($invoice->user->email)
                            ->send(new InvoiceExpiredMail($invoice));
                }

            }

        }
        //Log::info('END CheckInvoiceExpire');
    }
}
