<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Validator;

class RoleController extends Controller
{
    private $controller = 'role';
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
        $user = auth()->user();

        if (!$user->can($this->controller.'-create')){
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
        $user = auth()->user();
        if (!$user->can($this->controller.'-create')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        $arrValidates = [
            'name' => 'required|max:191|unique:roles',
            'display_name' => 'required'
        ];
        Validator::make($request->all(), $arrValidates, [], $this->arrNiceName())->validate();

        $arrSaves = [];
        $arrSaves['name'] = $request->name;
        $arrSaves['display_name'] = $request->display_name;
        
        $role = Role::create($arrSaves);

        return redirect()->route($this->controller.'.index')->with('status', __( 'main.data_has_been_added', ['page' => $role->display_name] ) );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $user = auth()->user();
        if (!$user->can($this->controller.'-update')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        $utils['action'] = __('main.edit');

        return view('admin.'.$this->controller.'.edit', compact('role', 'utils'))->with(['controller'=>$this->controller, 'title'=>$this->title(), 'description' => $this->description, 'icon' => $this->icon]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $user = auth()->user();
        if (!$user->can($this->controller.'-update')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        $arrValidates = [
            'display_name' => 'required'
        ];
        Validator::make($request->all(), $arrValidates, [], $this->arrNiceName())->validate();

        $role->display_name = $request->display_name;
        $role->save();

        return redirect()->route($this->controller.'.index')->with('status', __( 'main.data_has_been_updated', ['page' => $role->display_name] ) );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $user = auth()->user();
        if (!$user->can($this->controller.'-delete')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        $role->delete();
        return redirect()->route($this->controller.'.index')->with('status', __( 'main.data_has_been_deleted', ['page' => $role->display_name] ) );
    }

    public function permission(Role $role)
    {
        $user = auth()->user();
        if (!auth()->user()->can($this->controller.'-update')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        $permissions = Permission::orderBy('name')->get();
        
        $permissions = $permissions->each(function ($item, $key) {
            $name = $item['name'];
            $nameArray = explode('-', $name);
            $module = $nameArray[0];
            $operation = $nameArray[1];
            $item['module'] = $module;
            $item['operation'] = $operation;
            return $item;
        });

        $utils['action'] = __('main.permission');
        $utils['options']['permissions'] = $permissions->groupBy('module')->sortKeys();
        $utils['options']['rolePermission'] = $role->permissions()->pluck('id')->toArray();

        return view('admin.'.$this->controller.'.permission', compact('role', 'utils'))->with(['controller'=>$this->controller, 'title'=>$this->title(), 'description' => $this->description, 'icon' => $this->icon]);
    }

    public function updatePermission(Role $role)
    {
        if (!auth()->user()->can($this->controller.'-update')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        $permission = request('permission');
        $ids = collect($permission)->keys();
        $role->syncPermissions($ids);

        return redirect()->route($this->controller.'.index')->with('status', __( 'main.data_has_been_updated', ['page' => 'Permission ' . $role->name] ) );
    }

    public function getData(Request $request)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-index')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        $rows = Role::select([
            'id',
            'name',
            'display_name'
        ]);

        return Datatables::of($rows)
            ->addIndexColumn()
            ->addColumn('canPermission', function ($row) {
                return auth()->user()->can($this->controller.'-update');
            })
            ->addColumn('permissionUrl', function ($row) {
                return route($this->controller.'.permission', $row->id);
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
}
