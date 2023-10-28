<?php

namespace App\Http\Controllers;

use App\Http\Resources\PublicResource;
use App\Models\News;
use App\Models\EPaper;
use Illuminate\Http\Request;
use \stdClass;

class PublicController extends Controller
{
    public function getActiveNews(Request $request)
    {
        try {
            $data = News::select('news.id', 'news.title', 'news.description', 'categories.name_en as catname_en', 'categories.name_bn as catname_bn', 'tags.id as tag_id', 'tags.name_en as tagname_en', 'tags.name_bn as tagname_bn')
                ->join('categories', 'categories.id', '=', 'news.category_id')
                ->leftJoin('news_tags', 'news_tags.news_id', '=', 'news.id')
                ->leftJoin('tags', 'tags.id', '=', 'news_tags.tag_id')
                ->where('is_published', 1)
                ->groupBy('news.id', 'news.title', 'news.description', 'categories.name_en', 'categories.name_bn', 'tags.id', 'tags.name_en', 'tags.name_bn')
                ->orderBy('category_id', 'asc')
                ->orderBy('is_featured', 'asc')
                ->orderBy('id', 'desc');

            if(!empty($request->input('id')))
            {
                $data = $data->where('unique_id',trim($request->input('id')))->get();
            }
            elseif(!empty($request->input('category')))
            {
                $data = $data->where('categories.name_en',trim($request->input('category')))->get();
                
                if(empty($data))
                {
                    $data = $data->where('categories.name_bn',trim($request->input('category')))->get();
                }
            }
            elseif(!empty($request->input('tag')))
            {
                $data = $data->where('tags.name_en',trim($request->input('tag')))->get();
                
                if(empty($data))
                {
                    $data = $data->where('tags.name_bn',trim($request->input('tag')))->get();
                }
            }
            else{
                $data = $data->get();
            }
            $type = new stdClass;
            $type->tags = [];

            foreach ($data as $row) {
                $tag = new stdClass;
                $tag->id = $row->tag_id;
                $tag->tagname_en = $row->tagname_en;
                $tag->tagname_bn = $row->tagname_bn;

                if (!isset($type->{$row->id})) {
                    $newsItem = new stdClass;
                    $newsItem->id = $row->id;
                    $newsItem->title = $row->title;
                    $newsItem->description = $row->description;
                    $newsItem->catname_en = $row->catname_en;
                    $newsItem->catname_bn = $row->catname_bn;
                    $newsItem->tags = [];
                    $type->{$row->id} = $newsItem;
                }

                $type->{$row->id}->tags[] = $tag;
            }

            return response()->json([
                'status' => true,
                'data' => array_values((array) $type),
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    //get news details by slug
    public function getNewsBySlug(Request $request)
    {
        try {
            $slug = $request->input('slug');
            $data['detailedNews'] = News::where('slug', ltrim($slug))
                ->where('status', 1)->where('is_published', 1)->first();
            // get similar news by category
            if(!empty($data['detailedNews']->category_id)) {
                $data['similarNews'] = News::where('category_id', $data['detailedNews']->category_id)
                    ->where('status', 1)
                    ->where('is_published', 1)
                    ->where('unique_id', '!=', $data['detailedNews']->unique_id)
                    ->select('unique_id','slug','title')
                    ->limit(10)->get();
                
            }
            return response()->json([
                'status' => true,
                'data' => $data,
            ], 200);
        } catch(\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    //get 10 featured news
    public function getFeaturedNews()
    {
        try{
            $data = News::where('is_featured', 1)
            ->where('status', 1)
            ->where('is_published', 1)
            ->orderBy('id', 'desc')
            ->limit(10)-get();

            return response()->json([
                'status' => true,
                'data' => $data,
            ], 200);
        } catch(\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    //view epaper
    public function viewEPaper(Request $request)
    {
        try
        {
            $date = date('Y-m-d', strtotime($request->input('date')));
            $data = EPaper::orderBy('page_no', 'asc')
                ->where('publish_date', $date)
                ->where('is_published', 1)
                ->where('status', 1)
                ->pagination(1);
            return response()->json([
                'status' => true,
                'data' => $data,
                'total' => $data->total(),
                'count' => $data->count(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'total_pages' => $data->lastPage(),
            ], 200);
        }
        catch(Throwable $th)
        {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 404);
        }
    }

}
