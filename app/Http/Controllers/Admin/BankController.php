<?php

namespace App\Http\Controllers\Admin;

use App\Bank;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class BankController extends Controller
{
    private $controller = 'bank';
    private $description = '';
    private $icon = 'flaticon-squares-1';

    public function __construct()
    {
        $this->middleware('auth');
    }

    private function title()
    {
        return __('main.'.$this->controller);
    }

    private function arrNiceName()
    {
        return array(
            'name' => 'Nama',
            'account_number' => 'Nomor Rekening',
            'owner_name' => 'Nama Pemilik Rekening',
            'branch' => 'Cabang',
            'file' => 'Logo'
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-index')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        return view('admin.'.$this->controller.'.index')->with(array('controller' => $this->controller, 'title' => $this->title(), 'description' => $this->description, 'icon' => $this->icon));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-create')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        $utils['action'] = __('main.add_new');

        return view('admin.'.$this->controller.'.create', compact('utils'))->with(array('controller' => $this->controller, 'title' => $this->title(), 'description' => $this->description, 'icon' => $this->icon));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-create')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        $arrValidates = [
            'name' => 'required',
            'account_number' => 'required',
            'owner_name' => 'required',
            'branch' => 'required',
            'file' => 'required|image|mimes:jpeg,png,jpg|max:500',
        ];

        Validator::make($request->all(), $arrValidates, [], $this->arrNiceName())->validate();

        $arrSaved = [
            'name'      => $request->get('name'),
            'account_number'      => $request->get('account_number'),
            'owner_name'      => $request->get('owner_name'),
            'branch'      => $request->get('branch'),
            'status'     => $request->get('status')
        ];

        $file = $request->file;
        $destinationPath = 'public/images/'.$this->controller.'s';

        $image = Image::make($file);
        $isJpg = $image->mime() === 'image/jpg' || $image->mime() === 'image/jpeg';
        if($isJpg && $image->exif('Orientation')){
            $image = orientate($image, $image->exif('Orientation'));
        }

        $image->stream(); // <-- Key point
        $destinationPath = $destinationPath.'/'.$file->getClientOriginalName();
        Storage::put($destinationPath, $image);

        $arrSaved['image'] = $file->getClientOriginalName();

        $bank = Bank::create($arrSaved);

        return redirect()->route($this->controller.'.index')->with('status', __( 'main.data_has_been_added', ['page' => $bank->name] ) );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function show(Bank $bank)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function edit(Bank $bank)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-update')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        if ($bank->status === 99){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );           
        }

        $utils['action'] = __('main.edit');
        return view('admin.'.$this->controller.'.edit', compact('bank', 'utils'))->with(array('controller' => $this->controller, 'title' => $this->title(), 'description' => $this->description, 'icon' => $this->icon));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bank $bank)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-update')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        if ($bank->status === 99){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );           
        }

        $arrValidates = [
            'name' => 'required',
            'account_number' => 'required',
            'owner_name' => 'required',
            'branch' => 'required'
        ];
        Validator::make($request->all(), $arrValidates, [], $this->arrNiceName())->validate();

        $arrSaved = [
            'name'      => $request->get('name'),
            'account_number'      => $request->get('account_number'),
            'owner_name'      => $request->get('owner_name'),
            'branch'      => $request->get('branch'),
            'status'     => $request->get('status')
        ];

        $file = $request->file;

        if ($file) {
            $destinationPath = 'public/images/'.$this->controller.'s';
            if(!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0775, true);
            }

            if(!is_null($bank->image) && File::exists($destinationPath.'/'.$bank->image)) {
                unlink($destinationPath.'/'.$bank->image);
            }

            $image = Image::make($file);
            $isJpg = $image->mime() === 'image/jpg' || $image->mime() === 'image/jpeg';
            if($isJpg && $image->exif('Orientation')){
                $image = orientate($image, $image->exif('Orientation'));
            }

            $image->stream(); // <-- Key point
            $destinationPath = $destinationPath.'/'.$file->getClientOriginalName();
            Storage::put($destinationPath, $image);

            $bank->image = $file->getClientOriginalName();
        }
        
        $bank->update($arrSaved);

        return redirect()->route($this->controller.'.index')->with('status', __( 'main.data_has_been_updated', ['page' => $bank->name] ) );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bank $bank)
    {
        //
    }

    public function getData(Request $request)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-index')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }
        
        $arrColumns = [1=>'id', 2=>'name', 3=>'account_number', 4=>'owner_name', 5=>'branch', 6=>'status'];

        $draw = $request->get('draw');
        $start = $request->get('start');
        $length = $request->get('length');
        $orderBy = $request->get('order')[0]['column'];
        $orderAD = $request->get('order')[0]['dir'];
        $q = $request->get('search')['value'];

        $where = [];
        $where[] = ['status', '<>', 99];   

        $rows = Bank::select([
            'id',
            'name',
            'account_number',
            'owner_name',
            'branch',
            'status',
            'image'
        ])
        ->where($where);

        if ($q !== null){
            $rows->where('name', 'LIKE', '%'.$q.'%');
            $rows->orWhere('account_number', 'LIKE', '%'.$q.'%');
            $rows->orWhere('owner_name', 'LIKE', '%'.$q.'%');
        }

        $totalRows = $rows->get()->count();
        $rows = $rows->offset($start)->take($length)
            ->orderBy($arrColumns[$orderBy],$orderAD)
            ->get();

        return Datatables::of($rows)
            ->addIndexColumn()
            ->skipPaging()
            ->with([
                'recordsTotal' => $totalRows,
                'recordsFiltered' => $totalRows,
            ])
            ->addColumn('image', function ($row) {
                return route(config('imagecache.route'), ['template' => 'medium', 'filename' => Bank::getImage($row) ]);
            })
            ->addColumn('statusText', function ($row) {
                return arrStatusActive()[$row->status];
            })
            ->addColumn('statusClass', function ($row) {
                return arrStatusActiveClass()[$row->status];
            })
            ->addColumn('canEdit', function ($row) {
                return auth()->user()->can($this->controller.'-update');
            })
            ->addColumn('editUrl', function ($row) {
                return route($this->controller.'.edit', $row->id);
            })
            ->make();
    }
}
