<?php
  //set headers to NOT cache a page
  header("Cache-Control: no-cache, must-revalidate"); //HTTP 1.1
  header("Pragma: no-cache"); //HTTP 1.0
  header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
  //header("Content-Security-Policy: default-src 'none'; script-src 'self'; connect-src 'self'; img-src 'self'; style-src 'self';");
?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css">
    <link rel="stylesheet" href="/css/invoice.css">
  <link rel="stylesheet" href="/css/plugins.bundle.css">
  <link rel="stylesheet" href="/css/style.bundle.css">
    <link href="/images/favicon.ico" rel="shortcut icon" type="image/x-icon" />
    <style type="text/css">
        body{
            font-size: 14px;
            background-color: #fff!important;
        }

        .table-bordered, .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
            border: 1px solid #000000;
        }

        .table-bordered>tbody>tr>th {
            vertical-align: middle;
            text-align: center;
        }

        .table>thead>tr>th, .table>tbody>tr>td {
            font-size: 9px;
            padding-top: .5rem;
            padding-bottom: .5rem;
        }
        .table>thead>tr>th{
            background-color: #efefef;
        }
        .table-scrollable>.table-bordered>thead>tr>th:last-child {
            border-right: 1px solid #e7ecf1;
        }
        .table-scrollable>.table-bordered>thead>tr>th.last-child:last-child {
            border-right: 0;
        }
    </style>
    @yield('themeStyles')
</head>
<body onload="window.print();">
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    @yield('content')
    </div>
</body>
</html>
