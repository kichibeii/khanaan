<?php

if (!function_exists('currentCurrency')) {
    /**
     * @return string
     */
    function currentCurrency(){
        if (Session::has('appcurrency')) {
            return Session::get('appcurrency');
        }
        return 'idr';
    }
}

if (!function_exists('setCurrency')) {
    /**
     * @param string $currency
     * @return void
     * @throws \Exception
     */
    function setCurrency($currency){
        Session::set('appcurrency', $currency);
    }
}

if (!function_exists('currentVoucher')) {
    /**
     * @return string
     */
    function currentVoucher(){
        if (Session::has('appVoucher')) {
            return Session::get('appVoucher');
        }
        return ['voucher_id' => '', 'voucher_nominal'=>'', 'voucher_code'=>''];
    }
}

if (!function_exists('setVoucher')) {
    /**
     * @param string $currency
     * @return void
     * @throws \Exception
     */
    function setVoucher($voucher){
        Session::set('appVoucher', $voucher);
    }
}

function getPrice($currency, $idr, $price)
{

    if ($currency == 'usd'){
        $price = $idr > 0 ? ($price / $idr) : 0;
        return round($price, 2);
    }
    return $price;
}

function displayPrice($currency, $price)
{
    $text = strtoupper($currency);
    if ($currency == 'usd'){
        $text .= ' '. number_format($price,2,".",",");
    } else {
        $text .= ' '. numberFormat($price);
    }

    return $text;
}

function getTableName($table) {
    return config('database.connections.mysql.prefix').$table;
}
function arrYesNo()
{
	return [1=>'Ya', 0=>'Tidak'];
}

function print_rr($arr, $exit=false)
{
    echo '<pre>';
    print_r($arr);
    echo '</pre>';

    if ($exit){
        exit;
    }
}

function arrApprove()
{
    return [1=>'Approve', 0=>'Not Approve'];
}

function arrStatusPublish()
{
	return [1=>'Publish', 0=>'Hidden'];
}

function arrStatusApproval()
{
	return [1=>'Approved', 0=>'Pending', 2=>'Not Approve'];
}

function arrStatusActive()
{
	return [1=>'Aktif', 0=>'Non Aktif'];
}

function arrStatusInvoice()
{
	return [1=>'Aktif', 0=>'Expired'];
}

function arrStatusPayment()
{
	return [1=>'paid', 0=>'unpaid'];
}

function arrStatusActiveClass()
{
    return [1=>'btn-label-success', 0=>'btn-label-danger'];
}

function arrStatusApproveClass()
{
    return [1=>'btn-label-success', 0=>'btn-label-warning', 2=>'btn-label-danger'];
}

function generate_numbers($start, $count, $digits) {
    return str_pad($start, $digits, "0", STR_PAD_LEFT);
    $result = array();
    for ($n = $start; $n < $start + $count; $n++) {

        $result[] = str_pad($n, $digits, "0", STR_PAD_LEFT);

    }

    return $result;
}

function arrMonth() {
    return array(1 => 'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember');
}

function arrDay() {
    return array(1 => 'Senin', 2=>'Selasa', 3=>'Rabu', 4=>'Kamis', 5=>'Jumat', 6=>'Sabtu', 7=>'Minggu');
}

function dateFormat($date){
    //return date('d', strtotime($date)) .' '. arrMonth()[date('n', strtotime($date))] .' '. date('Y', strtotime($date));
    return date('F', strtotime($date)) .' '. date('d', strtotime($date)) .', '. date('Y', strtotime($date));
}

function dateFormatLang($date){
    $langActive = LaravelLocalization::getCurrentLocale();
    if ($langActive == 'en'){
        return date('F', strtotime($date)) .' '. date('d', strtotime($date)) .', '. date('Y', strtotime($date));
    } else {
        return date('d', strtotime($date)) . ' ' . arrMonth()[date('n', strtotime($date))] . ' ' . date('Y', strtotime($date));
    }
    //return date('d', strtotime($date)) .' '. arrMonth()[date('n', strtotime($date))] .' '. date('Y', strtotime($date));

}

function numberFormat($number, $length=0) {
    return number_format($number,$length,",",".");
}

function numberToRomanRepresentation($number) {
    $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
    $returnValue = '';
    while ($number > 0) {
        foreach ($map as $roman => $int) {
            if($number >= $int) {
                $number -= $int;
                $returnValue .= $roman;
                break;
            }
        }
    }
    return $returnValue;
}

function arrCourier()
{
    return [
        'jne'=>'JNE',
        // 'tiki'=>'TIKI',
        //'jnt'=>'J&T Express',

        //'pos' => 'Pos',
        //'rpx' => 'rpx',
        //'pandu' => 'pandu',
        //'wahana' => 'wahana',
        //'sicepat' => 'sicepat',
        //'pahala' => 'pahala',
        //'sap' => 'sap',
        //'jet' => 'jet',
        //'indah' => 'indah',
        //'dse' => 'dse',
        //'slis' => 'slis',
        //'first' => 'first',
        //'ncs' => 'ncs',
        //'star' => 'star',
        //'ninja' => 'ninja',
        //'lion' => 'lion',
        //'idl' => 'idl',
        //'rex' => 'rex',
        //'ide' => 'ide',
        //'sentral' => 'sentral'

    ];

    //jne, pos, tiki, rpx, pandu, wahana, sicepat, jnt, pahala, sap, jet, indah, dse, slis, first, ncs, star, ninja, lion, idl, rex, ide, sentral
}

function arrCourierAbroad()
{
    return [
        'pos' => 'Pos',
        //'jne'=>'JNE',
        //'slis'=>'Slis',
        'expedito'=>'Expedito',
        //'tiki'=>'TIKI',
    ];
}

function getImageUrl($url)
{
    return str_replace('api.', '', $url);
}

function orientate($image, $orientation)
{
    switch ($orientation) {

        // 888888
        // 88
        // 8888
        // 88
        // 88
        case 1:
            return $image;

        // 888888
        //     88
        //   8888
        //     88
        //     88
        case 2:
            return $image->flip('h');


        //     88
        //     88
        //   8888
        //     88
        // 888888
        case 3:
            return $image->rotate(180);

        // 88
        // 88
        // 8888
        // 88
        // 888888
        case 4:
            return $image->rotate(180)->flip('h');

        // 8888888888
        // 88  88
        // 88
        case 5:
            return $image->rotate(-90)->flip('h');

        // 88
        // 88  88
        // 8888888888
        case 6:
            return $image->rotate(-90);

        //         88
        //     88  88
        // 8888888888
        case 7:
            return $image->rotate(-90)->flip('v');

        // 8888888888
        //     88  88
        //         88
        case 8:
            return $image->rotate(90);

        default:
            return $image;
    }
}

function arrLanguage(){
    $data = [
        'en' => [
            'title' => __('main.english'),
            'img'   => '/assets/img/language/en.png',
        ],
        'id' => [
            'title' => __('main.indonesian'),
            'img'   => '/assets/img/language/id.png',
        ],
    ];
    return $data;
}

function getTextLang($row, $object)
{
    $langActive = LaravelLocalization::getCurrentLocale();
    if ($langActive == 'en'){
        return $row->{$object};
    } else {
        return is_null($row->{$object.'_id'}) ? $row->{$object} : $row->{$object.'_id'};
    }
}
