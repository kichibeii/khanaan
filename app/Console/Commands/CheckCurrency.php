<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Setting;

class CheckCurrency extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CheckCurrency:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengecek rate currency rajaongkir';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://pro.rajaongkir.com/api/currency",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "key: 6c45bba279ac5280e4c3b921b5640667"
            ),
        ));

        $response = curl_exec($curl);
        $json = json_decode($response);

        if ($json->rajaongkir->status->code == '200'){
            $setting = Setting::where('name', 'dollar')->first();
            if ($setting){
                $setting->values = $json->rajaongkir->result->value;
                $setting->save();    
            }
        }
    }
}
