<?php

namespace App\Http\Controllers\Admin;

use App\Color;
use App\ColorGroup;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Validator;
use Str;

class ColorController extends Controller
{
    private $controller = 'color';
    private $description = '';
    private $icon = 'flaticon-more';

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
        $utils['options']['group_color'] = ColorGroup::where('status', 1)->orderBy('sort_order', 'ASC')->pluck('title', 'id')->toArray();

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
            'color_hex'    => 'required|alpha_num|max:6|unique:colors,color_hex',
            'sort_order' => 'required',
        ];

        Validator::make($request->all(), $arrValidates, [], $this->arrNiceName())->validate();

        $arrSaved = [
            'title'      => $request->get('title'),
            'color_hex'  => $request->get('color_hex'),
            'sort_order'  => $request->get('sort_order'),
            'status'     => $request->get('status')
        ];

        $color = Color::create($arrSaved);
        $color->color_group_color()->sync($request->get('group_color'));

        return redirect()->route($this->controller.'.index')->with('status', __( 'main.data_has_been_added', ['page' => $color->title] ) );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Color  $color
     * @return \Illuminate\Http\Response
     */
    public function show(Color $color)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Color  $color
     * @return \Illuminate\Http\Response
     */
    public function edit(Color $color)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-update')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        if ($color->status === 99){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );           
        }

        $utils['action'] = __('main.edit');
        $utils['options']['group_color'] = ColorGroup::where('status', 1)->orderBy('sort_order', 'ASC')->pluck('title', 'id')->toArray();
        $utils['options']['groupColorSelecteds'] = $color->color_group_color()->pluck('color_group_id')->toArray();
        return view('admin.'.$this->controller.'.edit', compact('color', 'utils'))->with(array('controller' => $this->controller, 'title' => $this->title(), 'description' => $this->description, 'icon' => $this->icon));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Color  $color
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Color $color)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-update')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        if ($color->status === 99){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );           
        }

        $arrValidates = [
            'title'     => 'required|max:80',
            'color_hex'    => 'required|alpha_num|max:6|unique:colors,color_hex,'.$color->id,
            'sort_order' => 'required'
        ];
        Validator::make($request->all(), $arrValidates, [], $this->arrNiceName())->validate();

        $arrSaved = [
            'title'      => $request->get('title'),
            'color_hex'  => $request->get('color_hex'),
            'sort_order'  => $request->get('sort_order'),
            'status'     => $request->get('status')
        ];
        
        $color->update($arrSaved);

        $color->color_group_color()->sync($request->get('group_color'));
        return redirect()->route($this->controller.'.index')->with('status', __( 'main.data_has_been_updated', ['page' => $color->title] ) );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Color  $color
     * @return \Illuminate\Http\Response
     */
    public function destroy(Color $color)
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

        $rows = Color::select([
            'id',
            'title',
            'color_hex',
            'sort_order',
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
}
