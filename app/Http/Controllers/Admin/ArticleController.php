<?php

namespace App\Http\Controllers\Admin;

use App\Article;
use App\Dropdown;
use App\Tag;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Str;
use Intervention\Image\Facades\Image;

class ArticleController extends Controller
{
    private $controller = 'article';
    private $description = '';
    private $icon = 'flaticon2-paper';

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
            'title' => 'Judul',
            'title_id' => 'Judul (ID)',
            'slug' => 'Slug',
            'preview' => 'Preview Text',
            'preview_id' => 'Preview Text (ID)',
            'description' => 'Full Text',
            'description_id' => 'Full Text (ID)',
            'published_on' => 'Tanggal Publish',
            'file' => 'Gambar'
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
        $utils['options']['categories'] = Dropdown::getOptions('blogcategory');
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
            //'title' => 'required|min:3|unique:articles,title',
            'title' => 'required|min:3',
            'title_id' => 'required|min:3',
            'slug' => 'required|min:3|unique:articles,slug',
            'preview' => 'required',
            'preview_id' => 'required',
            'description' => 'required',
            'description_id' => 'required',
            'published_on' => 'required',
            'file' => 'required|image|mimes:jpeg,png,jpg|max:500',
        ];

        Validator::make($request->all(), $arrValidates, [], $this->arrNiceName())->validate();

        $arrSaved = [
            'title'      => $request->get('title'),
            'title_id'      => $request->get('title_id'),
            'slug'  => Str::slug($request->get('slug'), '-'),
            'preview'      => $request->get('preview'),
            'preview_id'      => $request->get('preview_id'),
            'description'      => $request->get('description'),
            'description_id'      => $request->get('description_id'),
            'published_on'      => date('Y-m-d H:i:s', strtotime($request->get('published_on'))),
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
        $storagePath = Storage::put($destinationPath, $image);

        $arrSaved['image'] = $file->getClientOriginalName();

        $article = Article::create($arrSaved);
        $article->categories()->sync($request->get('categories'));
        $article->saveTags(Tag::setTag($request->get('tags'), $article->title));

        return redirect()->route($this->controller.'.index')->with('status', __( 'main.data_has_been_added', ['page' => $article->title] ) );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-update')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        if ($article->status === 99){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );           
        }
        
        $utils['action'] = __('main.edit');
        $utils['options']['categories'] = Dropdown::getOptions('blogcategory');
        $utils['options']['categoriesSelecteds'] = $article->categories()->pluck('dropdown_items.id')->toArray();
        
        return view('admin.'.$this->controller.'.edit', compact('article', 'utils'))->with(array('controller' => $this->controller, 'title' => $this->title(), 'description' => $this->description, 'icon' => $this->icon));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-update')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        if ($article->status === 99){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );           
        }

        $arrValidates = [
            'title' => 'required|min:3',
            'title_id' => 'required|min:3',
            'slug' => 'required|min:3|unique:articles,slug,'.$article->id,
            'preview' => 'required',
            'preview_id' => 'required',
            'description' => 'required',
            'description_id' => 'required',
            'published_on' => 'required'
        ];
        
        Validator::make($request->all(), $arrValidates, [], $this->arrNiceName())->validate();

        $arrSaved = [
            'title'      => $request->get('title'),
            'title_id'      => $request->get('title_id'),
            'slug'  => Str::slug($request->get('slug'), '-'),
            'preview'      => $request->get('preview'),
            'preview_id'      => $request->get('preview_id'),
            'description'      => $request->get('description'),
            'description_id'      => $request->get('description_id'),
            'published_on'      => date('Y-m-d H:i:s', strtotime($request->get('published_on'))),
            'status'     => $request->get('status')
        ];

        $file = $request->file;

        if ($file) {
            $destinationPath = 'public/images/'.$this->controller.'s';

            if(!is_null($article->image) && File::exists($destinationPath.'/'.$article->image)) {
                unlink($destinationPath.'/'.$article->image);
            }

            $image = Image::make($file);
            $isJpg = $image->mime() === 'image/jpg' || $image->mime() === 'image/jpeg';
            if($isJpg && $image->exif('Orientation')){
                $image = orientate($image, $image->exif('Orientation'));
            }

            $image->stream(); // <-- Key point
            $destinationPath = $destinationPath.'/'.$file->getClientOriginalName();
            Storage::put($destinationPath, $image);

            $article->image = $file->getClientOriginalName();
        }
        
        $article->update($arrSaved);
        $article->categories()->sync($request->get('categories'));
        
        $article->saveTags(Tag::setTag($request->get('tags'), $article->title));

        return redirect()->route($this->controller.'.index')->with('status', __( 'main.data_has_been_updated', ['page' => $article->title] ) );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-delete')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        if ($article->status === 99){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );           
        }

        $article->status = 99;
        $article->save();

        return redirect()->route($this->controller.'.index')->with('status', __( 'main.data_has_been_deleted', ['page' => $article->title] ) );
    }

    public function show(Article $article)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-update')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        if ($article->status === 99){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );           
        }
        
        $utils['action'] = __('main.view');
        $utils['options']['categoriesSelecteds'] = $article->categories()->pluck('dropdown_items.id')->toArray();
        
        return view('admin.'.$this->controller.'.show', compact('article', 'utils'))->with(array('controller' => $this->controller, 'title' => $this->title(), 'description' => $this->description, 'icon' => $this->icon));
    }

    public function getData(Request $request)
    {
        $userLoged = auth()->user();
        if (!$userLoged->can($this->controller.'-index')){
            return redirect()->route('admin.dashboard.index')->with('error', __( 'main.401') );    
        }

        $where = [];
        $where[] = ['status', '<>', 99];   

        $rows = Article::select([
            'id',
            'image',
            'title',
            'published_on',
            'status'
        ])
        ->where($where);

        return Datatables::of($rows)
            ->addIndexColumn()
            ->addColumn('categories', function ($row) {
                $categories = $row->categories()->pluck('dropdown_items.title')->toArray();
                $arrName = [];
                if (count($categories)){
                    foreach ($categories as $v){
                        $arrName[] = $v;
                    }
                }
                return implode(', ', $arrName);
            })
            
            ->addColumn('image', function ($row) {
                return route(config('imagecache.route'), ['template' => 'medium', 'filename' => Article::getImage($row) ]);
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
            ->addColumn('canDelete', function ($row) {
                return auth()->user()->can($this->controller.'-delete');
            })
            ->addColumn('deleteUrl', function ($row) {
                return route($this->controller.'.destroy', $row->id);
            })
            ->addColumn('viewUrl', function ($row) {
                return route($this->controller.'.show', $row->id);
            })
            ->make(true);
    }
}
