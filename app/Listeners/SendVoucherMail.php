<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Voucher;
use Carbon\Carbon;
use Mail;
use App\Mail\RegistrationVoucherMail;

class SendVoucherMail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $code = substr(strtoupper(md5(uniqid(rand(), true))), 0, 10);
        $dateNow = Carbon::now('Asia/Jakarta')->format("Y-m-d");

        $arrSaved = array(
            'code'          => $code,
            'nominal'       => 50000,
            'start_date'    => $dateNow,
            'end_date'      => (new Carbon($dateNow))->addDays(7)->format('Y-m-d')
        );

        $voucher = Voucher::create($arrSaved);

        Mail::to($event->user->email)
                ->send(new RegistrationVoucherMail(__('main.voucher_from') . config('app.name') , $event->user, $voucher));
    }
}
