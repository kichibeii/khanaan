<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Cart;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'reg_name' => ['required', 'string', 'max:255'],
            'reg_email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'reg_username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'reg_wa' => ['required', 'string', 'max:20'],
            //'reg_password' => ['required', 'string', 'min:8', 'confirmed'],
            'reg_password' => ['required', 'string', 'min:8'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['reg_name'],
            'email' => $data['reg_email'],
            'username' => $data['reg_username'],
            'wa_number' => $data['reg_wa'],
            'status' => 1,
            'password' => Hash::make($data['reg_password']),
            'currency' => currentCurrency(),
            'language' => LaravelLocalization::getCurrentLocale(),
        ]);

        $user->syncRoles(['member']);

        Cart::store($user->id);

        return $user;
    }
}
