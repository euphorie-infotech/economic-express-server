<?php

namespace App\Http\Controllers;

use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allTag = Tag::all();
        
        foreach($allTag as $key => $value){
            $allTag[$key]->metaKeywords = json_decode($allTag[$key]->metaKeywords);
        }
        
        if(!$allTag){
            return response()->json([
                'status' => false,
                'message' => 'No Data Found'
            ], 404);
        }
        else{
            return response()->json([
                'status' => true,
                'message' => 'tags fetched Successfully',
                'data' => $allTag,
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
                    'name' => 'required|unique:tags,name',
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
            
            $tag = Tag::create([
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
                'message' => 'Tag Created Successfully',
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
            $tag = Tag::where('id',$id)->get();
            if(!$tag){
                return response()->json([
                    'status' => false,
                    'message' => 'Tag fetched Successfully',
                    'data' => $tag,
                ], 404);    
            }
            if(isset($tag->metaKeywords)){
                $tag->metaKeywords = json_decode($tag->metaKeywords);
                
            }
            
            return response()->json([
                'status' => true,
                'message' => 'Tag fetched Successfully',
                'data' => $tag,
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
                'name' => 'required|unique:tags,name,'.$id,
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
            $TagData = Tag::where('id', $id)->update($data);
            
            return response()->json([
                'status' => true,
                'message' => 'Tag Updated Successfully'
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
        Tag::findOrFail($id)->delete();
        
        return response()->json([
                'status' => true,
                'message' => 'Tag Deleted Successfully'
            ], 200);
    }
}
