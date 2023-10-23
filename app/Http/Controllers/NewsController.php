<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use App\Models\News;
use App\Models\NewsImages;
use App\Models\NewsTags;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
//        $perPage = $request->per_page ? $request->per_page : 10;
//        $currentPage = $request->current_page ? $request->current_page : 1;
//
//        $news = News::paginate($perPage, ["*"], "page", $currentPage);
        $allNews = News::all();
        return response()->json([
            'status' => true,
            'message' => 'News fetched Successfully',
            'data' => $allNews,
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
                'title' => 'required',
                'description' => 'required',
                'author' => 'required',
                'slug' => 'required',
                'status' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validate->errors()
                ], 401);
            }

            $newsId = News::insertGetId([
                'unique_id' => substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 15),
                'title' => $request->title,
                'description' => $request->description,
                'language' => $request->language,
                'publish_date' => date('Y-m-d',strtotime($request->publishDate)),
                'slug' => date('Ymd', strtotime($request->publishDate)) . '-' . Str::slug($request->title, '-'),
                'author' => $request->author,
                'type' => 1,
                'image' => $img_path,
                'is_published' => $request->isPublished,
                'is_featured' => $request->isFeatured,
                'status' => $request->status,
                'createdBy' => $request->createdBy,
            ]);

            if ($request->has('image')) {
                $files = $request->file('image');
                // foreach($files as $file){
                    $destinationPath = 'uploads/news_image/';
                    $extension = $file->getClientOriginalExtension(); 
                    $image_name = $newsId . '_' . time() . '_' . Str::random(10) . '.' . $extension;
                    // Storage::putFileAs(public_path($destinationPath), $image_data, $image_name);
                    $files->move($destinationPath,$image_name);
                    
                    NewsImages::insertGetId([
                        'newsId' => $id,
                        'location' => $destinationPath . '/' . $image_name,
                    ]);
                // }
                
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
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(),
            [
                'title' => 'required',
                'description' => 'required',
                'author' => 'required',
                'status' => 'required',
            ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validate->errors()
            ], 401);
        }

        $img_path = false;
        if ($request->has('image')) {
            $files = $request->file('image');
            // foreach($files as $file){
                $destinationPath = 'uploads/news_image/';
                $extension = $file->getClientOriginalExtension(); 
                $image_name = $newsId . '_' . time() . '_' . Str::random(10) . '.' . $extension;
                // Storage::putFileAs(public_path($destinationPath), $image_data, $image_name);
                $files->move($destinationPath,$image_name);
                
                NewsImages::insertGetId([
                    'newsId' => $id,
                    'location' => $destinationPath . '/' . $image_name,
                ]);
            // }
            
        }

        $data = array(
            'title' => $request->title,
            'description' => $request->description,
            'language' => $request->language,
            'publish_date' => date('Y-m-d',strtotime($request->publishDate)),
            'slug' => date('Ymd', strtotime($request->publishDate)) . '-' . Str::slug($request->title, '-'),
            'author' => $request->author,
            'type' => 1,
            'image' => $img_path,
            'is_published' => $request->isPublished,
            'is_featured' => $request->isFeatured,
            'status' => $request->status,
            'updated_by' => $request->updatedBy
        );

        try {
            $data = News::where('id', $id)->update($data);
            NewsTags::findOrFail('newsId', $id)->delete();

            if ($id) {
                foreach ($request->tags as $tag) {
                    $newsId = NewsTags::insertGetId([
                        'newsId' => $id,
                        'tagId' => $tag,
                    ]);
                }
            }

            return response()->json(["data" => [
                "success" => true,
                'message' => 'News Updated Successfully',
            ]]);
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
    public function destroy($id)
    {
        News::findOrFail($id)->delete();
        NewsTags::findOrFail('newsId', $id)->delete();
        $this->deleteImage($id);

        return response()->json(["data" => [
            "success" => true
        ]]);
    }

    public function deleteImage($id)
    {
        try {
            $data = News::findOrFail($id);
            \Storage::delete($data->image);

            News::findOrFail('newsId', $id)->delete();

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
