<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Page;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Validator;
use Str;

class PageController extends Controller
{
    private $controller = 'page';
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

    private function arrNiceName()
    {
        return array(
            'title' => 'Judul',
            'title_id' => 'Judul (ID)',
            'slug' => 'Slug',
            'description' => 'Konten',
            'description_id' => 'Konten (ID)',
        );
    }

    private function arrValidate($action="create", $id=0)
    {
        $arrValidates = [
            'description' => 'required',
            'title' => 'required',
            'title_id' => 'required',
        ];

        if (!empty($id)){
            //$arrValidates['title'] = 'required|min:3|unique:pages,title,'.$id;
            $arrValidates['slug'] = 'required|min:3|unique:pages,slug,'.$id;
        } else {
            //$arrValidates['title'] = 'required|min:3|unique:pages,title';
            $arrValidates['slug'] = 'required|min:3|unique:pages,slug';
        }

        return $arrValidates;
    }

    private function arrSaved($request, $action="create")
    {
        $arrSaved = [
            'title'      => $request->get('title'),
            'title_id'      => $request->get('title_id'),
            'slug'  => Str::slug($request->get('slug'), '-'),
            'sort_order'      => $request->get('sort_order'),
            'description'      => $request->get('description'),
            'description_id'      => $request->get('description_id'),
            'status'     => $request->get('status')
        ];

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

        Validator::make($request->all(), $this->arrValidate(), [], $this->arrNiceName())->validate();

        $page = Page::create($this->arrSaved($request));

        return redirect()->route($this->controller.'.index')->with('status', __( 'main.data_has_been_added', ['page' => $page->title] ) );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function show(Page $page)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-update')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        if ($page->status === 99){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );           
        }
        
        $utils['action'] = __('main.view');
        
        return view('admin.'.$this->controller.'.show', compact('page', 'utils'))->with(array('controller' => $this->controller, 'title' => $this->title(), 'description' => $this->description, 'icon' => $this->icon));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-update')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        if ($page->status === 99){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );           
        }
        
        $utils['action'] = __('main.edit');
        
        return view('admin.'.$this->controller.'.edit', compact('page', 'utils'))->with(array('controller' => $this->controller, 'title' => $this->title(), 'description' => $this->description, 'icon' => $this->icon));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-update')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        if ($page->status === 99){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );           
        }
        
        Validator::make($request->all(), $this->arrValidate('edit', $page->id), [], $this->arrNiceName())->validate();
        
        $page->update($this->arrSaved($request));

        return redirect()->route($this->controller.'.index')->with('status', __( 'main.data_has_been_updated', ['page' => $page->title] ) );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-delete')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        if ($page->status === 99){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );           
        }

        $page->status = 99;
        $page->save();

        return redirect()->route($this->controller.'.index')->with('status', __( 'main.data_has_been_deleted', ['page' => $page->title] ) );
    }

    public function getData(Request $request)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-index')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        $where = [];
        $where[] = ['status', '<>', 99];   

        $rows = Page::select([
            'id',
            'title',
            'status',
            'sort_order'
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
            ->addColumn('canDelete', function ($row) {
                return auth()->user()->can($this->controller.'-delete');
            })
            ->addColumn('deleteUrl', function ($row) {
                return route($this->controller.'.destroy', $row->id);
            })
            ->addColumn('viewUrl', function ($row) {
                return route($this->controller.'.show', $row->id);
            })
            ->make(true);
    }
}
