<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RajaOngkir;
use App\UserBilling;

class RajaOngkirController extends Controller
{
    public function country()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://pro.rajaongkir.com/api/v2/internationalDestination",
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

        curl_close($curl);

        $arr = [];
        $arr[] = [
            'id' => 0,
            'text' => 'Indonesia'
        ];
        if ($json->rajaongkir->status->code == 200 && count($json->rajaongkir->results)){
            foreach ($json->rajaongkir->results as $row){
                $arr[] = [
                    'id' => $row->country_id,
                    'text' => $row->country_name
                ];
            }
        }

        return response()->json($arr);
    }

    public function index()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://pro.rajaongkir.com/api/province",
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

        curl_close($curl);

        $arr = [];
        if ($json->rajaongkir->status->code == 200 && count($json->rajaongkir->results)){
            foreach ($json->rajaongkir->results as $row){
                $arr[] = [
                    'id' => $row->province_id,
                    'text' => $row->province
                ];
            }
        }

        return response()->json($arr);
    }

    public function city($province)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://pro.rajaongkir.com/api/city?province=" . (int) $province,
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

        curl_close($curl);

        $arr = [];
        if ($json->rajaongkir->status->code == 200 && count($json->rajaongkir->results)){
            foreach ($json->rajaongkir->results as $row){
                $arr[] = [
                    'id' => $row->city_id,
                    'text' => $row->type . ' ' . $row->city_name
                ];
            }
        }

        return response()->json($arr);
    }

    public function subdistrict($city)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://pro.rajaongkir.com/api/subdistrict?city=" . (int) $city,
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

        curl_close($curl);

        $arr = [];
        if ($json->rajaongkir->status->code == 200 && count($json->rajaongkir->results)){
            foreach ($json->rajaongkir->results as $row){
                $arr[] = [
                    'id' => $row->subdistrict_id,
                    'text' => $row->subdistrict_name
                ];
            }
        }

        return response()->json($arr);
    }

    public function subdistrictDetail($city, $subdistrict)
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

        return response()->json($json->rajaongkir->results);
    }

    public function getCourier(Request $request)
    {
        $weight = (int) $request->weight;
        $billing = auth()->user()->billings()->whereId((int) $request->billing_id)->firstOrFail();

        $arrCourier = [];

        $idr = 1;
        $currentCurrency = currentCurrency();
        if ($currentCurrency == 'usd'){
            $idr = \App\Setting::getValue('dollar');
        }

        if (is_null($billing->country_id)){
            foreach (arrCourier() as $k => $v){
                $arrCourier[] = $k;
            }

            $rajaOngkirCost = RajaOngkir::cost($billing->subdistrict_id, $weight, implode(':', $arrCourier));
        } else {

            foreach (arrCourierAbroad() as $k => $v){
                $arrCourier[] = $k;
            }

            $rajaOngkirCost = RajaOngkir::costAbroad($billing->country_id, $weight, implode(':', $arrCourier));
        }

        $arr = [];
        //return $rajaOngkirCost->status->description;
        if ($rajaOngkirCost->status->code == 200 && count($rajaOngkirCost->results)){

            foreach ($rajaOngkirCost->results as $k => $v){

                if (count($v->costs)){
                    $children = [];
                    foreach ($v->costs as $a => $b){
                        if (is_null($billing->country_id)){
                            $priceText = getPrice($currentCurrency, $idr, $b->cost[0]->value);
                            $children[] = [
                                //'id' => $v->code.'|'.$b->service.'|'.$b->description.'|'.$b->cost[0]->etd.'|'.$b->cost[0]->value.'|'.$rajaOngkirCost->origin_details->subdistrict_id.'|'.$rajaOngkirCost->destination_details->subdistrict_id.'|'.$rajaOngkirCost->query->destinationType.'|'.$rajaOngkirCost->destination_details->city_id.'|'.$v->name,
                                'id' => $v->code.';'.$b->service.';'.$b->description.';'.$b->cost[0]->etd.';'.$b->cost[0]->value.';'.$rajaOngkirCost->origin_details->subdistrict_id.';'.$rajaOngkirCost->destination_details->subdistrict_id.';'.$rajaOngkirCost->query->destinationType.';'.$rajaOngkirCost->destination_details->city_id.';'.$v->name,
                                'text' => strtoupper($v->code).' - '.$b->description . ' ('. ($b->cost[0]->etd == '' ? 'x' : $b->cost[0]->etd).' '.__('main.days').', ' . displayPrice($currentCurrency, $priceText) . ')',
                                'cost' => $b->cost[0]->value,
                                'etd' => $b->cost[0]->etd,
                            ];
                        } else {
                            $priceText = getPrice($currentCurrency, $idr, $b->cost);
                            $children[] = [
                                //'id' => $v->code.'|'.$b->service.'||'.$b->etd.'|'.$b->cost.'|'.$rajaOngkirCost->origin_details->city_id.'|'.$rajaOngkirCost->destination_details->country_id.'|country|'.$rajaOngkirCost->destination_details->country_id.'|'.$v->name,
                                'id' => $v->code.';'.$b->service.';;'.$b->etd.';'.$b->cost.';'.$rajaOngkirCost->origin_details->city_id.';'.$rajaOngkirCost->destination_details->country_id.';country;'.$rajaOngkirCost->destination_details->country_id.';'.$v->name,
                                'text' => strtoupper($v->code).' - '.$b->service . ' ('. ($b->etd == '' ? 'x' : $b->etd).' '.__('main.days').', ' . displayPrice($currentCurrency, $priceText) . ')',
                                'cost' => $b->cost,
                                'etd' => $b->etd,
                            ];
                        }
                    }
                    $arr[] = [
                        //'id' => $v->code,
                        'text' => $v->name,
                        'children' => $children,
                        'element' => 'HTMLOptGroupElement'
                    ];
                }
            }
        }

        return response()->json($arr);
    }

    public function getCourierGuest(Request $request)
    {
        $weight = (int) $request->weight;
        $country_id = (int) $request->country_id;
        $province_id = (int) $request->province_id;
        $city_id = (int) $request->city_id;
        $subdistrict_id = (int) $request->subdistrict_id;

        $arrCourier = [];

        $idr = 1;
        $currentCurrency = currentCurrency();
        if ($currentCurrency == 'usd'){
            $idr = \App\Setting::getValue('dollar');
        }

        if (empty($country_id)){
            foreach (arrCourier() as $k => $v){
                $arrCourier[] = $k;
            }

            $rajaOngkirCost = RajaOngkir::cost($subdistrict_id, $weight, implode(':', $arrCourier));
        } else {

            foreach (arrCourierAbroad() as $k => $v){
                $arrCourier[] = $k;
            }

            $rajaOngkirCost = RajaOngkir::costAbroad($country_id, $weight, implode(':', $arrCourier));
        }

        $arr = [];
        //return $rajaOngkirCost->status->description;
        if ($rajaOngkirCost->status->code == 200 && count($rajaOngkirCost->results)){

            foreach ($rajaOngkirCost->results as $k => $v){

                if (count($v->costs)){
                    $children = [];
                    foreach ($v->costs as $a => $b){
                        if (empty($country_id)){
                            $priceText = getPrice($currentCurrency, $idr, $b->cost[0]->value);
                            $children[] = [
                                //'id' => $v->code.'|'.$b->service.'|'.$b->description.'|'.$b->cost[0]->etd.'|'.$b->cost[0]->value.'|'.$rajaOngkirCost->origin_details->subdistrict_id.'|'.$rajaOngkirCost->destination_details->subdistrict_id.'|'.$rajaOngkirCost->query->destinationType.'|'.$rajaOngkirCost->destination_details->city_id.'|'.$v->name,
                                'id' => $v->code.';'.$b->service.';'.$b->description.';'.$b->cost[0]->etd.';'.$b->cost[0]->value.';'.$rajaOngkirCost->origin_details->subdistrict_id.';'.$rajaOngkirCost->destination_details->subdistrict_id.'|;'.$rajaOngkirCost->query->destinationType.';'.$rajaOngkirCost->destination_details->city_id.';'.$v->name,
                                'text' => strtoupper($v->code).' - '.$b->description . ' ('. ($b->cost[0]->etd == '' ? 'x' : $b->cost[0]->etd).' '.__('main.days').', ' . displayPrice($currentCurrency, $priceText) . ')',
                                'cost' => $b->cost[0]->value,
                                'etd' => $b->cost[0]->etd,
                            ];
                        } else {
                            $priceText = getPrice($currentCurrency, $idr, $b->cost);
                            $children[] = [
                                //'id' => $v->code.'|'.$b->service.'||'.$b->etd.'|'.$b->cost.'|'.$rajaOngkirCost->origin_details->city_id.'|'.$rajaOngkirCost->destination_details->country_id.'|country|'.$rajaOngkirCost->destination_details->country_id.'|'.$v->name,
                                'id' => $v->code.';'.$b->service.';;'.$b->etd.';'.$b->cost.';'.$rajaOngkirCost->origin_details->city_id.';'.$rajaOngkirCost->destination_details->country_id.';country;'.$rajaOngkirCost->destination_details->country_id.';'.$v->name,
                                'text' => strtoupper($v->code).' - '.$b->service . ' ('. ($b->etd == '' ? 'x' : $b->etd).' '.__('main.days').', ' . displayPrice($currentCurrency, $priceText) . ')',
                                'cost' => $b->cost,
                                'etd' => $b->etd,
                            ];
                        }
                    }
                    $arr[] = [
                        //'id' => $v->code,
                        'text' => $v->name,
                        'children' => $children,
                        'element' => 'HTMLOptGroupElement'
                    ];
                }
            }
        }

        return response()->json($arr);
    }

    public function getCourierByInvoice(Request $request)
    {
        $invoice_id = (int) $request->invoice_id;
        $invoice = Invoice::whereId($invoice_id)->firstOrFail();
        $invoice->courier = $invoice->courier == 'J&T' ? 'jnt' : $invoice->courier;

        $rajaOngkirCost = RajaOngkir::cost($invoice->destination_destination_id, 100, $invoice->courier);

        $arr = [];
        if ($rajaOngkirCost->status->code == 200 && count($rajaOngkirCost->results)){
            foreach ($rajaOngkirCost->results as $k => $v){

                if (count($v->costs)){
                    $children = [];
                    foreach ($v->costs as $a => $b){
                        $children[] = [
                            'id' => $v->code.'|'.$b->service.'|'.$b->description.'|'.$b->cost[0]->etd.'|'.$b->cost[0]->value.'|'.$rajaOngkirCost->origin_details->subdistrict_id.'|'.$rajaOngkirCost->destination_details->subdistrict_id.'|'.$rajaOngkirCost->query->destinationType.'|'.$rajaOngkirCost->destination_details->city_id.'|'.$v->name,
                            'text' => strtoupper($v->code).' - '.$b->description . ' ('. ($b->cost[0]->etd == '' ? 'x' : $b->cost[0]->etd).' hari, Rp.'.numberFormat($b->cost[0]->value).')',
                            'selected' => $invoice && ($v->code == $invoice->courier && $b->service == $invoice->courier_service) ? true : false
                        ];
                    }
                    $arr[] = [
                        //'id' => $v->code,
                        'text' => $v->name,
                        'children' => $children,
                        'element' => 'HTMLOptGroupElement'
                    ];
                }
            }
        }

        return response()->json($arr);
    }

    public function testCourier(Request $request)
    {
        $weight = 1500;
        $billing = UserBilling::whereId(1)->firstOrFail();

        $arrCourier = [];
        foreach (arrCourier() as $k => $v){
            $arrCourier[] = $k;
        }

        $rajaOngkirCost = RajaOngkir::cost($billing->subdistrict_id, $weight, implode(':', $arrCourier));

        $arr = [];
        if ($rajaOngkirCost->status->code == 200 && count($rajaOngkirCost->results)){
            foreach ($rajaOngkirCost->results as $k => $v){

                if (count($v->costs)){
                    $children = [];
                    foreach ($v->costs as $a => $b){
                        $children[] = [
                            'id' => $v->code.'|'.$b->service.'|'.$b->description.'|'.$b->cost[0]->etd.'|'.$b->cost[0]->value.'|'.$rajaOngkirCost->origin_details->subdistrict_id.'|'.$rajaOngkirCost->destination_details->subdistrict_id.'|'.$rajaOngkirCost->query->destinationType.'|'.$rajaOngkirCost->destination_details->city_id.'|'.$v->name,
                            'text' => strtoupper($v->code).' - '.$b->description . ' ('. ($b->cost[0]->etd == '' ? 'x' : $b->cost[0]->etd).' hari, Rp.'.numberFormat($b->cost[0]->value).')',
                            'cost' => $b->cost[0]->value,
                            'etd' => $b->cost[0]->etd,
                        ];
                    }
                    $arr[] = [
                        //'id' => $v->code,
                        'text' => $v->name,
                        'children' => $children,
                        'element' => 'HTMLOptGroupElement'
                    ];
                }
            }
        }

        return response()->json($arr);
    }
}
