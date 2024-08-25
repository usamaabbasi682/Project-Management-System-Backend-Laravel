<?php

namespace App\Repositories;

use App\Models\Tag;
use App\Repositories\Contracts\TagRepositoryInterface;

class TagRepository implements TagRepositoryInterface
{
    public function tags() 
    {
        $tags = Tag::select('id','name')->orderBy('name','ASC')->get();   
        return $tags;
    }
}
