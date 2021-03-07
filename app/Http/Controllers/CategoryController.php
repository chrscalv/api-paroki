<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use Carbon;
use Auth;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json(Category::all(), 200);
    }

    public function show(Category $category)
    {
        return response()->json(Category::where('slug', '=', $category->slug)->with('Post')->get(), 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'category'  => 'required|unique:category',
            'slug'      => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'error' => $validator->error
            ]);
        }

        $category = Category::create([
            'category'  => $request->category,
            'slug'      => $request->slug
        ]);

        return response()->json([
            'status'    => (bool)$category,
            'category'  => $category
        ]);
    }

    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(),[
            'category'  => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'error' => $validator->error
            ]);
        }

        $category = Category::find($id);
        $category->category = $request['category'];
        $category->slug     = $request['slug'];
        $category->save();

        return response()->json([
            'status'    => (bool)$category,
            'category'  => $category
        ]);
    }

    public function destroy(Category $category)
    {
        $status = $category->delete();

        return response()->json([
            'status'    => $status,
            'message'   => $status ? 'Category Deleted' : 'Error deleting category'
        ]);
    }
}
