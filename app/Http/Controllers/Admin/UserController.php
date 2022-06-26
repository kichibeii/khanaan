<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Validator;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    private $controller = 'user';
    private $description = '';
    private $icon = 'flaticon-users-1';

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
            'name' => 'Kode ' . __('main.'.$this->controller),
            'display_name' => 'Nama ' . __('main.'.$this->controller),
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
        $utils['options']['roles'] = Role::where($this->getWhereRole($userLoged))->pluck('display_name', 'name')->toArray();

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
            'name'     => 'required|max:191',
            'username' => 'required|min:5|max:20|alpha_num|unique:users,username',
            'email'    => 'required|email|max:191|unique:users',
            'password' => 'required|min:6|confirmed',
        ];
        Validator::make($request->all(), $arrValidates, [], $this->arrNiceName())->validate();

        $arrSaved = [
            'name'      => $request->get('name'),
            'username'  => $request->get('username'),
            'email'  => $request->get('email'),
            'password'  => Hash::make($request->get('password')),
            'status'     => $request->get('status')
        ];

        $user = User::create($arrSaved);
        $user->syncRoles($request->role);

        return redirect()->route($this->controller.'.index')->with('status', __( 'main.data_has_been_added', ['page' => $user->name] ) );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $userLoged = auth()->user();

        if ($user->status === 99){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );           
        }

        $utils['action'] = __('main.view');
        $roles = $user->getRoleNames()->toArray();
        $utils['roleName'] = implode(', ', $roles);

        return view('admin.'.$this->controller.'.show', compact('user', 'utils'))->with(array('controller' => $this->controller, 'title' => $this->title(), 'description' => $this->description, 'icon' => $this->icon));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-update')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        if ($user->status === 99){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );           
        }

        $utils['action'] = __('main.edit');
        $utils['options']['roles'] = Role::where($this->getWhereRole($userLoged))->pluck('display_name', 'name')->toArray();
        $utils['options']['roleSelecteds'] = $user->getRoleNames()->toArray();

        return view('admin.'.$this->controller.'.edit', compact('user', 'utils'))->with(array('controller' => $this->controller, 'title' => $this->title(), 'description' => $this->description, 'icon' => $this->icon));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-update')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        if ($user->status === 99){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );           
        }

        $arrValidates = [
            'name'     => 'required|max:191',
            'username' => 'required|alpha_num|max:20|unique:users,username,'.$user->id,
            'email'    => 'required|email|max:191|unique:users,email,'.$user->id,
        ];
        Validator::make($request->all(), $arrValidates, [], $this->arrNiceName())->validate();

        $arrSaved = [
            'name'      => $request->get('name'),
            'username'  => $request->get('username'),
            'email'  => $request->get('email'),
            'status'     => $request->get('status')
        ];

        $password       = $request->get('password');
        if($password != ''){
            $arrSaved['password'] = Hash::make($password);
        }
        
        $user->update($arrSaved);
        $user->syncRoles($request->role);

        return redirect()->route($this->controller.'.index')->with('status', __( 'main.data_has_been_updated', ['page' => $user->name] ) );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-delete')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        if ($user->status === 99){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );           
        }

        $user->status = 99;
        $user->save();

        return redirect()->route($this->controller.'.index')->with('status', __( 'main.data_has_been_deleted', ['page' => $user->name] ) );
    }

    public function getData(Request $request)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-index')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        $roles = Role::where($this->getWhereRole($userLoged))->pluck('name', 'id')->toArray();  

        $where = [];
        $where[] = ['status', '<>', 99];   

        $rows = User::select([
            'id',
            'name',
            'username',
            'status'
        ])
        ->where($where)
        ->whereHas('roles', function ($query) use($roles) {
            $query->where('name','=', '');
            foreach ($roles as $key => $value) {
                $query->orWhere('name','=', $value);
            }
            return $query;
        });

        return Datatables::of($rows)
            ->addIndexColumn()
            ->addColumn('role', function ($row) {
                $roles = $row->getRoleNames()->toArray();
                $arrName = [];
                foreach ($roles as $v){
                    $role = Role::findByName($v);
                    $arrName[] = $role->display_name;
                }
                return implode(', ', $arrName);
            })
            ->addColumn('image', function ($row) {
                return route(config('imagecache.route'), ['template' => 'medium', 'filename' => User::getImage($row) ]);
            })
            ->addColumn('statusText', function ($row) {
                return arrStatusActive()[$row->status];
            })
            ->addColumn('statusClass', function ($row) {
                return arrStatusActiveClass()[$row->status];
            })
            ->addColumn('viewUrl', function ($row) {
                return route($this->controller.'.show', $row->id);
            })
            ->addColumn('canEdit', function ($row) {
                return auth()->user()->can($this->controller.'-update');
            })
            ->addColumn('editUrl', function ($row) {
                return route($this->controller.'.edit', $row->id);
            })
            ->addColumn('canDelete', function ($row) {
                return auth()->user()->can($this->controller.'-delete');
            })
            ->addColumn('deleteUrl', function ($row) {
                return route($this->controller.'.destroy', $row->id);
            })
            ->make(true);
    }

    private function getWhereRole($userLoged)
    {
        $whereRole = [];
        $whereRole[] = ['name', '<>', 'member'];  
        if ($userLoged->hasRole('superadministrator')){
            $whereRole[] = ['name', '=', 'admin'];  
        } elseif ($userLoged->hasRole('admin')){
            $whereRole[] = ['name', '<>', 'superadministrator'];
            //$whereRole[] = ['name', '<>', 'admin'];
        }

        return $whereRole;
    }
}
