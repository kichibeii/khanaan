<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Product;
use App\ProductImage;
use App\ProductColor;
use App\ProductColorSize;
use App\ProductSizeQty;
use App\ProductStokActivity;
use App\ProductStokAwal;
use App\ProductDiscount;
use App\ProductNewReleases;
use App\ProductHot;
use App\ProductAdditional;
use App\ProductAdditionalItem;
use App\StockOpname;
use App\StockOpnameItem;
use App\Tag;
use App\Dropdown;
use App\DropdownItem;
use App\Color;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Str;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    private $controller = 'product';
    private $description = '';
    private $icon = 'flaticon2-paper';

    public function __construct()
    {
        $this->middleware('auth');
    }


    private function title()
    {
        return __('main.'.$this->controller);
    }

    private function currentDate()
    {
        return Carbon::now('Asia/Jakarta')->format("Y-m-d");
    }

    private function arrNiceName()
    {
        return array(
            'code' => 'Kode ' . $this->title(),
            'title' => 'Nama ' . $this->title(),
            'brand_id' => __('main.brand'),
            'description' => 'Deskripsi',
            'description_id' => 'Deskripsi (ID)',
            'price' => 'Harga',
            'weight' => 'Berat',
            'image' => 'Foto Produk',
            'upload' => 'Gambar',
            'colors' => 'Warna',
            'size' => 'Ukuran',
            'images' => 'Foto Produk'
        );
    }

    private function arrValidate($action="create", $id=0)
    {
        $arrValidates = [
            'description' => 'required',
            'description_id' => 'required',
            'price' => 'required',
            'weight' => 'required',
            'published_on' => 'required',
            'categories' => 'required|array',
        ];

        if (!empty($id)){
            $arrValidates['title'] = 'required|min:3|unique:products,title,'.$id;
            //$arrValidates['slug'] = 'required|min:3|unique:products,slug,'.$id;
        } else {
            $arrValidates['title'] = 'required|min:3|unique:products,title';
            $arrValidates['images'] = 'required|array';
            $arrValidates['colors'] = 'required|array';
            $arrValidates['size'] = 'required|array';
            $arrValidates['image'] = 'required|array';
            //$arrValidates['slug'] = 'required|min:3|unique:products,slug';
        }

        return $arrValidates;
    }

    private function arrSaved($request, $action="create")
    {
        $arrSaved = [
            'code'      => $request->get('code'),
            'title'      => $request->get('title'),
            'slug'  => $request->slug,
            'brand_id'      => $request->get('brand_id'),
            'size_id'      => $request->get('size_id'),
            'description'      => $request->get('description'),
            'description_id'      => $request->get('description_id'),
            'price'      => str_replace('.', '', $request->get('price')),
            'weight'      => str_replace('.', '', $request->get('weight')),
            'image'  => $request->image_first,
            'image_second'  => $request->image_second,
            'published_on'      => date('Y-m-d H:i:s', strtotime($request->get('published_on'))),
            'status'     => $request->get('status'),
            'size_id'  => $request->size_id,
        ];

        if ($action == 'create'){
            $arrSaved['qty'] = 0;
        }

        return $arrSaved;
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

        return view('admin.'.$this->controller.'.index')->with(array('controller' => $this->controller, 'title' => $this->title(), 'description' => $this->description, 'icon' => $this->icon));//
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-create')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );
        }

        $utils['action'] = __('main.add_new');
        $utils['options']['categories'] = Dropdown::getOptions('productcategory');
        $utils['options']['collections'] = Dropdown::getOptions('productcollection');
        $utils['options']['size'] = Dropdown::getOptions('size');
        $utils['options']['colors'] = Color::getData();
        $utils['options']['colorSelected'] = old('color_checked') != null ? explode(',', old('color_checked')) : [];
        $utils['options']['categoriesSelected'] = old('categories') != null ? old('categories') : [];
        $utils['options']['collectionsSelected'] = old('collections') != null ? old('collections') : [];
        $utils['options']['arrColorSizeQty'] = [];
        $utils['options']['arrColorImage'] = [];

        return view('admin.'.$this->controller.'.create', compact('utils'))->with(array('controller' => $this->controller, 'title' => $this->title(), 'description' => $this->description, 'icon' => $this->icon));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-create')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );
        }

        $arrValidates = $this->arrValidate();

        Validator::make($request->all(), $arrValidates, [], $this->arrNiceName())->validate();



        $request->slug = Str::slug($request->get('title'), '-');
        $mainImages = $request->image;
        $request->image_first = $mainImages[1];
        $request->image_second = $mainImages[2];

        $product = Product::create($this->arrSaved($request));

        $fromPath = 'images/products/temps/';
        $destinationPath = 'images/products/'.$product->id.'/';

        // move main & second image
        Storage::disk('public')->move($fromPath.$product->image, $destinationPath.$product->image);
        Storage::disk('public')->move($fromPath.$product->image_second, $destinationPath.$product->image_second);

        $collections = $request->get('collections') !== null && $request->get('collections') > 0 ? $request->get('collections') : [];
        $product->categories()->sync(array_merge($request->get('categories'), $collections));
        $product->saveTags(Tag::setTag($request->get('tags'), $product->title));

        $arrProductImages = [];
        $totalQty = 0;
        $arrSave = [];
        $arrSave['ProductImage'] = [];
        $arrSave['ProductColor'] = [];
        $arrSave['ProductColorSize'] = [];
        $arrSave['ProductSizeQty'] = [];
        $arrSave['ProductStokActivity'] = [];

        $colors = $request->get('colors');
        $sizes = $request->get('size');
        $images = $request->images;

        foreach ($colors as $indexColor => $color_id){
            $arrSave['ProductColor'][] = [
                'product_id' => $product->id,
                'color_id' => $color_id,
                'sort_order' => $indexColor+1
            ];

            /* IMAGE */
            // copy image to real directory

            if (count($images[$color_id])){
                foreach ($images[$color_id] as $indexImage => $fileName){
                    if (Storage::disk('public')->exists($fromPath.$fileName)){
                        $arrSave['ProductImage'][] = [
                            'product_id' => $product->id,
                            'color_id' => $color_id,
                            'image' => $fileName,
                            'sort_order' => $indexImage + 1
                        ];
                        Storage::disk('public')->move($fromPath.$fileName, $destinationPath.$fileName);
                    }
                }
            }

            /* SIZE */
            if (count($sizes[$color_id])){
                foreach ($sizes[$color_id] as $size_id => $sizeQty){
                    $arrSave['ProductColorSize'][] = [
                        'product_id' => $product->id,
                        'color_id' => $color_id,
                        'size_id' => $size_id
                    ];

                    $arrSave['ProductSizeQty'][] = [
                        'product_id' => $product->id,
                        'color_id' => $color_id,
                        'size_id' => $size_id,
                        'qty' => $sizeQty
                    ];

                    $productStokAwal = ProductStokAwal::create([
                        'product_id' => $product->id,
                        'color_id' => $color_id,
                        'size_id' => $size_id,
                        'qty' => $sizeQty
                    ]);

                    $arrSave['ProductStokActivity'][] = [
                        'tanggal' => $this->currentDate(),
                        'product_id' => $product->id,
                        'color_id' => $color_id,
                        'size_id' => $size_id,
                        'qty' => $sizeQty,
                        'jenis' => 1,
                        'id_terkait' => $productStokAwal->id
                    ];

                    $totalQty = $totalQty + $sizeQty;
                }
            }
        }

        ProductColor::insert($arrSave['ProductColor']);
        ProductImage::insert($arrSave['ProductImage']);
        ProductColorSize::insert($arrSave['ProductColorSize']);
        ProductSizeQty::insert($arrSave['ProductSizeQty']);
        ProductStokActivity::insert($arrSave['ProductStokActivity']);

        $product->qty = $totalQty;
        $product->save();

        /* START DISCOUNT */
        $discounts = $request->get('discounts');
        if (count($discounts['price'])) {
            $discount_date_start = $discounts['date_start'];
            $discount_date_end = $discounts['date_end'];
            $discount_price = $discounts['price'];
            $discount_priority = $discounts['priority'];

            $arrSave['ProductDiscount'] = [];
            foreach ($discount_price as $k => $price) {
                if ($price != '' || $discount_priority[$k] != '' || $discount_date_start[$k] != '') {
                    $arrSave['ProductDiscount'][] = [
                        'product_id' => $product->id,
                        'date_start' => date('Y-m-d H:i:s', strtotime($discount_date_start[$k])),
                        'date_end' => $discount_date_end[$k] != '' ? date('Y-m-d H:i:s', strtotime($discount_date_end[$k])) : null,
                        'price' => str_replace('.', '', $price),
                        'priority' => (int) $discount_priority[$k]
                    ];
                }
            }

            ProductDiscount::insert($arrSave['ProductDiscount']);
        }
        /* END DISCOUNT */

        /* START NEW RELEASES */
        $new_releases = $request->get('new_releases');
        if (count($new_releases['date_start'])) {
            $new_release_date_start = $new_releases['date_start'];
            $new_release_date_end = $new_releases['date_end'];
            $new_release_priority = $new_releases['priority'];

            $arrSave['ProductNewReleases'] = [];
            foreach ($new_release_date_start as $k => $date_start) {
                if ($date_start != '' || $new_release_priority[$k] != '') {
                    $arrSave['ProductNewReleases'][] = [
                        'product_id' => $product->id,
                        'date_start' => date('Y-m-d H:i:s', strtotime($date_start)),
                        'date_end' => $new_release_date_end[$k] != '' ? date('Y-m-d H:i:s', strtotime($new_release_date_end[$k])) : null,
                        'priority' => (int) $new_release_priority[$k]
                    ];
                }
            }

            ProductNewReleases::insert($arrSave['ProductNewReleases']);
        }
        /* END NEW RELEASES */

        /* START BEST SELLER / HOT */
        $best_sellers = $request->get('best_sellers');
        if (count($best_sellers['date_start'])) {
            $best_seller_date_start = $best_sellers['date_start'];
            $best_seller_date_end = $best_sellers['date_end'];
            $best_seller_priority = $best_sellers['priority'];

            $arrSave['ProductHot'] = [];
            foreach ($best_seller_date_start as $k => $date_start) {
                if ($date_start != '' || $best_seller_priority[$k] != '') {
                    $arrSave['ProductHot'][] = [
                        'product_id' => $product->id,
                        'date_start' => date('Y-m-d H:i:s', strtotime($date_start)),
                        'date_end' => $best_seller_date_end[$k] != '' ? date('Y-m-d H:i:s', strtotime($best_seller_date_end[$k])) : null,
                        'priority' => (int) $best_seller_priority[$k]
                    ];
                }
            }

            ProductHot::insert($arrSave['ProductHot']);
        }
        /* END BEST SELLER / HOT */

        return redirect()->route($this->controller.'.index')->with('status', __( 'main.data_has_been_added', ['page' => $product->title] ) );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-update')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );
        }

        if ($product->status === 99){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );
        }

        $utils['action'] = __('main.edit');
        $utils['options']['categories'] = Dropdown::getOptions('productcategory');
        $utils['options']['collections'] = Dropdown::getOptions('productcollection');
        $utils['options']['categoriesSelected'] = $product->categories()->pluck('dropdown_items.id')->toArray();
        $utils['options']['collectionsSelected'] = $product->collections()->pluck('dropdown_items.id')->toArray();
        $utils['options']['arrColorSizeQty'] = [];
        $utils['options']['arrColorImage'] = [];

        return view('admin.'.$this->controller.'.edit', compact('product', 'utils'))->with(array('controller' => $this->controller, 'title' => $this->title(), 'description' => $this->description, 'icon' => $this->icon));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-update')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );
        }

        if ($product->status === 99){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );
        }

        /*
        $validator = Validator::make($request->all(), $this->arrValidate("edit", $product->id), $this->arrNiceName());

        if ($validator->fails()) {
            return $validator->errors();
        }
        */

        Validator::make($request->all(), $this->arrValidate("edit", $product->id), [], $this->arrNiceName())->validate();

        $request->slug = Str::slug($request->get('title'), '-');
        $mainImages = $request->image;

        $request->image_first = $product->image;
        $request->image_second = $product->image_second;
        if (!is_null($mainImages[1])){
            $request->image_first = $mainImages[1];
        }
        if (!is_null($mainImages[2])){
            $request->image_second = $mainImages[2];
        }

        $product->update($this->arrSaved($request, 'edit'));

        // move main & second image
        $fromPath = 'images/products/temps/';
        $destinationPath = 'images/products/'.$product->id.'/';
        if (!is_null($mainImages[1])){
            Storage::disk('public')->move($fromPath.$product->image, $destinationPath.$product->image);
        }
        if (!is_null($mainImages[2])){
            Storage::disk('public')->move($fromPath.$product->image_second, $destinationPath.$product->image_second);
        }

        $collections = $request->get('collections') !== null && $request->get('collections') > 0 ? $request->get('collections') : [];
        $product->categories()->sync(array_merge($request->get('categories'), $collections));
        $product->saveTags(Tag::setTag($request->get('tags'), $product->title));

        /* START DISCOUNT */
        $product->discounts()->delete();
        $discounts = $request->get('discounts');
        if (count($discounts['price'])) {
            $discount_date_start = $discounts['date_start'];
            $discount_date_end = $discounts['date_end'];
            $discount_price = $discounts['price'];
            $discount_priority = $discounts['priority'];

            $arrSave['ProductDiscount'] = [];
            foreach ($discount_price as $k => $price) {
                if ($price != '' || $discount_priority[$k] != '' || $discount_date_start[$k] != '') {
                    $arrSave['ProductDiscount'][] = [
                        'product_id' => $product->id,
                        'date_start' => date('Y-m-d H:i:s', strtotime($discount_date_start[$k])),
                        'date_end' => $discount_date_end[$k] != '' ? date('Y-m-d H:i:s', strtotime($discount_date_end[$k])) : null,
                        'price' => str_replace('.', '', $price),
                        'priority' => (int) $discount_priority[$k]
                    ];
                }
            }

            ProductDiscount::insert($arrSave['ProductDiscount']);
        }
        /* END DISCOUNT */

        /* START NEW RELEASES */
        $product->newReleases()->delete();
        $new_releases = $request->get('new_releases');
        if (count($new_releases['date_start'])) {
            $new_release_date_start = $new_releases['date_start'];
            $new_release_date_end = $new_releases['date_end'];
            $new_release_priority = $new_releases['priority'];

            $arrSave['ProductNewReleases'] = [];
            foreach ($new_release_date_start as $k => $date_start) {
                if ($date_start != '' || $new_release_priority[$k] != '') {
                    $arrSave['ProductNewReleases'][] = [
                        'product_id' => $product->id,
                        'date_start' => date('Y-m-d H:i:s', strtotime($date_start)),
                        'date_end' => $new_release_date_end[$k] != '' ? date('Y-m-d H:i:s', strtotime($new_release_date_end[$k])) : null,
                        'priority' => (int) $new_release_priority[$k]
                    ];
                }
            }

            ProductNewReleases::insert($arrSave['ProductNewReleases']);
        }
        /* END NEW RELEASES */

        /* START BEST SELLER / HOT */
        $product->recomendeds()->delete();
        $best_sellers = $request->get('best_sellers');

        if (is_array($best_sellers) && count($best_sellers['date_start'])) {
            $best_seller_date_start = $best_sellers['date_start'];
            $best_seller_date_end = $best_sellers['date_end'];
            $best_seller_priority = $best_sellers['priority'];

            $arrSave['ProductHot'] = [];
            foreach ($best_seller_date_start as $k => $date_start) {
                if ($date_start != '' || $best_seller_priority[$k] != '') {
                    $arrSave['ProductHot'][] = [
                        'product_id' => $product->id,
                        'date_start' => date('Y-m-d H:i:s', strtotime($date_start)),
                        'date_end' => $best_seller_date_end[$k] != '' ? date('Y-m-d H:i:s', strtotime($best_seller_date_end[$k])) : null,
                        'priority' => (int) $best_seller_priority[$k]
                    ];
                }
            }

            ProductHot::insert($arrSave['ProductHot']);
        }
        /* END BEST SELLER / HOT */

        return redirect()->route($this->controller.'.index')->with('status', __( 'main.data_has_been_updated', ['page' => $product->title] ) );
    }

    public function viewStock(Product $product)
    {
        $userLoged = auth()->user();
        if ($product->status === 99){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );
        }

        $utils['action'] = __('main.viewStock');
        $utils['options']['size'] = Dropdown::getOptions('size');
        $utils['options']['colors'] = Color::where('status', 1)->pluck('title', 'id')->toArray();
        $utils['options']['colorSizes'] = $product->colorSizeQtys;

        $arrColorSize = array();
        foreach ($product->colorSizeQtys as $colorSize) {
            $arrColorSize[$colorSize->color_id][$colorSize->size_id] = $colorSize->qty;
        }

        $utils['options']['colorSizes'] = $arrColorSize;

        return view('admin.'.$this->controller.'.view-stock', compact('product', 'utils'))->with(array('controller' => $this->controller, 'title' => $this->title(), 'description' => $this->description, 'icon' => $this->icon));
    }

    public function additionalStock(Product $product)
    {
        $userLoged = auth()->user();
        if ($product->status === 99){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );
        }

        $utils['action'] = __('main.additionalStock');

        return view('admin.'.$this->controller.'.additional-stock', compact('product', 'utils'))->with(array('controller' => $this->controller, 'title' => $this->title(), 'description' => $this->description, 'icon' => $this->icon));
    }

    public function additionalStockCreate(Product $product)
    {
        $userLoged = auth()->user();
        if ($product->status === 99){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );
        }

        $utils['action'] = __('main.add_new') . ' ' . __('main.additionalStock');
        $utils['options']['size'] = Dropdown::getOptions('size');
        $utils['options']['colors'] = Color::getData(true);

        return view('admin.'.$this->controller.'.additional-stock-create', compact('product', 'utils'))->with(array('controller' => $this->controller, 'title' => $this->title(), 'description' => $this->description, 'icon' => $this->icon));
    }

    public function additionalStockStore(Request $request, Product $product)
    {
        $userLoged = auth()->user();
        if ($product->status === 99){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );
        }

        $colors = $request->color;
        $qtys = $request->qty;

        if(!count($qtys)){
            return redirect()->route($this->controller.'.additionalStock.create', $product->id)->with('error', 'Tidak ada data untuk diproses' );
        }

        $additionalStock = ProductAdditional::create([
            'additional_date' => Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'uploaded_by'   => $userLoged->id,
            'product_id'    => $product->id,
        ]);

        $countProductColorSizeOld = ProductColorSize::where('product_id', $product->id)->groupBy('color_id')->count();

        $arrSave = [];
        $totalQty = $product->qty;
        foreach ($qtys as $color_id => $sizes) {
            foreach ($sizes as $size_id => $qty) {

                $oldQty = 0;
                $productSizeQty = ProductSizeQty::where('product_id', $product->id)
                    ->where('color_id', $color_id)
                    ->where('size_id', $size_id)
                    ->first();

                if ($productSizeQty){
                    $oldQty = $productSizeQty->qty;
                }

                $productAdditionalItem = ProductAdditionalItem::create([
                    'product_additional_id' => $additionalStock->id,
                    'product_id'    => $product->id,
                    'color_id'      => $color_id,
                    'size_id'       => $size_id,
                    'qty'           => $qty,
                ]);

                $arrSave['ProductStokActivity'][] = [
                    'tanggal' => $this->currentDate(),
                    'product_id' => $product->id,
                    'color_id' => $color_id,
                    'size_id' => $size_id,
                    'qty' => $qty,
                    'jenis' => 2,
                    'id_terkait' => $productAdditionalItem->id
                ];

                ProductColorSize::firstOrCreate(
                    [
                        'product_id' => $product->id,
                        'color_id' => $color_id,
                        'size_id' => $size_id,
                    ]
                );

                $newQty = $oldQty + $qty;
                $totalQty = $totalQty + $newQty;

                ProductSizeQty::firstOrCreate(
                    [
                        'product_id' => $product->id,
                        'color_id' => $color_id,
                        'size_id' => $size_id,
                    ],
                    [
                        'qty' => $newQty
                    ]
                );
            }
        }

        ProductStokActivity::insert($arrSave['ProductStokActivity']);

        $countProductColorSizeNew = ProductColorSize::where('product_id', $product->id)->groupBy('color_id')->count();

        $product->qty = $totalQty;
        if ($countProductColorSizeOld == $countProductColorSizeNew){
            $product->save();

            return redirect()->route($this->controller.'.additionalStock', $product->id)->with('status', __( 'main.data_has_been_added', ['page' => 'Penambahan Stok ' . $product->title] ) );
        } else {
            $product->status = 0;
            $product->save();

            return redirect()->route($this->controller.'.additionalStock', $product->id)->with('status', 'Penambahan stok '.$product->title.' telah berhasil dilakukan. Produk secara otomatis dinon aktifkan oleh sistem, dikarenakan terjadi penambahan warna baru. Silahkan upload gambar untuk warna baru tersebut, kemudian aktifkan kembali secara manual!' );
        }
    }

    public function additionalStockShow(Product $product, ProductAdditional $additional_stock)
    {
        $userLoged = auth()->user();
        if ($product->status === 99){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );
        }

        $utils['action'] = __('main.view') . ' ' . __('main.additionalStock');
        $utils['options']['size'] = Dropdown::getOptions('size');
        $utils['options']['colors'] = Color::getData(true);

        return view('admin.'.$this->controller.'.additional-stock-show', compact('product', 'additional_stock', 'utils'))->with(array('controller' => $this->controller, 'title' => $this->title(), 'description' => $this->description, 'icon' => $this->icon));
    }

    public function stockOpname(Product $product)
    {
        $userLoged = auth()->user();
        if ($product->status === 99){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );
        }

        $utils['action'] = __('main.stockopname');

        return view('admin.'.$this->controller.'.so', compact('product', 'utils'))->with(array('controller' => $this->controller, 'title' => $this->title(), 'description' => $this->description, 'icon' => $this->icon));
    }

    public function stockOpnameCreate(Product $product)
    {
        $userLoged = auth()->user();
        if ($product->status === 99){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );
        }

        $utils['action'] = __('main.add_new') . ' ' . __('main.stockopname');
        $utils['options']['size'] = Dropdown::getOptions('size');
        $utils['options']['colors'] = Color::getData(true);

        return view('admin.'.$this->controller.'.so-create', compact('product', 'utils'))->with(array('controller' => $this->controller, 'title' => $this->title(), 'description' => $this->description, 'icon' => $this->icon));
    }

    public function stockOpnameStore(Request $request, Product $product)
    {
        $userLoged = auth()->user();
        if ($product->status === 99){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );
        }

        $qtys = $request->qty;

        $stockOpname = StockOpname::create([
            'tanggal' => Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'uploaded_by'   => $userLoged->id,
            'product_id'    => $product->id,
        ]);

        $arrSave = [];
        $totalQty = $product->qty;
        foreach ($qtys as $color_id => $sizes) {
            foreach ($sizes as $size_id => $qty) {

                $qtyBooked = $product->bookeds()->where('color_id', $color_id)->where('size_id', $size_id)->sum('qty');

                $arrSave['StockOpnameItem'][] = [
                    'stock_opname_id' => $stockOpname->id,
                    'product_id'    => $product->id,
                    'color_id'      => $color_id,
                    'size_id'       => $size_id,
                    'qty'           => $qty,
                ];

                // update stock real
                $productSizeQty = ProductSizeQty::where('product_id', $product->id)
                    ->where('color_id', $color_id)
                    ->where('size_id', $size_id)
                    ->first();

                $oldQty = $productSizeQty->qty;



                $productSizeQty->qty = $qtyBooked + $qty;
                $productSizeQty->save();

                $totalQty = $totalQty - $oldQty + $productSizeQty->qty;

            }
        }

        $product->qty = $totalQty;
        $product->save();

        StockOpnameItem::insert($arrSave['StockOpnameItem']);

        return redirect()->route($this->controller.'.so', $product->id)->with('status', 'Stock Opname '.$product->title.' telah berhasil dilakukan' );

    }

    public function stockOpnameDelete($productSizeQty)
    {
        $productSizeQty = ProductSizeQty::whereId($productSizeQty)->firstOrFail();
        $product = $productSizeQty->product;
        $qtyBooked = $product->bookeds()->where('color_id', $productSizeQty->color_id)->where('size_id', $productSizeQty->size_id)->sum('qty');

        $utils['options']['size'] = Dropdown::getOptions('size');
        $utils['options']['colors'] = Color::getData(true);

        // check booking, kalau masih ada ditolak
        if ($qtyBooked > 0){
            return redirect()->route($this->controller.'.so.create', $productSizeQty->product_id)->with('error', 'Warna '. $utils['options']['colors'][$productSizeQty->color_id].' Ukuran ' . $utils['options']['size'][$productSizeQty->size_id] . ' GAGAL dihapus karena masih mempunyai stok booking' );
        }

        $product->qty = $product->qty - $productSizeQty->qty;
        $product->save();

        $productSizeQty->delete();

        return redirect()->route($this->controller.'.so.create', $productSizeQty->product_id)->with('status', 'Warna '. $utils['options']['colors'][$productSizeQty->color_id].' Ukuran ' . $utils['options']['size'][$productSizeQty->size_id] . ' telah berhasil dihapus' );
    }

    public function StockOpnameShow(Product $product, StockOpname $so)
    {
        $userLoged = auth()->user();
        if ($product->status === 99){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );
        }

        $utils['action'] = __('main.view') . ' ' . __('main.stockopname');
        $utils['options']['size'] = Dropdown::getOptions('size');
        $utils['options']['colors'] = Color::getData(true);

        return view('admin.'.$this->controller.'.so-show', compact('product', 'so', 'utils'))->with(array('controller' => $this->controller, 'title' => $this->title(), 'description' => $this->description, 'icon' => $this->icon));
    }

    public function images(Product $product)
    {
        $userLoged = auth()->user();
        if ($product->status === 99){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );
        }

        $utils['action'] = 'Kelola Foto';

        $utils['options']['colors'] = $product->colors()
            ->leftJoin('colors', 'colors.id', 'product_colors.color_id')
            ->orderBy('product_colors.sort_order')
            ->pluck('colors.title', 'colors.id');

        return view('admin.'.$this->controller.'.images', compact('product', 'utils'))->with(array('controller' => $this->controller, 'title' => $this->title(), 'description' => $this->description, 'icon' => $this->icon));
    }

    public function imagesStore(Request $request, Product $product)
    {
        $userLoged = auth()->user();
        if ($product->status === 99){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );
        }

        $productImages = $product->images;
        $arrImageOld = [];
        foreach ($productImages as $image){
            $arrImageOld[$image->color_id][] = $image->image;
        }

        $images = $request->images;

        if (count($images)){
            $fromPath = 'images/products/temps/';
            $destinationPath = 'images/products/'.$product->id.'/';
            $arrSave = [];
            $arrSave['ProductImage'] = [];
            $arrImageNew = [];


            foreach ($images as $color_id => $v){
                foreach ($images[$color_id] as $indexImage => $fileName){
                    if (!isset($arrImageOld[$color_id]) || !in_array($fileName, $arrImageOld[$color_id])){
                        if (Storage::disk('public')->exists($fromPath.$fileName)){
                            $arrSave['ProductImage'][] = [
                                'product_id' => $product->id,
                                'color_id' => $color_id,
                                'image' => $fileName,
                                'sort_order' => $indexImage + 1
                            ];
                            Storage::disk('public')->move($fromPath.$fileName, $destinationPath.$fileName);
                        }
                    }
                    $arrImageNew[$color_id][] = $fileName;
                }
            }

            if (count($arrSave['ProductImage'])){
                ProductImage::insert($arrSave['ProductImage']);
            }

            // delete image
            foreach ($arrImageOld as $color_id => $files){
                foreach ($files as $file){
                    if (!in_array($file, $arrImageNew[$color_id])){
                        // delete from db
                        ProductImage::where('color_id', $color_id)->where('image', $file)->delete();

                        // delete file
                        Storage::disk('public')->delete($destinationPath.$file);
                    }
                }
            }
        }

        return redirect()->route($this->controller.'.index')->with('status', __( 'main.data_has_been_updated', ['page' => 'Foto produk ' . $product->title] ) );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }

    public function dropzone(Request $request)
    {
        $arrValidates = [
            'file' => 'required|image|mimes:jpeg,png,jpg|max:500',
        ];

        $validator = Validator::make($request->all(), $arrValidates, [], $this->arrNiceName());
        if ($validator->fails()) {
            $resp = [
                'id' => $request->id,
                'error' => $validator->errors()->first()
            ];
            return response()->json($resp, 400);
        }

        $file = $request->file;
        $destinationPath = 'public/images/products/temps';

        $image = Image::make($file);
        $isJpg = $image->mime() === 'image/jpg' || $image->mime() === 'image/jpeg';
        if($isJpg && $image->exif('Orientation')){
            $image = orientate($image, $image->exif('Orientation'));
        }

        $image->stream(); // <-- Key point
        $fileName = md5(time().$file->getClientOriginalName()) .'.'. pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
        $destinationPath = $destinationPath.'/'.$fileName;
        $storagePath = Storage::put($destinationPath, $image);

        if (!$storagePath){
            return response()->json([
                'id' => $request->id
            ]);
        }

        return response()->json([
            'id' => $request->id,
            'url' => $fileName
        ]);
    }

    public function uploadImage(Request $request)
    {
        $arrValidates = [
            'file' => 'required|array',
        ];

        $validator = Validator::make($request->all(), $arrValidates, [], $this->arrNiceName());
        if ($validator->fails()) {
            $resp = [
                'id' => $request->id,
                'error' => $validator->errors()->first()
            ];
            return response()->json($resp, 400);
        }

        $files = $request->file;
        if (count($files) > 0) {
            $arrFiles = array();
            $destinationPath = 'public/images/products/temps';

            foreach($files as $index => $file) {

                $image = Image::make($file);
                $isJpg = $image->mime() === 'image/jpg' || $image->mime() === 'image/jpeg';
                if($isJpg && $image->exif('Orientation')){
                    $image = orientate($image, $image->exif('Orientation'));
                }

                $image->stream(); // <-- Key point
                $fileName = md5(time().$file->getClientOriginalName()) .'.'. pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
                $destinationPath = $destinationPath.'/'.$fileName;
                Storage::put($destinationPath, $image);

                $arrFiles[$index]['storage'] = $fileName;
                $arrFiles[$index]['filename'] = $fileName;
            }
            return response()->json(array('files' => $arrFiles), 200);
        } else {
            return response()->json(array('status' => 0, 'msg' => 'Silahkan upload gambar'), 200);
        }
    }

    public function dropzoneProduct(Request $request)
    {
        $arrValidates = [
            'file' => 'required|image|mimes:jpeg,png,jpg|max:500',
        ];

        $validator = Validator::make($request->all(), $arrValidates, [], $this->arrNiceName());
        if ($validator->fails()) {
            $resp = [
                'id' => $request->id,
                'error' => $validator->errors()->first()
            ];
            return response()->json($resp, 400);
        }

        $file = $request->file;
        $destinationPath = 'public/images/products/temps';

        $image = Image::make($file);
        $isJpg = $image->mime() === 'image/jpg' || $image->mime() === 'image/jpeg';
        if($isJpg && $image->exif('Orientation')){
            $image = orientate($image, $image->exif('Orientation'));
        }

        $image->stream(); // <-- Key point
        $fileName = md5(time().$file->getClientOriginalName()) .'.'. pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
        $destinationPath = $destinationPath.'/'.$fileName;
        $storagePath = Storage::put($destinationPath, $image);

        if (!$storagePath){
            return response()->json([
                'id' => $request->id
            ]);
        }

        return response()->json([
            'id' => $request->id,
            'url' => $fileName
        ]);
    }

    public function getData(Request $request)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-index')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );
        }

        $where = [];
        $where[] = ['products.status', '<>', 99];

        $rows = Product::select([
            'products.id',
            'products.code',
            'products.image',
            'products.title',
            'products.price',
            'products.qty',
            'products.published_on',
            'products.status',
            'brands.name as brand_name'
        ])
        ->selectRaw("(SELECT price FROM ".getTableName(with(new ProductDiscount)->getTable())." pd WHERE pd.product_id = ".getTableName(with(new Product)->getTable()).".id AND ((pd.date_start IS NULL OR pd.date_start <= '".$this->currentDate()."') AND (pd.date_end IS NULL OR pd.date_end >= '".$this->currentDate()."')) ORDER BY pd.priority ASC LIMIT 1 ) AS discount")
        ->leftJoin('brands', 'brands.id','=','products.brand_id')
        ->where($where);

        return Datatables::of($rows)
            ->addIndexColumn()
            ->addColumn('categories', function ($row) {
                $categories = $row->categories()
                    ->whereIn('dropdown_item_id', function($query){
                        $query->select('id')
                            ->from(with(new DropdownItem)->getTable())
                            ->where('dropdown_id',1);
                    })
                    ->pluck('dropdown_items.title')->toArray();
                $arrName = [];
                if (count($categories)){
                    foreach ($categories as $v){
                        $arrName[] = $v;
                    }
                }
                return implode(', ', $arrName);
            })
            ->addColumn('collections', function ($row) {
                $categories = $row->categories()
                    ->whereIn('dropdown_item_id', function($query){
                        $query->select('id')
                            ->from(with(new DropdownItem)->getTable())
                            ->where('dropdown_id',2);
                    })
                    ->pluck('dropdown_items.title')->toArray();
                $arrName = [];
                if (count($categories)){
                    foreach ($categories as $v){
                        $arrName[] = $v;
                    }
                }
                return implode(', ', $arrName);
            })
            ->addColumn('published_on', function ($row) {
                return date('d/m/Y H:i', strtotime($row->published_on));
            })
            ->addColumn('image', function ($row) {
                return route(config('imagecache.route'), ['template' => 'medium', 'filename' => $row->getImage() ]);
            })
            ->addColumn('statusText', function ($row) {
                return arrStatusActive()[$row->status];
            })
            ->addColumn('statusClass', function ($row) {
                return arrStatusActiveClass()[$row->status];
            })
            ->addColumn('canEdit', function ($row) {
                return auth()->user()->can($this->controller.'-update');
            })
            ->addColumn('editUrl', function ($row) {
                return route($this->controller.'.edit', $row->id);
            })
            ->addColumn('canDelete', function ($row) {
                return auth()->user()->can($this->controller.'-delete');
            })
            ->addColumn('deleteUrl', function ($row) {
                return route($this->controller.'.destroy', $row->id);
            })
            ->addColumn('viewUrl', function ($row) {
                return route($this->controller.'.show', $row->id);
            })
            ->addColumn('viewStock', function ($row) {
                return route($this->controller.'.viewStock', $row->id);
            })
            ->addColumn('additionalStock', function ($row) {
                return route($this->controller.'.additionalStock', $row->id);
            })
            ->addColumn('so', function ($row) {
                return route($this->controller.'.so', $row->id);
            })
            ->addColumn('imagesUrl', function ($row) {
                return route($this->controller.'.images', $row->id);
            })
            ->make(true);
    }

    public function additionalStockGetData(Request $request, Product $product)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-update')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );
        }

        if ($product->status === 99){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );
        }

        $where = [];

        $rows = $product->additionals;

        return Datatables::of($rows)
            ->addIndexColumn()

            ->addColumn('additional_date', function ($row) {
                return date('d/m/Y', strtotime($row->additional_date));
            })
            ->addColumn('admin', function ($row) {
                return $row->user->name;
            })
            ->addColumn('viewUrl', function ($row) use($product){
                return route($this->controller.'.additionalStock.show', ['product'=>$product->id, 'additional_stock'=>$row->id]);
            })
            ->make(true);
    }

    public function StockOpnameGetData(Request $request, Product $product)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-update')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );
        }

        if ($product->status === 99){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );
        }

        $where = [];

        $rows = $product->stockOpnames;

        return Datatables::of($rows)
            ->addIndexColumn()

            ->addColumn('tanggal', function ($row) {
                return date('d/m/Y', strtotime($row->tanggal));
            })
            ->addColumn('admin', function ($row) {
                return $row->user->name;
            })
            ->addColumn('viewUrl', function ($row) use($product){
                return route($this->controller.'.so.show', ['product'=>$product->id, 'so'=>$row->id]);
            })
            ->make(true);
    }
}
