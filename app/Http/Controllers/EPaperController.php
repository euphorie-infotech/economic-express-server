<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use App\Models\EPaper;

class EPaperController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allEPaper = EPaper::all();
        return response()->json([
            'status' => true,
            'message' => 'EPaper fetched Successfully',
            'data' => $allEPaper,
        ], 200);
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
            // Validated
            $validate = Validator::make($request->all(), [
                'images' => 'required',
                'publishDate' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validate->errors()
                ], 401);
            }

            if ($request->has('images')) {
                $files = $request->file('images');
                $page_no = 1;
                //create folder by date  if not exist
                $date = date('Y-m-d', strtotime($request->publishDate));
                $folderPath = 'uploads/epapers/' . $date;

                if (!file_exists($folderPath)) {
                    mkdir($folderPath, 0777, true);
                }

                foreach($files as $file){
                    $destinationPath = $folderPath;
                    $extension = $file->getClientOriginalExtension(); 
                    $image_name = $page_no . '_' . Str::random(10) . '.' . $extension;
                    // Storage::putFileAs(public_path($destinationPath), $image_data, $image_name);
                    $files->move($destinationPath,$image_name);
                    
                    EPaperImages::insertGetId([
                        'image' => $destinationPath . '/' . $image_name,
                        'page_no' => $page_no,
                        'publish_date' => date('Y-m-d',strtotime($date)),
                        'created_by'
                    ]);

                    $page_no++;
                }
                
            }

            // Rest of your code...

        } catch (\Throwable $th) {
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

    public function destroy(Request $request)
    {
        try {
            $date = date('Y-m-d', strtotime($request->input('date')));
            $results = EPaper::where('publish_date', $date)->get();
            
            foreach($results as $result)
            {
                \Storage::delete($result->image);
                EPaper::findOrFail('id', $result->id)->delete();
            }
            return response()->json(["data" => [
                "success" => true
            ]]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
