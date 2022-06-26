<?php

namespace App\Http\Controllers\Admin;

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
    private $icon = 'flaticon-network';

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
            'file' => 'Foto'
        );
    }

    public function edit()
    {
        $user = auth()->user();

        $utils['action'] = __('main.edit') .' '. $this->title();

        return view('admin.'.$this->controller.'.edit', compact('user', 'utils'))->with(array('controller' => $this->controller, 'title' => $this->title(), 'description' => $this->description, 'icon' => $this->icon));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        request()->validate([
            'name'     => 'required|max:191',
            'username' => 'required|alpha_num|max:20|unique:users,username,'.$user->id,
            'email'    => 'required|email|max:191|unique:users,email,'.$user->id,
        ]);

        $user->name     = $request->get('name');
        $user->username = $request->get('username');
        $user->email    = $request->get('email');
        $password       = $request->get('password');
        if($password != ''){
            $user->password = Hash::make($password);
        }

        $file = $request->file;

        if ($file) {
            $arrValidates = [
                'file' => 'required|image|mimes:jpeg,png,jpg|max:500',
            ];
    
            Validator::make($request->all(), $arrValidates, [], $this->arrNiceName())->validate();

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

        return redirect()->route($this->controller.'.edit')->with('status', __( 'main.data_has_been_updated', ['page' => 'Profil'] ) );
    }
}
