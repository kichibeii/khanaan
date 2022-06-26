<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RajaOngkir extends Model
{
    public static function detail($city, $subdistrict)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://pro.rajaongkir.com/api/subdistrict?city=" . (int) $city . "&id=" . (int) $subdistrict,
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
        return $json->rajaongkir->results;
    }

    public static function country($country)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://pro.rajaongkir.com/api/v2/internationalDestination?id=" . (int) $country,
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
        return $json->rajaongkir->results;
    }

    public static function waybill($noresi,$courier){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://pro.rajaongkir.com/api/waybill",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "waybill=".$noresi."&courier=".$courier,
            CURLOPT_HTTPHEADER => array(
              "content-type: application/x-www-form-urlencoded",
              "key: 6c45bba279ac5280e4c3b921b5640667"
            ),
          ));

        $response = curl_exec($curl);
        $json = json_decode($response,true);
        if(isset($json['rajaongkir'])):
            return $json['rajaongkir'];
        else:
            return [];
        endif;
    }

    public static function cost($subdistrict, $weight, $courier)
    {
        $originProvince = 6; // DKI Jakarta
        $originCity = 153; // Jakarta Selatan
        $origin = 2106; // kebayoran lama
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://pro.rajaongkir.com/api/cost",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => array(
                'origin' => $origin,
                'originType' => 'subdistrict',
                'destination' => $subdistrict,
                'destinationType' => 'subdistrict',
                'weight' => $weight,
                'courier' => $courier
            ),
            CURLOPT_HTTPHEADER => array(
                "key: 6c45bba279ac5280e4c3b921b5640667"
            ),
        ));

        $response = curl_exec($curl);
        $json = json_decode($response);

        return $json->rajaongkir;
    }

    public static function costAbroad($country_id, $weight, $courier)
    {
        $originProvince = 6; // DKI Jakarta
        $originCity = 153; // Jakarta Selatan
        $origin = 2106; // kebayoran lama
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://pro.rajaongkir.com/api/v2/internationalCost",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => array(
                'origin' => $originCity,
                'destination' => $country_id,
                'weight' => $weight,
                'courier' => $courier
            ),
            CURLOPT_HTTPHEADER => array(
                "key: 6c45bba279ac5280e4c3b921b5640667"
            ),
        ));

        $response = curl_exec($curl);
        $json = json_decode($response);

        return $json->rajaongkir;
    }
}
