<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    public function index(){
        $tags = Tag::orderBy('name', 'asc')->get();
        $products = Product::orderBy('name', 'asc')->get();
    
        $productsByTag = [];
    
        foreach ($tags as $tag) {
            $productsByTag[$tag->slug] = $tag->products;
        }
    
        $data = [
            'products' => $products,
            'tags' => $tags,
            'productsByTag' => $productsByTag,
        ];
    
        return view('mobile.homepage.index', $data);
    }
    
}
