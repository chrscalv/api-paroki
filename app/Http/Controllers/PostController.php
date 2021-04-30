<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Auth;
class PostController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if($user->hasPermissionTo('see all post')){
            return response()->json(Post::with('tagged', 'user:name', 'category:category')->orderBy('id', 'DESC')->get(), 200);
        }else{
            return response()->json([
                'response'  => 401,
                'message'   => 'Anauthorized'
            ]);
        }
    }

    public function allByCategory(Category $category)
    {
        return response()->json(Post::Where('category_id', '=', $category->id)->Where('is_published', '=', true)->with(['user' => fn ($query) => $query->select('id','name')])->get(), 200);
    }

    public function allByTag()
    {
        return response()->json(Post::tagNames()->orderBy('id', 'DESC')->get(), 200);
    }

    public function all()
    {
        return response()->json(Post::with('tagged','User','Category')->where('is_published','=', true)->orderBy('id', 'DESC')->get(), 200);
    }

    public function show($slug)
    {
        return response()->json(Post::where('slug', '=', $slug)->with('tagged', 'User')->get(), 200);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if($user->hasPermissionTo('create post')){
            $validator = Validator::make($request->all(), [
                'title'             => 'required|max:255|unique:posts',
                'body'              => 'required',
                'category_id'       => 'required'
            ]);
    
            if($validator->fails()){
                return response()->json([
                    'error' => $validator->errors()
                ]);
            }
            
            if($request->boolean('is_published') === true){
                $post = Post::create([
                    'title'         => $request->title,
                    'body'          => $request->body,
                    'is_published'  => true,
                    'published_at'  => now(),
                    'user_id'       => Auth::id(),
                    'category_id'   => $request->category_id
                ]);
    
                return response()->json([
                    'post'      => $post,
                    'message'   => $post ? 'Post Published' : 'error Publishing Post'
                ]);
            }else{
                $post = Post::create([
                    'title'         => $request->title,
                    'body'          => $request->body,
                    'is_published'  => false,
                    'user_id'       => Auth::id(),
                    'category_id'   => $request->category_id
                ]);
                
                foreach($post->tags as $tag) {
                    echo $tag->name . ' with url slug of ' . $tag->slug;
                }
                $post->tag(explode(',', $request->tags));                
    
                return response()->json([
                    'post'    => $post,
                    'message' => $post ? 'Post Created' : 'Error Creating Post'
                ]);
            }
        }else{
            return response()->json([
                'response'  => 401,
                'message'   => 'Unauthorized'
            ]);
        }
    }

    public function published($id)
    {
        $user = Auth::user();
        if($user->hasPermissionTo('publish post')){
            $post = Post::find($id);
            $post->is_published = true;
            $post->published_at = now();
            $post->save();

            return response()->json([
                'status'    => $post,
                'message'   => $post ? 'Post Published' : 'Error Publishing Post'
            ]);
        }else{
            return response()->json([
                'response'     => 401,
                'message'      => 'unauthorized'
            ], 401);
        }
    }

    public function archived($id)
    {
        $user = Auth::user();
        if($user->hasPermissionTo('unpublish post')){
            $post = Post::find($id);
            $post->is_published = false;
            $post->save();
    
            return response()->json([
                'status'    => $post,
                'message'   => $post ? 'Post Archived' : 'Error Archive Post'
            ]);
        }else{
            return response()->json([
                'error'     => '401 Unauthorized'
            ]);
        }
    }

    public function update(Request $request,$id)
    {
        $user = Auth::user();
        if($user->hasPermissionTo('update post')){
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
        }else{
            return response()->json(['error'=>'401 Unauthorized']);
        }
    }

    public function destroy(Post $post)
    {
        $user = Auth::user();
        if($user->hasPermissionTo('delete post')){
            $status = $post->delete();

            return response()->json([
                'status'    => $status,
                'message'   => $status ? 'Post Deleted' : 'Error Deleting Post'
            ]);
        }else{
            return response()->json([
                'error' => '401 Unauthorized'
            ]);
        }
    }
}
