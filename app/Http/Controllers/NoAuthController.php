<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
use App\RajaOngkir;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Cart;
use Illuminate\Support\Facades\DB;
use Validator;
use Session;
use Mail;
use App\Mail\InvoiceMail;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Xendit\Xendit;


class NoAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware(['cartauth']);
    }

    public function index()
    {


        /*
        $invoice = Invoice::whereId(8)->first();
        Mail::to($invoice->user->email)
                ->send(new InvoiceMail($invoice));
        */
        if (Auth::check()) {
            $cartData = DB::table('shoppingcart')->where('identifier', auth()->user()->id)->first();
        } else {
            $cartData = Cart::content();
        }

        return view('cart.index', compact('cartData'));
    }

    public function addToCart($slug, Request $request)
    {

        $currentDateTime = Carbon::now('Asia/Jakarta')->format("Y-m-d H:i:s");
        $product = Product::selectRaw("id, code, title, slug, image, image_second, price, description, qty,
            (SELECT price FROM ".getTableName(with(new ProductDiscount)->getTable())." pd WHERE pd.product_id = ".getTableName(with(new Product)->getTable()).".id AND ((pd.date_start IS NULL OR pd.date_start <= '".$currentDateTime."') AND (pd.date_end IS NULL OR pd.date_end >= '".$currentDateTime."')) ORDER BY pd.priority ASC LIMIT 1 ) AS discount
            ")
            ->where('status',1)
            ->where('published_on','<=',$currentDateTime)
            ->where('slug',$slug)
            ->firstOrFail();

        if ($product->qty <= 0){
            return redirect()->route('shop.show', $product->slug)->with('error', __( 'main.out_of_stock' ) );
        }

        $this->validate($request, [
            'color_id' => 'required',
            'size_id' => 'required',
            'qty' => 'required'
        ]);

        $colorSizeQty = $product->colorSizeQtys()
            ->select('qty', 'color_id', 'size_id')
            ->where('color_id', $request->color_id)
            ->where('size_id', $request->size_id)
            ->firstOrFail();

        if ($colorSizeQty->qty == 0 ) {
            return redirect()->route('shop.show', $product->slug)->with('error', __( 'main.out_of_stock' ) );
        }

        $product->addToCart($request->qty, $colorSizeQty->size_id, $colorSizeQty->color_id);
        return redirect()->route('shop.show', $product->slug)->with('success', __( 'main.add_to_cart_save' ) );
    }

    public function destroy($id)
    {
        if (Auth::check()) {
            Cart::restore(auth()->user()->id);
            Cart::remove($id);
            Cart::store(auth()->user()->id);
        } else {
            Cart::remove($id);
        }

        return redirect()->route('cart')->with('success', __('main.delete_product') );
        //return redirect()->route('cart');
    }

    public function update(Request $request)
    {
        $qtys = $request->qty;
        $voucherCode = $request->voucher;
        $action = $request->action;

        $arrErrors = [];
        if ($action == 1){
            if (Cart::count() > 0){
                $currentDateTime = Carbon::now('Asia/Jakarta')->format("Y-m-d H:i:s");
                $arrSizes = Dropdown::getOptions('size');
                $arrColors = Color::whereStatus(1)->pluck('title', 'id')->toArray();

                foreach (Cart::content() as $id => $cart){
                    $newQty = $qtys[$id];
                    $product = Product::selectRaw("id, code, title, slug, image, image_second, price, description, qty,
                        (SELECT price FROM ".getTableName(with(new ProductDiscount)->getTable())." pd WHERE pd.product_id = ".getTableName(with(new Product)->getTable()).".id AND ((pd.date_start IS NULL OR pd.date_start <= '".$currentDateTime."') AND (pd.date_end IS NULL OR pd.date_end >= '".$currentDateTime."')) ORDER BY pd.priority ASC LIMIT 1 ) AS discount
                        ")
                        ->where('status',1)
                        ->where('published_on','<=',$currentDateTime)
                        ->where('id',$cart->id)
                        ->first();

                    if ($product){
                        // check stock
                        $sizeColorQty = $product->colorSizeQtys()
                            ->select('product_size_qties.qty')
                            ->leftJoin('dropdown_items', 'dropdown_items.id', '=', 'product_size_qties.size_id')
                            ->where('color_id', $cart->options->color)
                            ->where('size_id', $cart->options->size)
                            ->first();

                        if ($sizeColorQty && $sizeColorQty->qty >= $newQty){
                            if (Auth::check()) {
                                Cart::restore(auth()->user()->id);
                                Cart::update($id, $newQty); // Will update the quantity
                                Cart::store(auth()->user()->id);
                            } else {
                                Cart::update($id, $newQty); // Will update the quantity
                            }

                        } else {
                            $arrErrors[] = __('main.insufficient_stock_for_the_product').' '. $product->title.' ('.$arrColors[$cart->options->color].' '.$arrSizes[$cart->options->size].') ';
                        }
                    } else {
                        $arrErrors[] = __('main.product_not_found');
                    }
                }
            } else {
                $arrErrors[] = __('main.the_shopping_basket_is_still_empty');
            }
        }

        if (!is_null($voucherCode)){
            //$cartData = DB::table('shoppingcart')->where('identifier', auth()->user()->id)->first();
            $voucher = Voucher::where('code', $voucherCode)
                ->first();

            if ($voucher){
                if (is_null($voucher->activated_date)){
                    $dateNow = Carbon::now('Asia/Jakarta')->format("Y-m-d");
                    if ($dateNow >= $voucher->start_date){
                        if ($dateNow <= $voucher->end_date){
                            $arrVoucher = ['voucher_id' => $voucher->id, 'voucher_nominal'=>$voucher->nominal, 'voucher_code'=>$voucher->code];
                                session(['appVoucher' => $arrVoucher]);
                            /*
                            if (Auth::check()) {
                                DB::table('shoppingcart')
                                    ->where('identifier', auth()->user()->id)
                                    ->update(['voucher_id' => $voucher->id, 'voucher_nominal'=>$voucher->nominal, 'voucher_code'=>$voucher->code]);
                            } else {

                            }
                            */
                        } else {
                            $arrErrors[] = 'Voucher '. $voucherCode . ' '.__('main.is_not_valid');
                        }
                    } else {
                        $arrErrors[] = 'Voucher '. $voucherCode . ' '.__('main.cannot_be_used_yet');
                    }
                } else {
                    $arrErrors[] = 'Voucher '. $voucherCode . ' '.__('main.has_been_activated_on_the_date').' '.date('d/m/Y', strtotime($voucher->activated_date));
                }

            } else {
                $arrErrors[] = 'Voucher '. $voucherCode . ' '.__('main.not_found');
            }
        }

        //Cart::store(auth()->user()->id);
        $redirect = $action == 1 ? 'cart' : 'checkout';
        if (count($arrErrors)){
            return redirect()->route($redirect)->with( ['errors' => true, 'data'=>$arrErrors]);
        } else {
            $text = $action == 1 ? __('main.cart') : 'Voucher';
            return redirect()->route($redirect)->with('success', $text.' '.__('main.has_been_successfully_updated') );
        }
    }

    public function voucherUpdate(Request $request)
    {
        $voucherCode = $request->voucher;

        $arrErrors = [];
        if (!is_null($voucherCode)){
            //$cartData = DB::table('shoppingcart')->where('identifier', auth()->user()->id)->first();
            $voucher = Voucher::where('code', $voucherCode)
                ->first();

            if ($voucher){
                if (is_null($voucher->activated_date)){
                    $dateNow = Carbon::now('Asia/Jakarta')->format("Y-m-d");
                    if ($dateNow >= $voucher->start_date){
                        if ($dateNow <= $voucher->end_date){
                            if (Auth::check()) {
                                DB::table('shoppingcart')
                                    ->where('identifier', auth()->user()->id)
                                    ->update(['voucher_id' => $voucher->id, 'voucher_nominal'=>$voucher->nominal, 'voucher_code'=>$voucher->code]);
                            } else {
                                Cart::update($id, $newQty); // Will update the quantity
                            }


                        } else {
                            $arrErrors[] = 'Voucher '. $voucherCode . ' '.__('main.is_not_valid');
                        }
                    } else {
                        $arrErrors[] = 'Voucher '. $voucherCode . ' '.__('main.cannot_be_used_yet');
                    }
                } else {
                    $arrErrors[] = 'Voucher '. $voucherCode . ' '.__('main.has_been_activated_on_the_date').' '.date('d/m/Y', strtotime($voucher->activated_date));
                }

            } else {
                $arrErrors[] = 'Voucher '. $voucherCode . ' '.__('main.not_found');
            }
        }

        if(count($arrErrors)>0):
            return redirect()->route('checkout')->with(['errors'=>true, 'data'=>$arrErrors] );
        else:
            return redirect()->route('checkout')->with('success', __('main.has_been_successfully_updated') );
        endif;
    }

    public function checkout(Request $request)
    {
        if (!Cart::count()){
            return redirect()->route('cart')->with( ['error' => __('main.the_shopping_basket_is_still_empty')]);
        }

        if (Auth::check()) {
            $billing_id = (int) $request->billing_id;
            $billings = auth()->user()->billings;

            $billingSelected = false;
            if (!empty($billing_id)){
                $billingSelected = auth()->user()->billings()->whereId($billing_id)->firstOrFail();
            } else {
                if (count($billings)){
                    $billingSelected = auth()->user()->billings()->where('is_main', 1)->firstOrFail();
                }
            }

            if ($billingSelected){
                if (!is_null($billingSelected->country_id)){
                    if (is_null($billingSelected->country_name)){
                        $countryData = $billingSelected->rajaOngkirCountry($billingSelected->country_id);
                        $billingSelected->country_name = $countryData->country_name;
                    }
                }

                if (is_null($billingSelected->country_id) && is_null($billingSelected->province_name)){
                    $provinceData = $billingSelected->rajaOngkir();
                    $billingSelected->province_name = $provinceData->province;
                    $billingSelected->city_name = $provinceData->city;
                    $billingSelected->subdistrict_name = $provinceData->subdistrict_name;
                    $billingSelected->type_name = $provinceData->type;
                }

                $billingSelected->save();
            }

            //stdClass Object ( [subdistrict_id] => 1806 [province_id] => 7 [province] => Gorontalo [city_id] => 131 [city] => Gorontalo Utara [type] => Kabupaten [subdistrict_name] => Sumalata )

            $cartData = DB::table('shoppingcart')->where('identifier', auth()->user()->id)->first();
        } else {
            $billings = [];
            $billingSelected = false;
            $cartData = Cart::content();
        }

        return view('cart.checkout', compact('cartData', 'billings', 'billingSelected'));
    }

    private function arrNiceName()
    {
        return array(
            'name_guest' => __('main.name'),
            'country_id_guest' => __('main.country'),
            'province_id_guest' => __('main.province'),
            'city_id_guest' => __('main.city'),
            'subdistrict_id_guest' => __('main.sub_district'),
            'address_guest' => __('main.address'),
            'postcode_guest' => __('main.postal_code'),
            'handphone_guest' => __('main.phone_number'),
            'billing' => __('main.data_shipping_address'),
            'weight' => __('main.weight'),
            'courier' => __('main.courier'),
        );
    }

    public function storeCheckout(Request $request)
    {
        $arrValidates = [
            'weight' => 'required',
            'courier' => 'required',
            //'ordernote'
        ];

        if (auth()->check()){
            $arrValidates['billing'] = 'required';
        } else {
            $arrValidates['name_guest'] = 'required';
            $arrValidates['country_id_guest'] = 'required';
            $arrValidates['province_id_guest'] = 'required';
            $arrValidates['subdistrict_id_guest'] = 'required';
            $arrValidates['address_guest'] = 'required';
            $arrValidates['postcode_guest'] = 'required';
            $arrValidates['handphone_guest'] = 'required';
        }

        Validator::make($request->all(), $arrValidates, [], $this->arrNiceName())->validate();

        $arrColors = Color::whereStatus(1)->pluck('title', 'id')->toArray();
        $arrSizes = Dropdown::getOptions('size');

        // cek stok lagi
        $currentDateTime = Carbon::now('Asia/Jakarta')->format("Y-m-d H:i:s");
        $arrErrors = [];
        $subTotal = 0;
        $totalWeight = 0;
        $arrProduct = [];

        if (auth()->check()){
            $cartData = DB::table('shoppingcart')->where('identifier', auth()->user()->id)->first();
        } else {
            $cartData = Cart::content();
        }

        //$voucher = false;
        $voucherSession = currentVoucher();
        if ($voucherSession['voucher_id'] != ''){
            $voucher = Voucher::where('id', $voucherSession['voucher_id'])->first();
            if ($voucher){
                if (is_null($voucher->activated_date)){
                    $dateNow = Carbon::now('Asia/Jakarta')->format("Y-m-d");
                    if ($dateNow >= $voucher->start_date){
                        if ($dateNow > $voucher->end_date){
                            $arrErrors[] = 'Voucher '. $voucher->code . ' '.__('main.is_not_valid');
                        }
                    } else {
                        $arrErrors[] = 'Voucher '. $voucher->code . ' '.__('main.cannot_be_used_yet');
                    }
                } else {
                    $arrErrors[] = 'Voucher '. $voucher->code . ' '.__('main.has_been_activated_on_the_date').' '.date('d/m/Y', strtotime($voucher->activated_date));
                }

            } else {
                $arrErrors[] = 'Voucher '. $voucher->code . ' '.__('main.not_found');
            }
        }

        if (count($arrErrors)){
            return redirect()->route('checkout')->with(['errors'=>true, 'data'=>$arrErrors] );
        }

        foreach (Cart::content() as $id => $cart){
            $product = Product::selectRaw("id, weight, code, title, slug, image, image_second, price, description, qty,
                (SELECT price FROM ".getTableName(with(new ProductDiscount)->getTable())." pd WHERE pd.product_id = ".getTableName(with(new Product)->getTable()).".id AND ((pd.date_start IS NULL OR pd.date_start <= '".$currentDateTime."') AND (pd.date_end IS NULL OR pd.date_end >= '".$currentDateTime."')) ORDER BY pd.priority ASC LIMIT 1 ) AS discount
                ")
                ->where('status',1)
                ->where('published_on','<=',$currentDateTime)
                ->where('id',$cart->id)
                ->first();

            if ($product){
                // check stock
                $sizeColorQty = $product->colorSizeQtys()
                    ->select('product_size_qties.qty')
                    ->leftJoin('dropdown_items', 'dropdown_items.id', '=', 'product_size_qties.size_id')
                    ->where('color_id', $cart->options->color)
                    ->where('size_id', $cart->options->size)
                    ->first();

                if ($sizeColorQty && $sizeColorQty->qty >= $cart->qty){
                    $price = $product->price;
                    if ($product->discount > 0){
                        $price = $product->discount;
                    }

                    $total = $price * $cart->qty;
                    $subTotal = $subTotal + $total;
                    $totalWeight = $totalWeight + ($cart->qty * $product->weight);

                    $arrProduct[] = [
                        'product_id' => $product->id,
                        'color_id' => $cart->options->color,
                        'size_id' => $cart->options->size,
                        'qty' => $cart->qty,
                        'price' => $price,
                        'colorSizeQty' => $sizeColorQty,
                        'product' => $product
                    ];
                } else {
                    $arrErrors[] = __('main.insufficient_stock_for_the_product').' '. $product->title.' ('.$arrColors[$cart->options->color].' '.$arrSizes[$cart->options->size].') ';
                }
            } else {
                $arrErrors[] = __('main.product_not_found');
            }
        }


        if (count($arrErrors)){
            return redirect()->route('cart')->with(['errors'=>true, 'data'=>$arrErrors] );
        }

        if (auth()->check()){
            $billing = auth()->user()->billings()->whereId($request->billing)->firstOrFail();
        } else {
            $country_id_guest = $request->get('country_id_guest');
            $country_name = 'Indonesia';
            if (!empty($country_id_guest)){
                $countryData = RajaOngkir::country($country_id_guest);
                $country_name = $countryData->country_name;
            }

            if (empty($country_id_guest)){
                $provinceData = RajaOngkir::detail($request->get('city_id_guest'), $request->get('subdistrict_id_guest'));
                $province_name = $provinceData->province;
                $city_name = $provinceData->city;
                $subdistrict_name = $provinceData->subdistrict_name;
                $type_name = $provinceData->type;
            }
            $billing = UserBilling::create([
                'name' => $request->get('name_guest'),
                'country_id' => $country_id_guest == 0 ? null : $country_id_guest,
                'country_name' => $country_name,
                'province_id' => $request->get('province_id_guest'),
                'province_name' => $province_name,
                'city_id' => $request->get('city_id_guest'),
                'city_name' => $city_name,
                'subdistrict_id' => $request->get('subdistrict_id_guest'),
                'subdistrict_name' => $subdistrict_name,
                'type_name' => $type_name,
                'address' => $request->get('address_guest'),
                'postcode' => $request->get('postcode_guest'),
                'handphone' => $request->get('handphone_guest'),
                'email' => $request->get('email_guest'),
                'is_main' => 1
            ]);
        }

        $spliceOngkir = explode(';', $request->courier);
        $invoice_date = Carbon::now('Asia/Jakarta')->format("Y-m-d H:i:s");
        //$unique_code = Invoice::generateUniqueCode();
        $unique_code = 0;

        $dataInvoice = Invoice::generateInvoiceNumber();
        $arrSave = [
            'invoice_number' => $dataInvoice['invoice_number'],
            'sort_order' => $dataInvoice['sort_order'],
            'invoice_period' => Carbon::now('Asia/Jakarta')->format("Y-m-d"),
            'invoice_date' => $invoice_date,
            'invoice_due_date' => (new Carbon($invoice_date))->addHours(config('app.expired_hours'))->format('Y-m-d H:i:s'),
            'user_id' => auth()->check() ? auth()->user()->id : null,
            'courier' => $spliceOngkir[0],
            'courier_name' => $spliceOngkir[9],
            'courier_service' => $spliceOngkir[1],
            'courier_service_description' => $spliceOngkir[2],
            'destination_etd' => $spliceOngkir[3],
            'destination_origin_id' => $spliceOngkir[5],
            'destination_country_id' => $billing->country_id,
            'destination_city_id' => $spliceOngkir[8],
            'destination_destination_id' => $spliceOngkir[6],
            'destination_destination_type' => $spliceOngkir[7],
            'total_shipping_charge' => $spliceOngkir[4],
            'voucher_id' => $voucherSession['voucher_id'] != '' ? $voucherSession['voucher_id'] : null,
            'voucher_code' => $voucherSession['voucher_id'] != '' ? $voucherSession['voucher_code'] : null,
            'voucher_nominal' => $voucherSession['voucher_id'] != '' ? $voucherSession['voucher_nominal'] : null,
            'total_weight' => $totalWeight,
            'total_order' => $subTotal,
            'unique_code' => $unique_code,
            'grand_total' => $subTotal + $spliceOngkir[4] - (int) $voucherSession['voucher_nominal'] + $unique_code,
            'status_invoice' => 1,
            'status_payment' => 0,
            'description' => $request->ordernote,
            'idr_rate' => \App\Setting::getValue('dollar'),
            'language' => LaravelLocalization::getCurrentLocale(),
            'currency' => currentCurrency()
        ];

        $invoice = Invoice::create($arrSave);

        // simpan invoice billing
        InvoiceBilling::create([
            'invoice_id' => $invoice->id,
            'name' => $billing->name,
            'country_id' => $billing->country_id,
            'country_name' => $billing->country_name,
            'province_id' => $billing->province_id,
            'province_name' => $billing->province_name,
            'city_id' => $billing->city_id,
            'city_name' => $billing->city_name,
            'subdistrict_id' => $billing->subdistrict_id,
            'subdistrict_name' => $billing->subdistrict_name,
            'type_name' => $billing->type_name,
            'address' => $billing->address,
            'postcode' => $billing->postcode,
            'handphone' => $billing->handphone
        ]);

        // simpan invoice tracking
        InvoiceTracking::create([
            'invoice_id' => $invoice->id,
            'tracking' => 1,
            'activity_date' => Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s')
        ]);

        // aktifkan voucher
        if ($voucherSession['voucher_id'] != '' && $voucher){
            $voucher->activated_date = $invoice->invoice_date;
            $voucher->user_id = $invoice->user_id;
            $voucher->save();

            $arrVoucher = ['voucher_id' => '', 'voucher_nominal'=>'', 'voucher_code'=>''];
            session(['appVoucher' => $arrVoucher]);

            /*
            DB::table('shoppingcart')
                ->where('identifier', auth()->user()->id)
                ->update(['voucher_id' => null, 'voucher_nominal'=>null, 'voucher_code'=>null]);
            */
        }

        $arrInsert = [];

        // simpan invoice produk
        foreach ($arrProduct as $productData){
            $arrInsert['InvoiceProduct'][] = [
                'invoice_id' => $invoice->id,
                'product_id' => $productData['product_id'],
                'color_id' => $productData['color_id'],
                'size_id' => $productData['size_id'],
                'qty' => $productData['qty'],
                'price' => $productData['price']
            ];
        }
        InvoiceProduct::insert($arrInsert['InvoiceProduct']);

        // simpan invoice produk booked
        foreach ($arrProduct as $productData){
            $arrInsert['InvoiceProductBooked'][] = [
                'invoice_id' => $invoice->id,
                'product_id' => $productData['product_id'],
                'color_id' => $productData['color_id'],
                'size_id' => $productData['size_id'],
                'qty' => $productData['qty']
            ];
        }
        InvoiceProductBooked::insert($arrInsert['InvoiceProductBooked']);

        // update stock produk color size
        foreach ($arrProduct as $productData){
            $newProductColorSizeQty = ProductSizeQty::where('product_id', $productData['product_id'])->where('color_id', $productData['color_id'])->where('size_id', $productData['size_id'])->first();
            $newProductColorSizeQty->qty = $newProductColorSizeQty->qty - $productData['qty'];
            $newProductColorSizeQty->save();
        }

        // update stok produk
        foreach ($arrProduct as $productData){
            $newProduct = Product::whereId($productData['product_id'])->first();
            $newProduct->qty = $newProduct->qty - $productData['qty'];
            $newProduct->save();
        }

        // tambah kartu stok produk
        foreach ($arrProduct as $productData){
            $arrInsert['ProductStokActivity'][] = [
                'tanggal' => $invoice->invoice_date,
                'product_id' => $productData['product_id'],
                'color_id' => $productData['color_id'],
                'size_id' => $productData['size_id'],
                'qty' => $productData['qty'],
                'jenis' => 3,
                'id_terkait' => $invoice->id
            ];
        }
        ProductStokActivity::insert($arrInsert['ProductStokActivity']);

        // clear shopping cart
        if (auth()->check()){
            Cart::restore(auth()->user()->id);
        }
        Cart::destroy();
        if (auth()->check()){
            Cart::store(auth()->user()->id);
        }

        $invoice = Invoice::whereId($invoice->id)->first();

        $email = auth()->check() ? $invoice->user->email : $billing->email;
        $user = !is_null($invoice->user_id) ? $invoice->user : null;
        $billingSelected = $invoice->billing;

        // XENDIT
        Xendit::setApiKey(config('app.xendit_api_key'));
        $params = [
            'external_id' => $invoice->invoice_number,
            'payer_email' => $email,
            'description' => 'Khanaan #'.$invoice->invoice_number,
            'amount' => $invoice->grand_total,
            'customer' => [
                'given_names' => $invoice->billing->name,
                'email' => $email,
                'address' => $billingSelected->address
            ],
            'invoice_duration' => (config('app.expired_hours') * 60) * 60,
            'success_redirect_url' => config('app.url') . '/' . LaravelLocalization::getCurrentLocale() . '/purchase',
            //'currency' => strtoupper($invoice->currency)
            'currency' => 'IDR'
        ];

        $xendit = \Xendit\Invoice::create($params);

        if (count($xendit)){
            $invoice->xendit_url = $xendit['invoice_url'];
            $invoice->save();
        }

        // email
        Mail::to($email)
                ->send(new InvoiceMail($invoice));

        return redirect()->route('cart.success')
            ->with( ['success' => 'Order Sukses', 'invoice'=>$invoice->id] );
    }

    public function successPage()
    {
        $invoice_id = Session::get('invoice');
        $invoice = Invoice::where('id', $invoice_id)->where('status_invoice', 1)->where('tracking', 1)->firstOrFail();

        return view('cart.success', compact('invoice'));
    }
}
