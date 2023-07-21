<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostRresource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();

        return new PostRresource(true, 'List Data Post', $posts);
    }
    public function store(Request $request)
    {
        $validator = FacadesValidator::make($request->all(),[
            'image'     => 'required|image|mimes:jpeg,png,jpg',
            'title'   => 'required',
            'content'   => 'required',
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors(),422);
        }

        $image = $request->file('image');
        $image->storeAs('/public/posts'.$image->hashName());

        $post = Post::create([
            'image'     => $image->hashName(),
            'title'     => $request->title,
            'content'     => $request->content,
        ]);

        return new PostRresource(true,'Data Berhasil DItambahkan',$post);
    }

    public function show($id)
    {
        $posts = Post::find($id);

        return new PostRresource(true, 'List Data Post', $posts);
    }
}
