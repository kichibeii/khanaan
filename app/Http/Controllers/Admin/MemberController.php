<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Validator;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{
    private $controller = 'member';
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

    public function index()
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-index')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );
        }

        return view('admin.'.$this->controller.'.index')->with(array('controller' => $this->controller, 'title' => $this->title(), 'description' => $this->description, 'icon' => $this->icon));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(User $member)
    {
        //
    }

    public function edit(User $member)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-update')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );
        }

        if ($member->status === 99){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );
        }

        $utils['action'] = __('main.edit');

        return view('admin.'.$this->controller.'.edit', compact('member', 'utils'))->with(array('controller' => $this->controller, 'title' => $this->title(), 'description' => $this->description, 'icon' => $this->icon));
    }

    public function update(Request $request, User $member)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-update')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );
        }

        if ($member->status === 99){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );
        }

        $arrValidates = [
            'name'     => 'required|max:191',
            'username' => 'required|alpha_num|max:20|unique:users,username,'.$member->id,
            'email'    => 'required|email|max:191|unique:users,email,'.$member->id,
            'wa_number'  => 'required|max:191',
        ];
        Validator::make($request->all(), $arrValidates, [], $this->arrNiceName())->validate();

        $arrSaved = [
            'name'      => $request->get('name'),
            'username'  => $request->get('username'),
            'email'     => $request->get('email'),
            'wa_number'  => $request->get('wa_number'),
            'status'     => $request->get('status')
        ];

        $password       = $request->get('password');
        if($password != ''){
            $arrSaved['password'] = Hash::make($password);
        }

        $member->update($arrSaved);

        return redirect()->route($this->controller.'.index')->with('status', __( 'main.data_has_been_updated', ['page' => $member->name] ) );
    }

    public function destroy(User $member)
    {
        //
    }

    public function getData(Request $request)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-index')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );
        }

        $roles = Role::where('id',3)->pluck('name', 'id')->toArray();

        $where = [];
        $where[] = ['status', '<>', 99];

        $rows = User::select([
            'id',
            'name',
            'username',
            'email',
            'wa_number',
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
            ->make(true);
    }
}
