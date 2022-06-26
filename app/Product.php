<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ProductDiscount;
use App\ProductNewReleases;
use App\ProductHot;

use Carbon\Carbon;
use Cart;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    protected $guarded = [
        'id'
    ];

    public function size()
    {
        return $this->belongsTo('App\Size');
    }

    public function categories() {
        return $this->belongsToMany('App\DropdownItem', 'product_dropdown_item');
    }

    public function collections() {
        return $this->belongsToMany('App\DropdownItem', 'product_dropdown_item');
    }

    public function discounts()
    {
        return $this->hasMany('App\ProductDiscount', 'product_id');
    }

    public function newReleases()
    {
        return $this->hasMany('App\ProductNewReleases', 'product_id');
    }

    public function recomendeds()
    {
        return $this->hasMany('App\ProductHot', 'product_id');
    }

    public function colors()
    {
        return $this->hasMany('App\ProductColor', 'product_id');
    }

    public function colorSizes()
    {
        return $this->hasMany('App\ProductColorSize', 'product_id');
    }

    public function colorSizeQtys()
    {
        return $this->hasMany('App\ProductSizeQty', 'product_id');
    }

    public function bookeds()
    {
        return $this->hasMany('App\InvoiceProductBooked', 'product_id');
    }

    public function images()
    {
        return $this->hasMany('App\ProductImage', 'product_id');
    }

    public function additionals()
    {
        return $this->hasMany('App\ProductAdditional', 'product_id');
    }

    public function stockOpnames()
    {
        return $this->hasMany('App\StockOpname', 'product_id');
    }

    public function getTags($useTitle=false)
    {
        $rows = ProductTag::leftJoin('tags', 'tags.id', '=', 'product_tags.tag_id')
            ->where('product_tags.product_id',$this->id);

        if (!$useTitle){
            $rows->where('is_title', 0);
        }

        return $rows->pluck('tags.name','tags.id')
            ->toArray();
    }

    public function saveTags($tags)
    {

        if (is_string($tags))
            $tags = explode(',', $tags);

        $tags = array_map('trim', $tags);

        $current_tags = $this->getTags();

        // no tag before! no tag now! ... nothing to do!
        if (count($tags) == 0 && count($current_tags) == 0)
            return;

        // delete all tags
        if (count($tags) == 0)
        {
            // update count (-1) of those tags
            foreach($current_tags as $tag) {
                Tag::where('name', $tag)
                    ->update(['count' => 'count - 1']);
            }

            ProductTag::where('product_id', $this->id)
                ->delete();
        }
        else
        {
            $old_tags = array_diff($current_tags, $tags);
            $new_tags = array_diff($tags, $current_tags);


            // insert all tags in the tag table and then populate the product_tag table
            foreach ($new_tags as $index => $tag_name)
            {
                if ( ! empty($tag_name))
                {
                    // try to get it from tag list, if not we add it to the list
                    $tag = Tag::where('name',$tag_name)
                        ->first();

                    if (!$tag) {
                        $tag = new Tag(array(
                            'name' => trim($tag_name),
                            'is_title' => trim($tag_name) == $this->title ? 1 : 0
                        ));
                    }

                    $tag->count++;
                    $tag->save();

                    // create the relation between the product and the tag
                    $ProductTag = new ProductTag(array(
                        'product_id' => $this->id,
                        'tag_id' => $tag->id
                    ));

                    $ProductTag->save();
                }
            }

            // remove all old tag
            foreach ($old_tags as $index => $tag_name)
            {
                // get the id of the tag
                $tag = Tag::where('name',$tag_name)
                    ->first();

                ProductTag::where('product_id', $this->id)
                    ->where('tag_id', $tag->id)
                    ->delete();

                $tag->count--;
                $tag->save();
            }
        }
    }

    public function getImage($second=false)
    {
        return $this->id .'/'. (!$second ? $this->image : $this->image_second);
    }

    public static function getProductColorSizeTable()
    {
        $product = new ProductColorSize;
        return $product->getTable();
    }

    public static function getProductDiscountTable()
    {
        $product = new ProductDiscount;
        return $product->getTable();
    }

    public static function getProductReleaseTable()
    {
        $product = new ProductNewReleases;
        return $product->getTable();
    }

    public static function getProductImageTable()
    {
        $product = new ProductImage;
        return $product->getTable();
    }

    public static function getProductTagsTable()
    {
        $product = new ProductTag;
        return $product->getTable();
    }

    public static function getProductTable()
    {
        $product = new Product;
        return $product->getTable();
    }

    private function currentDateTime()
    {
        return Carbon::now('Asia/Jakarta')->format("Y-m-d H:i:s");
    }

    public static function getProductList($isPaginate=true, $action='', $order='published_on', $orderBy='DESC', $limit=5, $useDescription=false)
    {
        $currentDateTime = Carbon::now('Asia/Jakarta')->format("Y-m-d H:i:s");
        //$currentDateTime = Carbon::now('Asia/Jakarta')->format("Y-m-d H:i:s");

        $datas = self::selectRaw("id, code, title, slug, image, image_second, price, ".($useDescription ? 'description,' : '')."
            (SELECT price FROM ".getTableName(with(new ProductDiscount)->getTable())." pd WHERE pd.product_id = ".getTableName(with(new Product)->getTable()).".id AND ((pd.date_start IS NULL OR pd.date_start <= '".$currentDateTime."') AND (pd.date_end IS NULL OR pd.date_end >= '".$currentDateTime."')) ORDER BY pd.priority ASC LIMIT 1 ) AS discount,
            (SELECT id FROM ".getTableName(with(new ProductNewReleases)->getTable())." pnr WHERE pnr.product_id = ".getTableName(with(new Product)->getTable()).".id AND ((pnr.date_start IS NULL OR pnr.date_start <= '".$currentDateTime."') AND (pnr.date_end IS NULL OR pnr.date_end >= '".$currentDateTime."')) ORDER BY pnr.priority ASC LIMIT 1 ) AS newRelease,
            (SELECT id FROM ".getTableName(with(new ProductHot)->getTable())." ph WHERE ph.product_id = ".getTableName(with(new Product)->getTable()).".id AND ((ph.date_start IS NULL OR ph.date_start <= '".$currentDateTime."') AND (ph.date_end IS NULL OR ph.date_end >= '".$currentDateTime."')) ORDER BY ph.priority ASC LIMIT 1 ) AS hot
            ");

        $datas->where('status',1);
        $datas->where('published_on','<=',$currentDateTime);

        switch($action){
            case "in-stock";
                $urlActive .= '/'.$action;
                $pageTitle .= " - In Stock";
                $datas->having("quantity",'>',0);
                break;

            case "search";
                $urlActive .= '/'.$action;
                $pageTitle .= " - Search";
                $q = $request->get('q');
                $datas->whereIn('id', function($query) use($q){
                    $query->select('product_id')
                        ->from(with(new ProductTag)->getTable())
                        ->whereIn('tag_id', function($query2) use($q){
                            $query2->select('id')
                                ->from(with(new Tag)->getTable())
                                ->where('name', 'LIKE', '%' .$q. '%')
                                ->orderBy('count','desc');
                        });
                });
                break;

            case "new-arrivals";
                $datas->whereIn('id', function($query) use($currentDateTime){
                    $query->select('product_id')
                        ->from(with(new ProductNewReleases)->getTable())
                        ->where(function ($query2) use($currentDateTime) {
                            $query2->where('date_start',null)
                                ->orWhere('date_start','<=',$currentDateTime);
                        })
                        ->where(function ($query3) use($currentDateTime) {
                            $query3->where('date_end',null)
                                ->orWhere('date_end','>=',$currentDateTime);
                        })
                        ->orderBy('priority','asc');
                });
                break;

            case "sale";
                $datas->whereIn('id', function($query) use($currentDateTime){
                    $query->select('product_id')
                        ->from(with(new ProductDiscount)->getTable())
                        ->where(function ($query2) use($currentDateTime) {
                            $query2->where('date_start',null)
                                ->orWhere('date_start','<=',$currentDateTime);
                        })
                        ->where(function ($query3) use($currentDateTime){
                            $query3->where('date_end',null)
                                ->orWhere('date_end','>=',$currentDateTime);
                        })
                        ->orderBy('priority','asc');
                });
                break;

            case "best-seller";
                $datas->whereIn('id', function($query) use($currentDateTime){
                    $query->select('product_id')
                        ->from(with(new ProductHot)->getTable())
                        ->where(function ($query2) use($currentDateTime) {
                            $query2->where('date_start',null)
                                ->orWhere('date_start','<=',$currentDateTime);
                        })
                        ->where(function ($query3) use($currentDateTime){
                            $query3->where('date_end',null)
                                ->orWhere('date_end','>=',$currentDateTime);
                        })
                        ->orderBy('priority','asc');
                });
                break;

            case "category";
                $category = Category::whereStatus(1)->whereSlug($cat)->firstOrFail();
                $urlActive .= '/'.$action.'/'.$category->slug;
                $sidebarActive = $action . $category->slug;
                $pageTitle .= " - Category - " . $category->title;
                $arrCatTopMenu = [7,9,10];
                if (in_array($category->id, $arrCatTopMenu)){
                    $menu = 'cat-'.$category->id;
                }

                $datas->whereIn('id', function($query) use ($category) {
                    $query->select('product_id')
                        ->from(with(new ProductCategory)->getTable())
                        ->where('category_id',$category->id);
                });
                break;

            case "collection";
                $collection = Collection::whereStatus(1)->whereSlug($cat)->firstOrFail();
                $urlActive .= '/'.$action.'/'.$collection->slug;
                $sidebarActive = $action . $collection->slug;
                $pageTitle .= " - Collection - " . $collection->title;

                $datas->whereIn('id', function($query) use ($collection) {
                    $query->select('product_id')
                        ->from(with(new ProductCollection)->getTable())
                        ->where('collection_id',$collection->id);
                });
                break;

            case "color";
                $colorGroup = ColorGroup::whereSlug($cat)->firstOrFail();
                $urlActive .= '/'.$action.'/'.$colorGroup->slug;
                $colors = $colorGroup->color;
                $sidebarActive = $action . $colorGroup->slug;
                $pageTitle .= " - Color - " . $colorGroup->title;
                $datas->whereIn('id', function($query) use ($colorGroup, $colors) {
                    $query->select('product_id')
                        ->from(with(new ProductColorSize)->getTable())
                        ->where('color_id','=', 0);
                        foreach ($colors as $tr){
                            $query->orWhere('color_id', $tr->id);
                        }
                });
                break;

            default:

                break;
        }
        $datas->orderBy($order, $orderBy);

        if ($isPaginate){
            return $datas->paginate($limit);
        } else {
            return $datas->limit($limit)->get();
        }

    }

    public function addToCart($qty, $size_id, $color_id, $voucher_id=0, $voucher_nominal=0, $voucher_code='')
    {
        $image = $this->images()->select('image')->where('color_id',$color_id)->orderBy('sort_order', 'ASC')->first()->image;
        if (Auth::check()) {
            //Cart::store(auth()->user()->id);
            Cart::restore(auth()->user()->id);
            $add = Cart::add(['id' => $this->id, 'name' => $this->title, 'qty' => $qty, 'price' => ($this->discount > 0 ? $this->discount : $this->price), 'options' => ['size' => $size_id, 'color' => $color_id, 'image'=>$image, 'weight'=>$this->weight, 'slug' => $this->slug, 'is_discount'=>($this->discount > 0 ? 1 : 0)]]);
            Cart::store(auth()->user()->id);
        } else {
            $add = Cart::add(['id' => $this->id, 'name' => $this->title, 'qty' => $qty, 'price' => ($this->discount > 0 ? $this->discount : $this->price), 'options' => ['voucher_id' => $voucher_id,'voucher_nominal' => $voucher_nominal,'voucher_code' => $voucher_code, 'size' => $size_id, 'color' => $color_id, 'image'=>$image, 'weight'=>$this->weight, 'slug' => $this->slug, 'is_discount'=>($this->discount > 0 ? 1 : 0)]]);
        }

        return $add;
    }

    public static function checkStock($products, $json=true)
    {
        $arrProducts = [];
        foreach ($products['product_id'] as $k => $product_id){
            $arrProducts[$product_id] = isset($arrProducts[$product_id]) ? ($arrProducts[$product_id] + $products['qty'][$k]) : $products['qty'][$k];
        }

        $arrError = [];
        if (count($arrProducts)){
            foreach ($arrProducts as $product_id => $qty){
                $qty = convertDecimal($qty, false);
                $product = Product::select('jasper_name', 'name')->whereId($product_id)->firstOrFail();

                $stock = Product::getProductStock($product->jasper_name);

                if ($qty > $stock){
                    $arrError[] = 'Stok Paket Data ' . $product->name . ' tidak mencukupi. Saat ini tersisa ' . $stock;
                }
            }
        }

        if ($json){
            return response()->json(['errors' => $arrError]);
        } else {
            return $arrError;
        }

    }
}
