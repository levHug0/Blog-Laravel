<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Post;

class PostsController extends Controller
{
    public function __construct()
    {
        // This pretty much means, don't allow guests to view any routes for
        // posts, but index and show are excepted, so guests can view all or one post
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$posts = Post::all();
        //$posts = Post::orderBy('title', 'asc')->get();

        $posts = Post::orderBy('created_at', 'desc')->get();

        // GET /posts
        return view('posts.index')->with('posts', $posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // GET /posts/create
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
        // POST /posts

        // This is validation, meaning 'title' and 'body' are required, else it won't submit
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'cover_image' => 'image|nullable|max:1999'
            /*
                image = jpg, png, etc
                nullable = optional
                max = max size i.e. 1999kb just under 2mb
            */
        ]);
        
        // Handle File Upload
        if ( $request->hasFile('cover_image') ) {

            // Get filename with the extension i.e. myImage.jpg
            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
            
            // Get filename i.e. myImage
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

            // Get extension i.e. jpg, png, etc.
            $extension = $request->file('cover_image')->getClientOriginalExtension();

            // Filename to store ie. myImage_1234123.jpg
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;

            // Path of upload image
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);

            // php artisan storage:link to link storage/app/public to public/storage

        } else {
            $fileNameToStore = 'noimage.jpg';
        }

        // Create Post
        $post              = new Post;
        $post->title       = $request->input('title');
        $post->body        = $request->input('body');
        $post->user_id     = auth()->user()->id;
        $post->cover_image = $fileNameToStore;
        $post->save();

        return redirect('/posts')->with('success', 'Post Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // GET /posts/:id
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
        // GET /posts/:id/edit
        $post = Post::find($id);

        // Prevent other users from editing other blog posts
        // Only the user that created the post can do it
        if (auth()->user()->id != $post->user_id) {
            return redirect('/posts')->with('error', 'Unauthorized page');
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
        // PUT /posts/:id
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required'
        ]);

        // Find Post so we can update
        $post = Post::find($id);
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->user_id = auth()->user()->id;

        // Handle File Upload
        if ( $request->hasFile('cover_image') ) {
            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);

            // Since we're updating the cover image, we need to delete old cover image
            // Only if the old image is NOT 'noimage.jpg'
            if ( $post->cover_image != "noimage.jpg" ) {
                Storage::delete('public/cover_images/' . $post->cover_image);
                // error_log('message here.'); This is for printing to terminal
            }

            // Add image to update
            $post->cover_image = $fileNameToStore;
        }

        $post->save();

        return redirect('/posts')->with('success', 'Post Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // DELETE /posts/:id
        $post = Post::find($id);
        
        // Only the correct user can delete their own post
        if (auth()->user()->id !== $post->user_id) {
            return redirect('/posts')->with('error', 'Unauthorized page');
        }

        // If post cover image is not noimage.jpg, DELETE IT in storage 
        if ( $post->cover_image != "noimage.jpg" ) {
            Storage::delete('public/cover_images/' . $post->cover_image);
        }

        $post->delete();
        return redirect('/posts')->with('success', 'Post Removed!');
    }
}
