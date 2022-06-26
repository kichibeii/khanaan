<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Validator;
use Intervention\Image\Facades\Image;

class DashboardController extends Controller
{
    private $controller = 'dashboard';
    private $description = '';
    private $icon = 'icon-grid';

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
            'upload' => 'File'
        );
    }

    public function index()
    {
        return view('admin.'.$this->controller.'.index')->with(array('controller' => $this->controller, 'title' => $this->title(), 'description' => $this->description, 'icon' => $this->icon));
    }

    public function imageUpload(Request $request)
    {
        $arrValidates = [
            'upload' => 'required|image|mimes:jpeg,png,jpg|max:500',
        ];

        $validator = Validator::make($request->all(), $arrValidates, [], $this->arrNiceName());
        if ($validator->fails()) {
            $resp = ['error' => ['message'=>$validator->errors()->first()]];
            return response()->json($resp, 400);
        }

        $file = $request->upload;
        $destinationPath = 'public/images/ckeditor';

        $image = Image::make($file);
        $isJpg = $image->mime() === 'image/jpg' || $image->mime() === 'image/jpeg';
        if($isJpg && $image->exif('Orientation')){
            $image = orientate($image, $image->exif('Orientation'));
        }

        $image->stream(); // <-- Key point
        $destinationPath = $destinationPath.'/'.$file->getClientOriginalName();
        Storage::put($destinationPath, $image);

        return response()->json([
            'url' => route(config('imagecache.route'), ['template' => 'banner', 'filename' => $file->getClientOriginalName()])
        ]);
    }
}
