<?php

namespace App\Http\Controllers;

use App\Article;
use App\ArticleTag;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ArticleController extends Controller
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
        $articles = Article::select('title', 'slug', 'preview', 'image', 'published_on')
            ->where('status',1)
            ->where('published_on','<=', Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s'))
            ->orderBy('published_on', 'DESC')
            ->paginate(9);

        return view('blog.index', compact('articles'));
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $article = Article::select ('id','title','title_id', 'slug', 'preview','preview_id', 'description','description_id', 'image', 'published_on')
            ->where('status',1)
            ->where('published_on','<=', Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s'))
            ->where('slug',$slug)
            ->firstOrFail();

        //return $article;

        $utils['relateds'] = Article::selectRaw('slug, preview,preview_id, image, title,title_id, published_on')
            ->whereStatus(1)
            ->where('published_on','<=', Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s'))
            ->where('id','!=',$article->id)
            ->whereIn('id', function($query) use ($article) {
                $query->select('article_id')
                    ->from(with(new ArticleTag)->getTable())
                    ->where('tag_id','=', 0);
                    foreach ($article->getTags() as $k => $v){
                        $query->orWhere('tag_id', $k);
                    }
            })
            ->orderBy('published_on', 'DESC')
            ->limit(3)
            ->get();

        return view('blog.show', compact('article', 'utils'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        //
    }
}
