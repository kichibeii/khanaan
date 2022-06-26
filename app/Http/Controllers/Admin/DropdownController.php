<?php

namespace App\Http\Controllers\Admin;

use App\Dropdown;
use App\DropdownItem;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Validator;
use Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class DropdownController extends Controller
{
    private $controller = 'dropdown';
    private $description = '';
    private $icon = 'flaticon-user-settings';

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
            'code' => 'Kode ' . __('main.'.$this->controller),
            'title' => 'Nama ' . __('main.'.$this->controller),
        );
    }

    private function arrNiceNameItem()
    {
        return array(
            'sort_order' => 'Urutan',
            'title' => 'Nama',
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
            'code' => 'required|string|max:30|unique:dropdowns',
            'title' => 'required'
        ];
        Validator::make($request->all(), $arrValidates, [], $this->arrNiceName())->validate();

        $dropdown = Dropdown::create([
            'code' => strtolower($request->code),
            'title' => $request->title,
            'description' => $request->description
        ]);

        return redirect()->route($this->controller.'.index')->with('status', __( 'main.data_has_been_added', ['page' => $dropdown->title] ) );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Dropdown  $dropdown
     * @return \Illuminate\Http\Response
     */
    public function show(Dropdown $dropdown)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Dropdown  $dropdown
     * @return \Illuminate\Http\Response
     */
    public function edit(Dropdown $dropdown)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-update')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        $utils['action'] = __('main.edit');
        return view('admin.'.$this->controller.'.edit', compact('dropdown', 'utils'))->with(array('controller' => $this->controller, 'title' => $this->title(), 'description' => $this->description, 'icon' => $this->icon));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Dropdown  $dropdown
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dropdown $dropdown)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-update')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        $arrValidates = [
            'code' => 'required|string|max:30|unique:dropdowns,code,'.$dropdown->id,
            'title' => 'required'
        ];
        Validator::make($request->all(), $arrValidates, [], $this->arrNiceName())->validate();

        $dropdown->code = strtolower($request->code);
        $dropdown->title = $request->title;
        $dropdown->description = $request->description;
        $dropdown->save();

        return redirect()->route($this->controller.'.index')->with('status', __( 'main.data_has_been_updated', ['page' => $dropdown->title] ) );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Dropdown  $dropdown
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dropdown $dropdown)
    {
        //
    }

    public function item(Dropdown $dropdown)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-update')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        $utils['action'] = 'Opsi';
        return view('admin.'.$this->controller.'.item', compact('dropdown', 'utils'))->with(array('controller' => $this->controller, 'title' => $this->title(), 'description' => $this->description, 'icon' => $this->icon));
    }

    public function itemCreate(Dropdown $dropdown)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-update')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        $utils['action'] = __('main.add_new') . ' Opsi';
        return view('admin.'.$this->controller.'.item-create', compact('dropdown', 'utils'))->with(array('controller' => $this->controller, 'title' => $this->title(), 'description' => $this->description, 'icon' => $this->icon));
    }

    public function itemStore(Request $request, Dropdown $dropdown)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-update')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        $arrValidates = [
            'sort_order' => 'required',
            'title' => 'required'
        ];
        Validator::make($request->all(), $arrValidates, [], $this->arrNiceNameItem())->validate();

        $dropdownItem = DropdownItem::create([
            'dropdown_id' => $dropdown->id,
            'title' => $request->title,
            'slug'  => Str::slug($request->title, '-'),
            'sort_order' => $request->sort_order,
            'status' => $request->status
        ]);

        $file = $request->file;

        if ($file) {
            $destinationPath = 'public/images/'.$this->controller.'s';

            $image = Image::make($file);
            $isJpg = $image->mime() === 'image/jpg' || $image->mime() === 'image/jpeg';
            if($isJpg && $image->exif('Orientation')){
                $image = orientate($image, $image->exif('Orientation'));
            }

            $image->stream(); // <-- Key point
            $destinationPath = $destinationPath.'/'.$file->getClientOriginalName();
            Storage::put($destinationPath, $image);

            $dropdownItem->image = $file->getClientOriginalName();
            $dropdownItem->save();
        }

        return redirect()->route($this->controller.'.item', $dropdown->id)->with('status', __( 'main.data_has_been_added', ['page' => $dropdownItem->title] ) );
    }

    public function itemEdit(Dropdown $dropdown, DropdownItem $item)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-update')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        $utils['action'] = __('main.edit');
        return view('admin.'.$this->controller.'.item-edit', compact('dropdown', 'item', 'utils'))->with(array('controller' => $this->controller, 'title' => $this->title(), 'description' => $this->description, 'icon' => $this->icon));
    }

    public function itemUpdate(Request $request, Dropdown $dropdown, DropdownItem $item)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-update')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        $arrValidates = [
            'sort_order' => 'required',
            'title' => 'required'
        ];
        Validator::make($request->all(), $arrValidates, [], $this->arrNiceNameItem())->validate();

        $item->title = $request->title;
        $item->sort_order = $request->sort_order;
        $item->status = $request->status;
        $item->slug  = Str::slug($request->title, '-');

        $file = $request->file;

        if ($file) {
            $destinationPath = 'public/images/'.$this->controller.'s';

            $image = Image::make($file);
            $isJpg = $image->mime() === 'image/jpg' || $image->mime() === 'image/jpeg';
            if($isJpg && $image->exif('Orientation')){
                $image = orientate($image, $image->exif('Orientation'));
            }

            $image->stream(); // <-- Key point
            $destinationPath = $destinationPath.'/'.$file->getClientOriginalName();
            Storage::put($destinationPath, $image);

            $item->image = $file->getClientOriginalName();
        }

        $item->save();

        return redirect()->route($this->controller.'.item', $dropdown->id)->with('status', __( 'main.data_has_been_updated', ['page' => $item->title] ) );
    }

    public function getData(Request $request)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-index')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        $rows = Dropdown::select([
            'id',
            'code',
            'title',
            'description'
        ]);

        return Datatables::of($rows)
            ->addIndexColumn()
            ->addColumn('totalItem', function ($row) {
                return $row->items()->count();
            })    
            ->addColumn('canEdit', function ($row) {
                return auth()->user()->can($this->controller.'-update');
            })
            ->addColumn('editUrl', function ($row) {
                return route($this->controller.'.edit', $row->id);
            })
            ->addColumn('itemUrl', function ($row) {
                return route($this->controller.'.item', $row->id);
            })
            ->addColumn('canDelete', function ($row) {
                return auth()->user()->can($this->controller.'-delete');
            })
            ->addColumn('deleteUrl', function ($row) {
                return route($this->controller.'.destroy', $row->id);
            })
            ->make(true);
    }

    public function itemGetData(Request $request)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-update')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }
        
        $rows = DropdownItem::select([
            'id',
            'status',
            'title',
            'status',
            'dropdown_id',
            'sort_order',
            'image'
        ]);
        $rows->where('dropdown_id', $request->get('dropdown_id'));

        return Datatables::of($rows)
            ->addIndexColumn()
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
                return route($this->controller.'.itemEdit', ['item'=>$row->id, 'dropdown'=>$row->dropdown_id]);
            })
            ->make(true);
    }
}
