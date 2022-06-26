<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Slideshow;
use App\SlideshowMeta;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class SlideshowController extends Controller
{
    private $controller = 'slideshow';
    private $description = '';
    private $icon = 'flaticon2-image-file';

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
            'sort_order' => 'Urutan',
            'file' => 'Gambar'
        );
    }

    private function arrTarget(){
        return array('_self'=>'_self', '_blank' => '_blank');
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
        $utils['options']['target'] = $this->arrTarget();
        $utils['options']['meta'] = $this->arrMeta();
        $utils['logo'] = Slideshow::arrLogo();
        
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
            'sort_order' => 'required',
            'sort_order' => 'required',
            'file' => 'required|image|mimes:jpeg,png,jpg|max:500',
        ];

        Validator::make($request->all(), $arrValidates, [], $this->arrNiceName())->validate();

        $arrSaved = [
            'title'      => $request->get('title'),
            'title_id'      => $request->get('title_id'),
            'url'      => $request->get('url'),
            'target'      => $request->get('target'),
            'sort_order'      => $request->get('sort_order'),
            'logo'      => $request->get('logo'),
            'status'     => $request->get('status')
        ];

        $file = $request->file;
        $destinationPath = 'public/images/'.$this->controller.'s';

        $image = Image::make($file);
        $isJpg = $image->mime() === 'image/jpg' || $image->mime() === 'image/jpeg';
        if($isJpg && $image->exif('Orientation')){
            $image = orientate($image, $image->exif('Orientation'));
        }

        $image->stream(); // <-- Key point
        $destinationPath = $destinationPath.'/'.$file->getClientOriginalName();
        Storage::put($destinationPath, $image);

        $arrSaved['image'] = $file->getClientOriginalName();

        $slideshow = Slideshow::create($arrSaved);
        $this->updateMeta($slideshow, $request->get('meta'));

        return redirect()->route($this->controller.'.index')->with('status', __( 'main.data_has_been_added', ['page' => $slideshow->title] ) );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Slideshow  $slideshow
     * @return \Illuminate\Http\Response
     */
    public function show(Slideshow $slideshow)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Slideshow  $slideshow
     * @return \Illuminate\Http\Response
     */
    public function edit(Slideshow $slideshow)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-update')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        if ($slideshow->status === 99){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );           
        }

        $utils['action'] = __('main.edit');
        $utils['options']['target'] = $this->arrTarget();
        $utils['options']['meta'] = $this->arrMeta();
        $utils['options']['meta_selected'] = $slideshow->metas()->pluck('value', 'title')->toArray();
        $utils['logo'] = Slideshow::arrLogo();
        return view('admin.'.$this->controller.'.edit', compact('slideshow', 'utils'))->with(array('controller' => $this->controller, 'title' => $this->title(), 'description' => $this->description, 'icon' => $this->icon));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Slideshow  $slideshow
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Slideshow $slideshow)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-update')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        if ($slideshow->status === 99){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );           
        }

        $arrValidates = [
            'sort_order' => 'required',
            'sort_order' => 'required'
        ];
        Validator::make($request->all(), $arrValidates, [], $this->arrNiceName())->validate();

        $arrSaved = [
            'title'      => $request->get('title'),
            'title_id'      => $request->get('title_id'),
            'url'      => $request->get('url'),
            'target'      => $request->get('target'),
            'sort_order'      => $request->get('sort_order'),
            'logo'      => $request->get('logo'),
            'status'     => $request->get('status')
        ];

        $file = $request->file;

        if ($file) {
            $destinationPath = 'public/images/'.$this->controller.'s';

            if(!is_null($slideshow->image) && File::exists($destinationPath.'/'.$slideshow->image)) {
                unlink($destinationPath.'/'.$slideshow->image);
            }

            $image = Image::make($file);
            $isJpg = $image->mime() === 'image/jpg' || $image->mime() === 'image/jpeg';
            if($isJpg && $image->exif('Orientation')){
                $image = orientate($image, $image->exif('Orientation'));
            }

            $image->stream(); // <-- Key point
            $destinationPath = $destinationPath.'/'.$file->getClientOriginalName();
            Storage::put($destinationPath, $image);

            $slideshow->image = $file->getClientOriginalName();
        }
        
        $slideshow->update($arrSaved);
        $this->updateMeta($slideshow, $request->get('meta'));

        return redirect()->route($this->controller.'.index')->with('status', __( 'main.data_has_been_updated', ['page' => $slideshow->title] ) );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Slideshow  $slideshow
     * @return \Illuminate\Http\Response
     */
    public function destroy(Slideshow $slideshow)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-delete')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        if ($slideshow->status === 99){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );           
        }

        $slideshow->status = 99;
        $slideshow->save();

        return redirect()->route($this->controller.'.index')->with('status', __( 'main.data_has_been_deleted', ['page' => $slideshow->title] ) );
    }

    public function getData(Request $request)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-index')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        $where = [];
        $where[] = ['status', '<>', 99];   

        $rows = Slideshow::select([
            'id',
            'title',
            'sort_order',
            'status',
            'image'
        ])
        ->where($where);

        return Datatables::of($rows)
            ->addIndexColumn()
            ->addColumn('image', function ($row) {
                return route(config('imagecache.route'), ['template' => 'medium', 'filename' => Slideshow::getImage($row) ]);
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
            ->make(true);
    }

    private function arrMeta()
    {
        return [
            'str1' => 'Subtitle 1',
            'str2' => 'Title',
            'str3' => 'Subtitle 2',
            'str4' => 'Button Text',
        ];
    }

    private function updateMeta($slideshow, $metas)
    {
        $slideshow->metas()->delete();
        if (is_array($metas) && count($metas)){
            foreach ($metas as $title => $value){
                $arrInsert[] = [
                    'slideshow_id' => $slideshow->id,
                    'title' => $title,
                    'value' => $value
                ];
            }
            SlideshowMeta::insert($arrInsert);
        }
    }
}
