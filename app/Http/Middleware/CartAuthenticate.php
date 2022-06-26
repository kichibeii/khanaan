<?php

namespace App\Http\Middleware;

use Closure;
use Cart;
use DB;
use Illuminate\Support\Facades\Auth;

class CartAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::check()) {
            $user = Auth::user();
            if ($user->status == 1) {
                $oldCartData = DB::table('shoppingcart')->where('identifier', $user->id)->first();

                Cart::restore(auth()->user()->id);

                Cart::store(auth()->user()->id);


                if (count(Cart::content())){
                    DB::table('shoppingcart')
                        ->where('identifier', $user->id)
                        ->update(['voucher_id' => $oldCartData ? $oldCartData->voucher_id : null, 'voucher_nominal'=>$oldCartData ? $oldCartData->voucher_nominal : null, 'voucher_code'=> $oldCartData ? $oldCartData->voucher_code : null]);
                }
            }
        }

        return $next($request);
    }
}
