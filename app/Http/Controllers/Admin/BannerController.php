<?php

namespace App\Http\Controllers\Admin;

use App\Banner;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class BannerController extends Controller
{
    private $controller = 'banner';
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

    private function bannerType()
    {
        return array(
            '1' => 'Banner 1 (570x807 px)',
            '2' => 'Banner 2 (570x391 px)',
            '3' => 'Banner 3 (570x391 px)',
            '4' => 'Banner 4 (570x807 px)',
            '5' => 'Banner 5 (1920x580 px)'
        );
    }

    private function arrNiceName()
    {
        return array(
            'title' => 'Judul',
            'title_id' => 'Judul (ID)',
            'banner_type' => 'Jenis Banner',
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
     * @param  \App\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function show(Banner $banner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function edit(Banner $banner)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-update')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        $utils['action'] = __('main.edit');
        $utils['options']['target'] = $this->arrTarget();
        $utils['options']['bannerType'] = $this->bannerType();
        return view('admin.'.$this->controller.'.edit', compact('banner', 'utils'))->with(array('controller' => $this->controller, 'title' => $this->title(), 'description' => $this->description, 'icon' => $this->icon));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Banner $banner)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-update')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        $arrValidates = [
            'title' => 'required',
            'banner_type' => 'required'
        ];
        Validator::make($request->all(), $arrValidates, [], $this->arrNiceName())->validate();

        $arrSaved = [
            'title'      => $request->get('title'),
            'title_id'      => $request->get('title_id'),
            'url'      => $request->get('url'),
            'target'      => $request->get('target'),
            'banner_type'      => $request->get('banner_type')
        ];

        $file = $request->file;

        if ($file) {
            $destinationPath = 'public/images/'.$this->controller.'s';

            if(!is_null($banner->image) && File::exists($destinationPath.'/'.$banner->image)) {
                unlink($destinationPath.'/'.$banner->image);
            }

            $image = Image::make($file);
            $isJpg = $image->mime() === 'image/jpg' || $image->mime() === 'image/jpeg';
            if($isJpg && $image->exif('Orientation')){
                $image = orientate($image, $image->exif('Orientation'));
            }

            $image->stream(); // <-- Key point
            $destinationPath = $destinationPath.'/'.$file->getClientOriginalName();
            Storage::put($destinationPath, $image);

            $banner->image = $file->getClientOriginalName();
        }
        
        $banner->update($arrSaved);

        return redirect()->route($this->controller.'.index')->with('status', __( 'main.data_has_been_updated', ['page' => $banner->title] ) );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Banner $banner)
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

        $rows = Banner::select([
            'id',
            'title',
            'banner_type',
            'image'
        ])
        ->where($where);

        $bannerType = $this->bannerType();

        return Datatables::of($rows)
            ->addIndexColumn()
            ->addColumn('image', function ($row) {
                return route(config('imagecache.route'), ['template' => 'medium', 'filename' => $row->image ]);
            })
            ->addColumn('canEdit', function () {
                return auth()->user()->can($this->controller.'-update');
            })
            ->addColumn('editUrl', function ($row) {
                return route($this->controller.'.edit', $row->id);
            })
            ->make(true);
    }
}
