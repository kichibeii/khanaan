<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductCategory;
use App\ProductDiscount;
use App\ProductNewReleases;
use App\ProductHot;
use App\ProductColorSize;
use App\DropdownItem;
use App\ProductTag;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(['cartauth']);
    }

    private function arrOrder()
    {
        return [
            'published_on' => 'Newest',
            'price' => 'Price',
        ];
    }

    private function arrOrderBy()
    {
        return [
            'asc' => 'ASC',
            'desc' => 'DESC',
        ];
    }

    private function arrLimit()
    {
        return [9,15,30];
    }

    private function arrFeature()
    {
        return [
            'new-arrivals' => 'New Arrivals',
            'sale' => 'Sale',
            'best-seller' => 'Best Seller',
        ];
    }

    public function collections()
    {
        $collections = \App\DropdownItem::where('status', 1)
                ->where('dropdown_id', 2)
                ->get();

        $utils['title'] = ' | ' . __('main.collections');
        return view('product.collections', compact('collections', 'utils'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $action='')
    {
        // all, sale, new-arrivals, best seller, collection

        $utils['title'] = __('main.shop');
        $utils['arrFeature'] = $this->arrFeature();
        $utils['categories'] = \App\Dropdown::getOptions('productcategory');
        $utils['collections'] = \App\Dropdown::getOptions('productcollection', true);
        $utils['sizes'] = \App\Dropdown::getOptions('size');

        $utils['colors'] = \App\Color::whereStatus(1)
            ->orderBy('sort_order', 'ASC')
            ->pluck('title', 'id')
            ->toArray();

        $utils['brands'] = \App\Brand::whereStatus(1)
            ->orderBy('name', 'ASC')
            ->pluck('name', 'id')
            ->toArray();

        $utils['append'] = [];
        $brand_id = $request->brand_id !== null && array_key_exists($request->brand_id, $utils['brands']) ? $request->brand_id : 0;
        $cat_id = $request->cat_id !== null && array_key_exists($request->cat_id, $utils['categories']) ? $request->cat_id : 0;
        $color_id = $request->color_id !== null && array_key_exists($request->color_id, $utils['colors']) ? $request->color_id : 0;
        $size_id = $request->size_id !== null && array_key_exists($request->size_id, $utils['sizes']) ? $request->size_id : 0;
        $order = $request->order !== null && array_key_exists($request->order, $this->arrOrder()) ? $request->order : 'published_on';
        $orderBy = $request->order_by !== null && array_key_exists($request->order_by, $this->arrOrderBy()) ? $request->order_by : 'DESC';
        $limit = $request->limit !== null && in_array($request->limit, $this->arrLimit()) ? $request->limit : '9';
        $search = $request->search;

        $currentDateTime = Carbon::now('Asia/Jakarta')->format("Y-m-d H:i:s");

        $datas = Product::where('status',1)
            ->where('published_on','<=',$currentDateTime);

        if (!empty($action) && $action !== 'sale'){
            // cek apakah termasuk collection
            $dropdownItem = \App\DropdownItem::where('status', 1)
                ->where('dropdown_id', 2)
                ->where('slug', $action)
                ->firstOrFail();

            $action = 'collection';
        }

        $utils['search'] = $search;
        $utils['baseUrl'] = 'shop';
        $utils['action'] = $action;
        $utils['menuActive'] = 'shop';
        switch ($action) {

            case 'sale':
                $datas->whereIn('id', function($query) use($currentDateTime){
                    $query->select('product_id')
                        ->from(with(new ProductDiscount)->getTable())
                        ->where(function ($query2) use($currentDateTime){
                            $query2->where('date_start',null)
                                ->orWhere('date_start','<=',$currentDateTime);
                        })
                        ->where(function ($query3) use($currentDateTime){
                            $query3->where('date_end',null)
                                ->orWhere('date_end','>=',$currentDateTime);
                        })
                        ->orderBy('priority','asc');
                });

                $utils['title'] .= ' | Sale';
                $utils['baseUrl'] = 'shop.action';
                $utils['append']['action'] = 'sale';
                $utils['menuActive'] = 'sale';

                break;

            case 'hot':
                # code...

                break;

            case 'new-arrivals':
                # code...

                break;

            case 'collection':
                $datas->whereIn('id', function($query) use ($dropdownItem) {
                    $query->select('product_id')
                        ->from('product_dropdown_item')
                        ->where('dropdown_item_id',$dropdownItem->id);
                });

                $utils['title'] .= ' | Collections | ' . $dropdownItem->title;
                $utils['baseUrl'] = 'shop.action';
                $utils['append']['action'] = $dropdownItem->slug;
                $utils['menuActive'] = 'collections';

                break;

            default:
                # code...
                break;
        }

        /* WHERE */
        $whereCount = [];
        $whereCount[] = ['products.status', 1];
        $whereCount[] = ['products.published_on', '<=', $currentDateTime];

        // BRAND
        if (!empty($brand_id)){
            $whereCount[] = ['products.brand_id', $brand_id];
            $datas->where('brand_id', $brand_id);

            $utils['brandSelected'] = [
                'id' => $brand_id,
                'title' => $utils['brands'][$brand_id]
            ];

            $utils['append']['brand_id'] = $brand_id;
            $utils['title'] .= ' | ' . $utils['brands'][$brand_id];
        }

        // CATEGORY
        if (!empty($cat_id)){
            //$whereCount[] = ['products.brand_id', $brand_id];
            $datas->whereIn('id', function($query) use ($cat_id) {
                $query->select('product_id')
                    ->from('product_dropdown_item')
                    ->where('dropdown_item_id',$cat_id);
            });

            $utils['categorySelected'] = [
                'id' => $cat_id,
                'title' => $utils['categories'][$cat_id]
            ];

            $utils['append']['cat_id'] = $cat_id;
            $utils['title'] .= ' | ' . $utils['categories'][$cat_id];
        }

        if (!empty($color_id)){
            $datas->whereIn('id', function($query) use ($color_id) {
                $query->select('product_id')
                    ->from('product_colors')
                    ->where('color_id',$color_id);
            });

            $utils['colorSelected'] = [
                'id' => $color_id,
                'title' => $utils['colors'][$color_id]
            ];

            $utils['append']['color_id'] = $color_id;
            $utils['title'] .= ' | ' . $utils['colors'][$color_id];
        }

        if (!empty($size_id)){
            $datas->whereIn('id', function($query) use ($size_id) {
                $query->select('product_id')
                    ->from('product_color_sizes')
                    ->where('size_id',$size_id);
            });

            $utils['sizeSelected'] = [
                'id' => $size_id,
                'title' => $utils['sizes'][$size_id]
            ];

            $utils['append']['size_id'] = $size_id;
            $utils['title'] .= $utils['title'] . ' | ' . $utils['sizes'][$size_id];
        }

        $datas->selectRaw("id, code, title, slug, image, image_second, price, description,
            (SELECT price FROM ".getTableName(with(new ProductDiscount)->getTable())." pd WHERE pd.product_id = ".getTableName(with(new Product)->getTable()).".id AND ((pd.date_start IS NULL OR pd.date_start <= '".$currentDateTime."') AND (pd.date_end IS NULL OR pd.date_end >= '".$currentDateTime."')) ORDER BY pd.priority ASC LIMIT 1 ) AS discount,
            (SELECT id FROM ".getTableName(with(new ProductNewReleases)->getTable())." pnr WHERE pnr.product_id = ".getTableName(with(new Product)->getTable()).".id AND ((pnr.date_start IS NULL OR pnr.date_start <= '".$currentDateTime."') AND (pnr.date_end IS NULL OR pnr.date_end >= '".$currentDateTime."')) ORDER BY pnr.priority ASC LIMIT 1 ) AS newRelease,
            (SELECT id FROM ".getTableName(with(new ProductHot)->getTable())." ph WHERE ph.product_id = ".getTableName(with(new Product)->getTable()).".id AND ((ph.date_start IS NULL OR ph.date_start <= '".$currentDateTime."') AND (ph.date_end IS NULL OR ph.date_end >= '".$currentDateTime."')) ORDER BY ph.priority ASC LIMIT 1 ) AS hot
            ")
            ->orderBy($order, $orderBy);
        if($search):
            $datas->where('title', 'like', '%'.$search.'%');
        endif;
        $products = $datas->paginate($limit);

        /*

        // START BRAND
        $productBrands = Product::selectRaw(getTableName(with(new Product)->getTable()).".brand_id as brand, count(".getTableName(with(new Product)->getTable()).".id) as total")
            ->where($whereCount)
            ->groupBy('products.brand_id')
            ->get();

        $arrProductBrands = [];
        if (count($productBrands)){
            foreach ($productBrands as $pb){
                if ($pb->total > 0){
                    $arrProductBrands[$pb->brand] = $pb->total;
                }
            }
        }
        $utils['countBrands'] = $arrProductBrands;
        // END BRAND


        $productCategories = Product::leftJoin('product_dropdown_item as pdi', 'pdi.product_id', '=', 'products.id')
            ->leftJoin('dropdown_items as di', 'di.id', '=', 'pdi.dropdown_item_id')
            ->selectRaw("kohakuho_di.slug as category, count(".getTableName(with(new Product)->getTable()).".id) as total")
            ->where('products.status',1)
            ->where('products.published_on','<=',$currentDateTime)
            ->groupBy('pdi.dropdown_item_id')
            ->get();

        $arrProductCategories = [];
        if (count($productCategories)){
            foreach ($productCategories as $pc){
                $arrProductCategories[$pc->category] = $pc->total;
            }
        }
        $utils['countCategories'] = $arrProductCategories;

        $productColors = Product::leftJoin('product_colors as c', 'c.product_id', '=', 'products.id')
            ->selectRaw("kohakuho_c.color_id as color, count(".getTableName(with(new Product)->getTable()).".id) as total")
            ->where('status',1)
            ->where('published_on','<=',$currentDateTime)
            ->groupBy('c.color_id')
            ->get();

        $arrProductColors = [];
        if (count($productColors)){
            foreach ($productColors as $pc){
                $arrProductColors[$pc->color] = $pc->total;
            }
        }
        $utils['countColors'] = $arrProductColors;

        $productSizes = ProductColorSize::leftJoin('products as p', 'product_color_sizes.product_id', '=', 'p.id')
            ->selectRaw("".getTableName(with(new ProductColorSize)->getTable()).".size_id as size, count(".getTableName(with(new ProductColorSize)->getTable()).".id) as total")
            ->where('p.status',1)
            ->where('p.published_on','<=',$currentDateTime)
            ->groupBy('product_color_sizes.size_id')
            ->get();

        $arrProductSizes = [];
        if (count($productSizes)){
            foreach ($productSizes as $ps){
                $arrProductSizes[$ps->size] = $ps->total;
            }
        }
        $utils['countSizes'] = $arrProductSizes;

        $utils['countProducts'] = Product::where('status',1)
            ->where('published_on','<=',$currentDateTime)
            ->count();
        */

        //return $utils['countSizes'];

        $utils['append']['order'] = $order;
        $utils['append']['order_by'] = $orderBy;
        $utils['append']['limit'] = $limit;
        $utils['arrLimit'] = $this->arrLimit();
        $utils['url'] = $utils['append'];
        if($search):
            $utils['append']['search'] = $search;
        endif;

        return view('product.index', compact('products', 'utils'));
    }

    public function categories($id)
    {
        $utils['categories'] = \App\Dropdown::getOptions('productcategory');
        if (!array_key_exists($id, $utils['categories'])){
            abort('404');
        }

        $utils['categorySelected'] = [
            'id' => $id,
            'title' => $utils['categories'][$id]
        ];
        $products = Product::getProductList(true, '', 'published_on', 'DESC', 12, true);

        return view('product.index', compact('products', 'utils'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $currentDateTime = Carbon::now('Asia/Jakarta')->format("Y-m-d H:i:s");

        $product = Product::selectRaw("id, code, title, slug, image, image_second, price, description, description_id, qty, size_id,
            (SELECT price FROM ".getTableName(with(new ProductDiscount)->getTable())." pd WHERE pd.product_id = ".getTableName(with(new Product)->getTable()).".id AND ((pd.date_start IS NULL OR pd.date_start <= '".$currentDateTime."') AND (pd.date_end IS NULL OR pd.date_end >= '".$currentDateTime."')) ORDER BY pd.priority ASC LIMIT 1 ) AS discount
            ")
            ->where('status',1)
            ->where('published_on','<=',$currentDateTime)
            ->where('slug',$slug)
            ->firstOrFail();

        $utils['colors'] = $product->colors()
            ->select('colors.id', 'colors.title', 'colors.color_hex')
            ->leftJoin('colors', 'colors.id', '=', 'product_colors.color_id')
            ->get();

        $categories = $product->categories()
            ->whereIn('dropdown_item_id', function($query){
                $query->select('id')
                    ->from(with(new DropdownItem)->getTable())
                    ->where('dropdown_id',1);
            })
            ->pluck('dropdown_items.title', 'dropdown_items.id')->toArray();

        $arrName = [];

        if (count($categories)){
            foreach ($categories as $k => $v){
                $arrName[] = '<li><a href="'.route('shop', ['cat_id'=>$k]).'">'.$v.'</a></li>';
            }
        }
        $utils['categories'] = implode(', ', $arrName);

        $images = $product->images()->orderBy('sort_order', 'asc')->get();
        $arrImage = [];
        foreach ($images as $image){
            $arrImage[$image->color_id][] = $image->image;
        }
        $utils['images'] = $arrImage;

        $utils['relateds'] = Product::selectRaw("id, code, title, slug, image, image_second, price, description,
            (SELECT price FROM ".getTableName(with(new ProductDiscount)->getTable())." pd WHERE pd.product_id = ".getTableName(with(new Product)->getTable()).".id AND ((pd.date_start IS NULL OR pd.date_start <= '".$currentDateTime."') AND (pd.date_end IS NULL OR pd.date_end >= '".$currentDateTime."')) ORDER BY pd.priority ASC LIMIT 1 ) AS discount,
            (SELECT id FROM ".getTableName(with(new ProductNewReleases)->getTable())." pnr WHERE pnr.product_id = ".getTableName(with(new Product)->getTable()).".id AND ((pnr.date_start IS NULL OR pnr.date_start <= '".$currentDateTime."') AND (pnr.date_end IS NULL OR pnr.date_end >= '".$currentDateTime."')) ORDER BY pnr.priority ASC LIMIT 1 ) AS newRelease,
            (SELECT id FROM ".getTableName(with(new ProductHot)->getTable())." ph WHERE ph.product_id = ".getTableName(with(new Product)->getTable()).".id AND ((ph.date_start IS NULL OR ph.date_start <= '".$currentDateTime."') AND (ph.date_end IS NULL OR ph.date_end >= '".$currentDateTime."')) ORDER BY ph.priority ASC LIMIT 1 ) AS hot
            ")
            ->whereIn('id', function($query) use ($product) {
                $query->select('product_id')
                    ->from(with(new ProductTag)->getTable())
                    ->where('tag_id','=', 0);
                    foreach ($product->getTags() as $k => $v){
                        $query->orWhere('tag_id', $k);
                    }
            })
            ->where('id','!=',$product->id)
            ->where('status',1)
            ->where('published_on','<=',$currentDateTime)
            ->limit(4)
            ->get();

        $utils['title'] = 'Shop | ' . $product->title;
        return view('product.show', compact('product', 'utils'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
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
        //
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

    public function getSize(Request $request)
    {
        $product_id = (int) $request->product_id;
        $color_id = (int) $request->color_id;

        $product = Product::where('status',1)
            ->where('published_on','<=',Carbon::now('Asia/Jakarta')->format("Y-m-d H:i:s"))
            ->where('id',$product_id)
            ->firstOrFail();

        $sizes = $product->colorSizeQtys()
            ->select('dropdown_items.id', 'dropdown_items.title', 'product_size_qties.qty')
            ->leftJoin('dropdown_items', 'dropdown_items.id', '=', 'product_size_qties.size_id')
            ->where('color_id', $color_id)
            ->orderBy('dropdown_items.sort_order', 'ASC')
            ->get();

        $arr = array();
        if (count($sizes)) {
            foreach ($sizes as $k => $v) {
                $arr[] = ['id' => $v->id, 'text' => $v->title, 'qty' => $v->qty ];
            }
        }
        return response()->json($arr);
    }

    public function getQty(Request $request)
    {
        $product_id = (int) $request->product_id;
        $color_id = (int) $request->color_id;
        $size_id = (int) $request->size_id;

        $product = Product::where('status',1)
            ->where('published_on','<=',Carbon::now('Asia/Jakarta')->format("Y-m-d H:i:s"))
            ->where('id',$product_id)
            ->firstOrFail();

        $size = $product->colorSizeQtys()
            ->select('dropdown_items.id', 'dropdown_items.title', 'product_size_qties.qty')
            ->leftJoin('dropdown_items', 'dropdown_items.id', '=', 'product_size_qties.size_id')
            ->where('color_id', $color_id)
            ->where('size_id', $size_id)
            ->firstOrFail();

        return response()->json([
            'qty' => $size->qty
            //'qty' => 0
        ]);
    }
}
