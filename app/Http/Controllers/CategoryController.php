<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allCategory = Category::all();
        
        foreach($allCategory as $key => $value){
            $allCategory[$key]->metaKeywords = json_decode($allCategory[$key]->metaKeywords);
        }
        
        if(!$allCategory){
            return response()->json([
                'status' => false,
                'message' => 'No Data Found'
            ], 404);
        }
        else{
            return response()->json([
                'status' => true,
                'message' => 'Categories fetched Successfully',
                'data' => $allCategory,
            ], 200);
            
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            //Validated
            $validate = Validator::make($request->all(),
                [
                    'name' => 'required|unique:categories,name',
                    'lang' => 'required',
                    'metaTitle' => 'required',
                    'metaKeywords' => 'required',
                    'metaDescription' => 'required',
                    'status' => 'required',
                ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ], 401);
            }
            
            $category = Category::create([
                'name' => $request->name,
                'lang' => $request->lang,
                'metaTitle' => $request->metaTitle,
                'metaKeywords' => json_encode($request->metaKeywords),
                'metaDescription' => $request->metaDescription,
                'status' => $request->status,
                'createdBy' => 1, //$request->createdBy,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Category Created Successfully',
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($prefix, $id)
    {
        try {
            $category = Category::where('id',$id)->get();
            if(!$category){
                return response()->json([
                    'status' => false,
                    'message' => 'Category fetched Successfully',
                    'data' => $category,
                ], 404);    
            }
            if(isset($category->metaKeywords)){
                $category->metaKeywords = json_decode($category->metaKeywords);
                
            }
            
            return response()->json([
                'status' => true,
                'message' => 'Category fetched Successfully',
                'data' => $category,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $prefix, $id)
    {
        $validate = Validator::make($request->all(),
            [
                'name' => 'required|unique:categories,name,'.$id,
                'lang' => 'required',
                'metaTitle' => 'required',
                'metaKeywords' => 'required',
                'metaDescription' => 'required',
                'status' => 'required',
            ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validate->errors()
            ], 401);
        }
    

        $data = array(
            'name' => $request->name,
            'lang' => $request->lang,
            'metaTitle' => $request->metaTitle,
            'metaKeywords' => json_encode($request->metaKeywords),
            'metaDescription' => $request->metaDescription,
            'status' => $request->status,
            'updatedBy' => 1, //$request->updatedBy
        );

        try {
            $CategoryData = Category::where('id', $id)->update($data);
            
            return response()->json([
                'status' => true,
                'message' => 'Category Updated Successfully'
            ], 200);
        }
        catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($prefix, $id)
    {
        Category::findOrFail($id)->delete();
        
        return response()->json([
                'status' => true,
                'message' => 'Category Deleted Successfully'
            ], 200);
    }
}
