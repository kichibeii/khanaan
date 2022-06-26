<?php

namespace App\Http\Controllers\Admin;

use App\Brand;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Validator;
use Str;

class BrandController extends Controller
{
    private $controller = 'brand';
    private $description = '';
    private $icon = 'flaticon-squares-1';

    public function __construct()
    {
        $this->middleware('auth');
    }

    private function title()
    {
        return __('main.'.$this->controller);
    }

    private function arrNiceName()
    {
        return array(
            'name' => 'Nama',
            'slug' => 'Slug'
        );
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

        return view('admin.'.$this->controller.'.index')->with(array('controller' => $this->controller, 'title' => $this->title(), 'description' => $this->description, 'icon' => $this->icon));
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

        $arrValidates = [
            'name'     => 'required',
            'slug' => 'required|min:3|unique:brands,slug'
        ];

        Validator::make($request->all(), $arrValidates, [], $this->arrNiceName())->validate();

        $arrSaved = [
            'name'      => $request->get('name'),
            'slug'  => Str::slug($request->get('slug'), '-'),
            'status'     => $request->get('status')
        ];

        $brand = Brand::create($arrSaved);

        return redirect()->route($this->controller.'.index')->with('status', __( 'main.data_has_been_added', ['page' => $brand->name] ) );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(Brand $brand)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-update')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        if ($brand->status === 99){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );           
        }

        $utils['action'] = __('main.edit');
        return view('admin.'.$this->controller.'.edit', compact('brand', 'utils'))->with(array('controller' => $this->controller, 'title' => $this->title(), 'description' => $this->description, 'icon' => $this->icon));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Brand $brand)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-update')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        if ($brand->status === 99){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );           
        }

        $arrValidates = [
            'name'     => 'required',
            'slug' => 'required|min:3|unique:brands,slug,'.$brand->id
        ];
        Validator::make($request->all(), $arrValidates, [], $this->arrNiceName())->validate();

        $arrSaved = [
            'name'      => $request->get('name'),
            'slug'  => Str::slug($request->get('slug'), '-'),
            'status'     => $request->get('status')
        ];
        
        $brand->update($arrSaved);

        return redirect()->route($this->controller.'.index')->with('status', __( 'main.data_has_been_updated', ['page' => $brand->name] ) );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        //
    }

    public function getData(Request $request)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-index')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        $where = [];
        $where[] = ['status', '<>', 99];   

        $rows = Brand::select([
            'id',
            'name',
            'status'
        ])
        ->where($where);

        return Datatables::of($rows)
            ->addIndexColumn()
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
            ->make(true);
    }

    public function getDataArray(Request $request)
    {
        $rows = Brand::getDataArray(false, $request->term);

        $arr = array();
        if (count($rows)) {
            foreach ($rows as $k => $v) {
                $arr[] = ['id' => $k, 'text' => $v ];
            }
        }
        return response()->json($arr);
    }

    public function getDetail(Brand $brand)
    {
        return response()->json([
            'id' => $brand->id, 
            'text' => $brand->name
        ]);
    }
}
