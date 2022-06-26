<?php

namespace App\Http\Controllers;

use App\Page;
use App\ContactUs;
use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;

class PageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['cartauth']);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function contactUs(Request $request)
    {
        $arrNiceName = [
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone Number',
            'con_message' => 'Message',
        ];

        $arrValidates = [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'con_message' => 'required'
        ];
        $validator = Validator::make($request->all(), $arrValidates, [], $arrNiceName);
        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        $arrSaved = [
            'name'      => $request->get('name'),
            'phone'  => $request->get('phone'),
            'email'  => $request->get('email'),
            'message'  => $request->get('con_message'),
            'send_date'  => Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s')
        ];

        ContactUs::create($arrSaved);

        return response()->json([
            'responseText' => 'Your message has been succesfully sent!'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $page = Page::where('status', 1)
            ->where('slug', $slug)
            ->firstOrFail();

        if ($page->id == 1){ // about us 
            return view('page.about-us', compact('page'));
        } if ($page->id == 5){ // Contact us 
            return view('page.contact-us', compact('page'));
        } else {
            return view('page.show', compact('page'));
        }

        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page)
    {
        //
    }
}
