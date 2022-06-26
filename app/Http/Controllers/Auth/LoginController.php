<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Redirect;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'username';
    }

    protected function authenticated($request, $user)
    {
        //$protocol = str_replace(config('app.base_domain'), '', config('app.url'));
        $protocol = 'http://';
        
        if($user->hasRole('admin') || $user->hasRole('superadministrator') ) {
            return Redirect::to($protocol.'admin.khanaan.com');
        }

        return redirect()->intended('/');
    }

    public function logout(Request $request)
    {
        $user = auth()->user();
        $this->guard()->logout();

        $request->session()->invalidate();

        $domain = 'khanaan.com';
        $protocol = str_replace('dev.'.$domain, '', config('app.url'));
        
        if($user->hasRole('admin') || $user->hasRole('superadministrator') ) {
            return Redirect::to($protocol.'admin.khanaan.com');
        }

        return redirect('/');
    }
}

/*
curl --request GET \
  --url 'https://pro.rajaongkir.com/api/v2/internationalDestination?id=16' \
  --header 'key: 6c45bba279ac5280e4c3b921b5640667'

  curl --request POST \
  --url https://pro.rajaongkir.com/api/v2/internationalCost \
  --header 'content-type: application/x-www-form-urlencoded' \
  --header 'key: 6c45bba279ac5280e4c3b921b5640667' \
  --data origin=153 \
  --data destination=16 \
  --data weight=1200 \
  --data courier=pos:slis:expedito

  curl --request GET \
  --url https://pro.rajaongkir.com/api/currency \
  --header 'key: 6c45bba279ac5280e4c3b921b5640667'
  */