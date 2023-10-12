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
            $data = News::join('categories', 'categories.id', '=', 'news.categoryId')
                ->join('news_tags', 'news_tags.newsId', '=', 'news.Id')
                ->join('tags', 'tags.id', '=', 'news_tags.tagId')
                ->where('tags.lang', 'en')
                ->where('categories.lang', 'en')
                ->where('isPublished', 1)
                ->where('news.status', 1)
                ->orderBy('categoryId', 'asc')
                ->orderBy('isFeatured', 'asc')
                ->orderBy('id', 'desc')
                ->select('news.*', 'categories.nameen as catNameEn', 'categories.namebn as catNameBn', 'tags.nameen as tagNameEn', 'tags.namebn as tagNameBn')
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

    public function getActiveNewsByCategory($name)
    {
        try {
            $data = News::join('categories', 'categories.id', '=', 'news.categoryId')
                ->join('news_tags', 'news_tags.newsId', '=', 'news.Id')
                ->join('tags', 'tags.id', '=', 'news_tags.tagId')
                ->where('isPublished', 1)
                ->where('news.status', 1)
                ->where('categories.nameen', $name)
                ->orderBy('isFeatured', 'asc')
                ->orderBy('id', 'desc')
                ->select('news.*', 'categories.nameen as catNameEn', 'categories.namebn as catNameBn', 'tags.nameen as tagNameEn', 'tags.namebn as tagNameBn')
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
            $data = News::join('tags', 'tags.id', '=', 'news.categoryId')
                ->join('news_tags', 'news_tags.newsId', '=', 'news.Id')
                ->join('tags', 'tags.id', '=', 'news_tags.tagId')
                ->where('isPublished', 1)
                ->where('news.status', 1)
                ->where('tags.nameen', $name)
                ->orderBy('isFeatured', 'asc')
                ->orderBy('id', 'desc')
                ->select('news.*', 'categories.nameen as catNameEn', 'categories.namebn as catNameBn', 'tags.nameen as tagNameEn', 'tags.namebn as tagNameBn')
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
            $data = News::join('categories', 'categories.id', '=', 'news.categoryId')
                ->join('news_tags', 'news_tags.newsId', '=', 'news.Id')
                ->join('tags', 'tags.id', '=', 'news_tags.tagId')
                ->where('categories.nameen', $name)
                ->where('unique_id', $id)
                ->orderBy('isFeatured', 'asc')
                ->select('news.*', 'categories.nameen as catNameEn', 'categories.namebn as catNameBn', 'tags.nameen as tagNameEn', 'tags.namebn as tagNameBn')
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
