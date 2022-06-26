<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    private $controller = 'profile';
    private $description = '';

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
            'name' => 'Kode ' . __('main.'.$this->controller),
            'display_name' => 'Nama ' . __('main.'.$this->controller),
        );
    }

    public function index()
    {
        $user = auth()->user();
        return view('auth.'.$this->controller,compact('user'))->with(array('controller' => $this->controller, 'title' => $this->title(), 'description' => $this->description));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(User $member)
    {
        //
    }

    public function edit(User $member)
    {
    
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $validate   = [
            'reg_name'      => 'required|max:191',
            'reg_username'  => 'required|alpha_num|max:20|unique:users,username,'.$user->id,
            'reg_email'     => 'required|email|max:191|unique:users,email,'.$user->id,
            'reg_wa'    => 'required|string|max:20',
        ];

        $user->name         = $request->reg_name;
        $user->username     = $request->reg_username;
        $user->email        = $request->reg_email;
        $user->wa_number    = $request->reg_wa;
        if($request->reg_password):
            $validate['reg_password'] = 'required|string|min:8';
        endif;

        request()->validate($validate);
        
        $file = $request->reg_file;
        if ($file) {
            $validate['reg_file'] = 'required|image|mimes:jpeg,png,jpg|max:500';
            request()->validate($validate);

            $destinationPath = 'public/images/users/'.$user->id;

            if(!is_null($user->image) && File::exists($destinationPath.'/'.$user->image)) {
                unlink($destinationPath.'/'.$user->image);
            }

            $image = Image::make($file);
            $isJpg = $image->mime() === 'image/jpg' || $image->mime() === 'image/jpeg';
            if($isJpg && $image->exif('Orientation')){
                $image = orientate($image, $image->exif('Orientation'));
            }

            $image->stream(); // <-- Key point
            $destinationPath = $destinationPath.'/'.$file->getClientOriginalName();
            Storage::put($destinationPath, $image);

            $user->image = $file->getClientOriginalName();
        }

        $user->save();
        return redirect()->route('profile')->with('success', __( 'main.data_has_been_updated',['page' => 'Profil']) );
    }

    public function destroy(User $member)
    {
        //
    }
}
