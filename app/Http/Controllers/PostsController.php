<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Post;

class PostsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // $posts = Post::all();
        // $posts = Post::orderBy('created_at', 'desc')->get();

        // use DB`를 하면 직접 쿼리를 날릴수 있다
        // $posts = DB::select('SELECT * FROM posts');

        $posts = Post::orderBy('id', 'desc')->paginate(4);
        return view('posts.index')->with('posts', $posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'cover_image' => 'image|nullable|max:1024'
        ]);

        if($request->hasFile('cover_image')) {
            // get file name with extension
            $fileNameWithExt = $request->file('cover_image')->getClientOriginalName();

            // get just file name
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);

            // get just ext
            $extension = $request->file('cover_image')->getClientOriginalExtension();

            // file name to store
            $fileNameToStore = $fileName . "_" . time() . "_" .$extension;

            // actually upload image
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);
        } else {
            $fileNameToStore = 'noimage.jpg';
        }

        $post = new Post;
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->user_id = auth()->user()->id;
        $post->cover_image = $fileNameToStore;
        $post->save();

        return redirect()->route('posts.index')->with('success', 'Post created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        return view('posts.show')->with('post', $post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);

        // URL에 직접 다른 사람의 post_id/edit 하려고 할때 자기것이 맞는지 확인
        if(auth()->user()->id !== $post->user_id) {
            return redirect()->route('posts.index')->with('error', 'Unauthorized page');
        }

        return view('posts.edit')->with('post', $post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'cover_image' => 'image|nullable|max:1024' 
        ]);

        $post = Post::find($id);

        // URL에 직접 다른 사람의 post_id/update 하려고 할때 자기것이 맞는지 확인
        if(auth()->user()->id !== $post->user_id) {
            return redirect()->route('posts.index')->with('error', 'Unauthorized page');
        }

        // handle the file upload
        if($request->hasFile('cover_image')) {
            // 기존 업로드한 이미지가 있을경우 삭제
            if($post->cover_image != 'noimage.jpg') {
                Storage::delete('public/cover_images/'.$post->cover_image);
            }
            
            // get filename with exensions
            $fileNameWithExt = $request->file('cover_image')->getClientOriginalName();

            // get just filename
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);

            // get just ext
            $extension = $request->file('cover_image')->getClientOriginalExtension();

            // fileName to store
            $fileNameToStore = $fileName . '_' . time() . '.' . $extension;

            // acutally upload image
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);
        }

        $post->title = $request->input('title');
        $post->body = $request->input('body');
        if($request->hasFile('cover_image')) {
            $post->cover_image = $fileNameToStore;
        }
        $post->save();

        return redirect()->route('posts.index')->with('success', 'Post updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);

        // URL에 직접 다른 사람의 post_id/destrory하려고 할때 자기것이 맞는지 확인
        if(auth()->user()->id !== $post->user_id) {
            return redirect()->route('posts.index')->with('error', 'Unauthorized page');
        }

        // delete image
        if($post->cover_image != 'noimage.jpg') {
            Storage::delete('public/cover_images/'.$post->cover_image);
        }

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post removed');
    }
}
