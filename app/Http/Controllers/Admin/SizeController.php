<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Size;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class SizeController extends Controller
{
    private $controller = 'sizes';
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
            'file' => 'Gambar'
        );
    }

    private function arrValidate($action="create", $id=0)
    {
        $userLoged = auth()->user();
        $arrValidates = [
            'name' => 'required|string|max:255'
        ];

        if ($action === "create"){
            $arrValidates['file'] = 'required|image|mimes:jpeg,png,jpg|max:500';
        }

        return $arrValidates;
    }

    private function arrSaved($request, $action="create", $client=false)
    {
        $arrSaved = [
            'title' => $request->name
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

        $size = Size::create($this->arrSaved($request));

        $file = $request->file;
        $destinationPath = 'public/images/'.$this->controller;

        $image = Image::make($file);
        $isJpg = $image->mime() === 'image/jpg' || $image->mime() === 'image/jpeg';
        if($isJpg && $image->exif('Orientation')){
            $image = orientate($image, $image->exif('Orientation'));
        }

        $image->stream(); // <-- Key point
        $destinationPath = $destinationPath.'/'.$file->getClientOriginalName();
        Storage::put($destinationPath, $image);

        $size->image = $file->getClientOriginalName();
        $size->save();

        return redirect()->route($this->controller.'.index')->with('status', __( 'main.data_has_been_added', ['page' => $size->title] ) );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Size  $size
     * @return \Illuminate\Http\Response
     */
    public function show(Size $size)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Size  $size
     * @return \Illuminate\Http\Response
     */
    public function edit(Size $size)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-update')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        $utils['action'] = __('main.edit');
        return view('admin.'.$this->controller.'.edit', compact('size', 'utils'))->with(array('controller' => $this->controller, 'title' => $this->title(), 'description' => $this->description, 'icon' => $this->icon));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Size  $size
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Size $size)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-update')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        Validator::make($request->all(), $this->arrValidate('update'), [], $this->arrNiceName())->validate();

        $size->update($this->arrSaved($request));

        $file = $request->file;

        if ($file) {
            $destinationPath = 'public/images/'.$this->controller;

            if(!is_null($size->image) && File::exists($destinationPath.'/'.$size->image)) {
                unlink($destinationPath.'/'.$size->image);
            }

            $image = Image::make($file);
            $isJpg = $image->mime() === 'image/jpg' || $image->mime() === 'image/jpeg';
            if($isJpg && $image->exif('Orientation')){
                $image = orientate($image, $image->exif('Orientation'));
            }

            $image->stream(); // <-- Key point
            $destinationPath = $destinationPath.'/'.$file->getClientOriginalName();
            Storage::put($destinationPath, $image);

            $size->image = $file->getClientOriginalName();
            $size->save();
        }

        return redirect()->route($this->controller.'.index')->with('status', __( 'main.data_has_been_updated', ['page' => $size->title] ) );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Size  $size
     * @return \Illuminate\Http\Response
     */
    public function destroy(Size $size)
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

        $rows = Size::select([
            'id',
            'title',
            'image'
        ])
        ->where($where);

        return Datatables::of($rows)
            ->addIndexColumn()
            ->addColumn('image', function ($row) {
                return route(config('imagecache.route'), ['template' => 'medium', 'filename' => $row->getImage() ]);
            })
            ->addColumn('canEdit', function ($row) {
                return auth()->user()->can($this->controller.'-update');
            })
            ->addColumn('editUrl', function ($row) {
                return route($this->controller.'.edit', $row->id);
            })
            ->make();
    }

    public function getDataArray(Request $request)
    {
        $rows = Size::getDataArray(false, $request->term);

        $arr = array();
        if (count($rows)) {
            foreach ($rows as $k => $v) {
                $arr[] = ['id' => $k, 'text' => $v ];
            }
        }
        return response()->json($arr);
    }

    public function getDetail(Size $size)
    {
        return response()->json([
            'id' => $size->id, 
            'text' => $size->title
        ]);
    }
}
