<?php

namespace App\Http\Controllers;

use App\Http\Resources\PublicResource;
use App\Models\News;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function getActiveNews()
    {
        try {
            $data = News::join('categories', 'categories.id', '=', 'news.category_id')
                ->join('news_tags', 'news_tags.news_id', '=', 'news.Id')
                ->join('tags', 'tags.id', '=', 'news_tags.tag_id')
                ->where('is_published', 1)
                ->where('news.status', 1)
                ->orderBy('category_id', 'asc')
                ->orderBy('is_featured', 'asc')
                ->orderBy('id', 'desc')
                ->select('news.*', 'tags.name_en as tagname_en', 'tags.name_bn as tagname_bn', 'categories.name_en as catname_en', 'categories.name_bn as catname_bn')
                ->get();

            return response()->json([
                'status' => true,
                'data' => $data,
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function getActiveNewsByCategory()
    {
        try {
            if ($request->has('category')) {
                $name = $request->input('category');
            }

            $data = News::join('categories', 'categories.id', '=', 'news.category_id')
                ->join('news_tags', 'news_tags.news_id', '=', 'news.Id')
                ->join('tags', 'tags.id', '=', 'news_tags.tag_id')
                ->where('is_published', 1)
                ->where('news.status', 1)
                ->where('categories.name_en', $name)
                ->orderBy('is_featured', 'asc')
                ->orderBy('id', 'desc')
                ->select('news.*', 'categories.name_en as catname_en', 'categories.name_bn as catname_bn', 'tags.name_en as tagname_en', 'tags.name_bn as tagname_bn')
                ->get();

            return response()->json([
                'status' => true,
                'data' => $data,
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function getActiveNewsByTag($name)
    {
        try {
            if ($request->has('tag')) {
                $name = $request->input('tag');
            }

            $data = News::join('tags', 'tags.id', '=', 'news.category_id')
                ->join('news_tags', 'news_tags.news_id', '=', 'news.Id')
                ->join('tags', 'tags.id', '=', 'news_tags.tag_id')
                ->where('is_published', 1)
                ->where('news.status', 1)
                ->where('tags.name_en', $name)
                ->orderBy('is_featured', 'asc')
                ->orderBy('id', 'desc')
                ->select('news.*', 'categories.name_en as catname_en', 'categories.name_bn as catname_bn', 'tags.name_en as tagname_en', 'tags.name_bn as tagname_bn')
                ->get();

            return response()->json([
                'status' => true,
                'data' => $data,
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function getNewsById($name, $id)
    {
        try {
            $data = News::join('categories', 'categories.id', '=', 'news.category_id')
                ->join('news_tags', 'news_tags.news_id', '=', 'news.Id')
                ->join('tags', 'tags.id', '=', 'news_tags.tag_id')
                ->where('categories.name_en', $name)
                ->where('unique_id', $id)
                ->orderBy('is_featured', 'asc')
                ->select('news.*', 'categories.name_en as catname_en', 'categories.name_bn as catname_bn', 'tags.name_en as tagname_en', 'tags.name_bn as tagname_bn')
                ->get();

            return response()->json([
                'status' => true,
                'data' => $data,
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
