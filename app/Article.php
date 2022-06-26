<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $guarded = [
        'id'
    ];

    public function categories() {
        return $this->belongsToMany('App\DropdownItem', 'article_dropdown_item');
    }

    public function getTags($useTitle=false)
    {
        $rows = ArticleTag::leftJoin('tags', 'tags.id', '=', 'article_tags.tag_id')
            ->where('article_tags.article_id',$this->id);

        if (!$useTitle){
            $rows->where('is_title', 0);
        }
        
        return $rows->pluck('tags.name','tags.id')
            ->toArray();
    }

    public function saveTags($tags)
    {
        
        if (is_string($tags))
            $tags = explode(',', $tags);

        $tags = array_map('trim', $tags);
        
        $current_tags = $this->getTags();

        // no tag before! no tag now! ... nothing to do!
        if (count($tags) == 0 && count($current_tags) == 0)
            return;

        // delete all tags
        if (count($tags) == 0)
        {
            // update count (-1) of those tags
            foreach($current_tags as $tag) {
                Tag::where('name', $tag)
                    ->update(['count' => 'count - 1']);
            }

            ArticleTag::where('article_id', $this->id)
                ->delete();
        }
        else
        {
            $old_tags = array_diff($current_tags, $tags);
            $new_tags = array_diff($tags, $current_tags);
            

            // insert all tags in the tag table and then populate the product_tag table
            foreach ($new_tags as $index => $tag_name)
            {
                if ( ! empty($tag_name))
                {
                    // try to get it from tag list, if not we add it to the list
                    $tag = Tag::where('name',$tag_name)
                        ->first();

                    if (!$tag) {
                        $tag = new Tag(array(
                            'name' => trim($tag_name),
                            'is_title' => trim($tag_name) == $this->title ? 1 : 0
                        ));
                    }

                    $tag->count++;
                    $tag->save();

                    // create the relation between the product and the tag
                    $articleTag = new ArticleTag(array(
                        'article_id' => $this->id,
                        'tag_id' => $tag->id
                    ));

                    $articleTag->save();
                }
            }

            // remove all old tag
            foreach ($old_tags as $index => $tag_name)
            {
                // get the id of the tag
                $tag = Tag::where('name',$tag_name)
                    ->first();

                ArticleTag::where('article_id', $this->id)
                    ->where('tag_id', $tag->id)
                    ->delete();

                $tag->count--;
                $tag->save();
            }
        }
    }

    public static function getImage($slideshow)
    {
        
        if (!is_null($slideshow->image)) {
            return $slideshow->image;
        } else {
            return 'noimage.jpg';
        }
    }

    public function setImage()
    {
        if (!is_null($this->image)) {
            return $this->image;
        } else {
            return 'noimage.jpg';
        }
    }
}
