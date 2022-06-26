<?php

namespace App\Http\Controllers\Admin;

use App\ColorGroup;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Validator;
use Str;

class ColorGroupController extends Controller
{
    private $controller = 'group_color';
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
            'title' => 'Nama',
            'slug' => 'Slug',
            'color_hex' => 'Kode Hex',
            'sort_order' => 'Urutan',
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
            'title'     => 'required|max:80',
            'slug' => 'required|min:3|unique:color_groups,slug',
            'color_hex'    => 'required|alpha_num|max:6|unique:color_groups,color_hex',
            'sort_order' => 'required',
        ];

        Validator::make($request->all(), $arrValidates, [], $this->arrNiceName())->validate();

        $arrSaved = [
            'title'      => $request->get('title'),
            'slug'  => Str::slug($request->get('slug'), '-'),
            'color_hex'  => $request->get('color_hex'),
            'sort_order'  => $request->get('sort_order'),
            'status'     => $request->get('status')
        ];

        $group_color = ColorGroup::create($arrSaved);

        return redirect()->route($this->controller.'.index')->with('status', __( 'main.data_has_been_added', ['page' => $group_color->title] ) );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ColorGroup  $colorGroup
     * @return \Illuminate\Http\Response
     */
    public function show(ColorGroup $colorGroup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ColorGroup  $colorGroup
     * @return \Illuminate\Http\Response
     */
    public function edit(ColorGroup $group_color)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-update')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        if ($group_color->status === 99){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );           
        }

        $utils['action'] = __('main.edit');
        return view('admin.'.$this->controller.'.edit', compact('group_color', 'utils'))->with(array('controller' => $this->controller, 'title' => $this->title(), 'description' => $this->description, 'icon' => $this->icon));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ColorGroup  $colorGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ColorGroup $group_color)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-update')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        if ($group_color->status === 99){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );           
        }

        $arrValidates = [
            'title'     => 'required|max:80',
            'slug' => 'required|min:3|unique:color_groups,slug,'.$group_color->id,
            'color_hex'    => 'required|alpha_num|max:6|unique:color_groups,color_hex,'.$group_color->id,
            'sort_order' => 'required'
        ];
        Validator::make($request->all(), $arrValidates, [], $this->arrNiceName())->validate();

        $arrSaved = [
            'title'      => $request->get('title'),
            'slug'  => Str::slug($request->get('slug'), '-'),
            'color_hex'  => $request->get('color_hex'),
            'sort_order'  => $request->get('sort_order'),
            'status'     => $request->get('status')
        ];
        
        $group_color->update($arrSaved);

        return redirect()->route($this->controller.'.index')->with('status', __( 'main.data_has_been_updated', ['page' => $group_color->title] ) );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ColorGroup  $colorGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(ColorGroup $colorGroup)
    {
        //
    }

    public function getData(Request $request)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-index')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }
        
        $arrColumns = [1=>'id', 2=>'title', 3=>'sort_order', 4=>'status'];

        $draw = $request->get('draw');
        $start = $request->get('start');
        $length = $request->get('length');
        $orderBy = $request->get('order')[0]['column'];
        $orderAD = $request->get('order')[0]['dir'];
        $q = $request->get('search')['value'];

        $where = [];
        $where[] = ['status', '<>', 99];   
        if ($q !== null){
            $where[] = ['title', 'LIKE', '%'.$q.'%'];   
        }

        $rows = ColorGroup::select([
            'id',
            'title',
            'color_hex',
            'sort_order',
            'status'
        ])
        ->where($where);

        $totalRows = $rows->get()->count();
        $rows = $rows->offset($start)->take($length)
            ->orderBy($arrColumns[$orderBy],$orderAD)
            ->get();

        return Datatables::of($rows)
            ->addIndexColumn()
            ->skipPaging()
            ->with([
                'recordsTotal' => $totalRows,
                'recordsFiltered' => $totalRows,
            ])
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
            ->make();
    }
}
