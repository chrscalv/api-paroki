<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\Post;
use App\Models\User;

class PostController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return response()->json(Post::all(), 200);
    }

    public function allByTag()
    {
        //
    }

    public function all()
    {
        return response()->json(Post::where('is_published', true)->with('Tag')->first(), 200);
    }

    public function show(Post $post)
    {
        return response()->json(Post::where('id', '=', $post->id)->withAllTags()->get(), 200);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if($user->hasPermissionTo('create-post')){
            $validator = Validator::make($request->all(), [
                'title'             => 'required|max:255',
                'body'              => 'required',
                'category_id'       => 'required'
            ]);
    
            if($validator->fails()){
                return response()->json([
                    'error' => $validator->error
                ]);
            }
    
            if($request->is_published = true){
                $post = Post::create([
                    'title'         => $request->title,
                    'body'          => $request->body,
                    'is_published'  => true,
                    'user_id'       => $request->user_id,
                    'category_id'   => $request->category_id
                ]);
    
                return response()->json([
                    'post'      => $post,
                    'message'   => $post ? 'Post Published' : 'error Publishing Post'
                ]);
            }
            else{
                $post = Post::create([
                    'title'         => $request->title,
                    'body'          => $request->body,
                    'is_published'  => false,
                    'user_id'       => $request->user_id,
                    'category_id'   => $request->category_id
                ]);
    
                return response()->json([
                    'post'    => $post,
                    'message' => $post ? 'Post Created' : 'Error Creating Post'
                ]);
            }
        }
    }

    public function published($id)
    {
        $post = Post::find($id);
        $post->is_published = true;
        $post->save();

        return response()->json([
            'status'    => $post,
            'message'   => $post ? 'Post Published' : 'Error Publishing Post'
        ]);
    }

    public function archive($id)
    {
        $post = Post::find($id);
        $post->is_published = false;
        $post->save();

        return response()->json([
            'status'    => $post,
            'message'   => $post ? 'Post Archived' : 'Error Archive Post'
        ]);
    }

    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(),[
            'title'         => 'required|max:255',
            'body'          => 'required',
            'category_id'   => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'error'     => $validator->error
            ]);
        }

        $data = Post::find($id);
        $data->title        = $request['title'];
        $data->body         = $request['body'];
        $data->category_id  = $request['category_id'];
        $data->save();

        return response()->json([
            'post'      => $data,
            'message'   => $data ? 'Post Updated' : 'Error Updating Post'
        ]);
    }

    public function destroy(Post $post)
    {
        $status = $post->delete();

        return response()->json([
            'status'    => $status,
            'message'   => $status ? 'Post Deleted' : 'Error Deleting Post'
        ]);
    }
}
