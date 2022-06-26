<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Slideshow;
use App\Banner;
use App\Product;
use App\Article;
use App\ConfirmPayment;
use Carbon\Carbon;
use Validator;
use Illuminate\Support\Facades\Redirect;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Xendit\Xendit;

class HomeController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['cartauth']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $utils['slideshows'] = Slideshow::select('title', 'title_id', 'url', 'target', 'image', 'logo')
            ->where('status', 1)
            ->orderBy('sort_order', 'ASC')
            ->limit(5)
            ->get();

        $utils['arrLogo'] = Slideshow::arrLogo();

        $utils['banners'] = Banner::select('title', 'title_id', 'url', 'target', 'image', 'banner_type')
            ->orderBy('banner_type', 'ASC')
            ->get();

        $utils['products']['newReleases'] = Product::getProductList(false, 'new-arrivals');
        $utils['products']['sales'] = Product::getProductList(false, 'sale');
        $utils['products']['bestSeller'] = Product::getProductList(false, 'best-seller');

        $utils['articles'] = Article::select('title','title_id', 'slug', 'preview','preview_id', 'image', 'published_on')
            ->where('status',1)
            ->where('published_on','<=', Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s'))
            ->orderBy('published_on', 'DESC')
            ->limit(3)
            ->get();

        return view('home', compact('utils'));
    }

    public function setCurrency(Request $request)
    {
        session(['appcurrency' => $request->currency]);

        return redirect($request->url);
    }

    public function xendit()
    {
        return config('app.url') . '/' . LaravelLocalization::getCurrentLocale() . '/purchase';
        Xendit::setApiKey(config('app.xendit_api_key'));

        /*
        $getBalance = \Xendit\Balance::getBalance('CASH');
        return $getBalance;
        */

        $params = [
            'external_id' => '12312414',
            'payer_email' => 'heri1845@gmail.com',
            'description' => 'Khanaan #12312414',
            'amount' => 8000000,
            'customer' => [
                'given_names' => 'Heri Herliana',
                'email' => 'heri1845@gmail.com',
                'mobile_number' => '+628123123123',
                'address' => 'Jl. Abc 123'
            ],
            'invoice_duration' => (config('app.expired_hours') * 60 * 60),
            'success_redirect_url' => config('app.url') . '/' . LaravelLocalization::getCurrentLocale() . '/purchase',
            'currency' => 'IDR',
            /*
            'items' => [{
                {"name":"Produk A", "quantity":1, "price":1000000},
                {"name":"Produk B", "quantity":2, "price":500000},
                {"name":"Produk C", "quantity":4, "price":250000},
            }]
            ,
            'fees' => [
                {"type":"Ongkos Kirim", "value":100000},
            ]
            */
        ];

        $invoice = \Xendit\Invoice::create($params);
        echo '<pre>';
        print_r($invoice);
        echo '</pre>';
        exit;
        //var_dump($createInvoice);
        return $createInvoice;

        /*
        $id = $createInvoice['id'];

        $getInvoice = \Xendit\Invoice::retrieve($id);
        var_dump($getInvoice);

        $expireInvoice = \Xendit\Invoice::expireInvoice($id);
        var_dump($expireInvoice);

        $getAllInvoice = \Xendit\Invoice::retrieveAll();
        var_dump(($getAllInvoice));
        */
    }

}
