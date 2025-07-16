<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Group;
use App\Models\User;
use App\Models\Comment;
use App\Models\Topic;
use App\Models\Image;
use App\Models\Like;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function show($query)
    {
        // TODO: get query from database

        // THIS IS A PUBLIC API

        return view('pages.search');
    }


    public function search(Request $request)
    {

        $query_string = trim($request->route('query_string'));
        $type_search = $request->route('type_search');
        $type_order = $request->route('type_order');
        $offset = $request->route('offset');

        if ($query_string === '*') $query_string = ' ';

        $searchItems = [];

        $query_string = str_replace("%23", "#", $query_string);
        $query_string = str_replace("%20", " ", $query_string);

        if ($type_search === "users") {
            $searchItems = $this->searchUsers($query_string);
        } else if ($type_search === "groups") {
            $searchItems = $this->searchGroups($query_string);
        } else if ($type_search === "posts") {
            $searchItems = $this->searchPosts($query_string, $type_order, $offset);
        } else if ($type_search === "topics") {
            $searchItems = $this->searchTopics($query_string);
        }

        return json_encode($searchItems);
    }


    private function searchUsers($query_string)
    {   // this also includes de tsvectors of bio
        // FUNCTION CALLED IN SEARCH
        if ($query_string[0] === '#') {
            $query_string = substr($query_string, 1);

            $topics_search = explode("#", $query_string);

            if ($topics_search[0] === "") {
                array_shift($topics_search);
            }

            for ($i = 0; $i < sizeof($topics_search); $i++) {
                $topics_search[$i] = trim($topics_search[$i]);
            }
        
            $users = User::whereHas('topics_names', function ($query) use ($topics_search) {
                $query->whereIn('topic', $topics_search);
            })->get();

        } else {
            $users = User::whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?)', [$query_string])
                ->orWhere('username', 'ILIKE', '%' . $query_string . '%')
                ->selectRaw('*, ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) as ranking', [$query_string])
                ->orderBy('ranking', 'desc')
                ->limit(40)
                ->get();
        }

        

        return $users;
    }


    private function searchGroups($query_string)
    {   // this also includes de tsvectors of bio
        // FUNCTION CALLED IN SEARCH
        if ($query_string[0] === '#') {
            $query_string = substr($query_string, 1);

            $topics_search = explode("#", $query_string);

            if ($topics_search[0] === "") {
                array_shift($topics_search);
            }

            for ($i = 0; $i < sizeof($topics_search); $i++) {
                $topics_search[$i] = trim($topics_search[$i]);
            }

            $groups = Group::whereHas('topics_names', function ($query) use ($topics_search) {
                $query->whereIn('topic', $topics_search);
            })->get();

        } else {
            $groups = Group::whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?)', [$query_string])
                ->orWhere('name', 'ILIKE', '%' . $query_string . '%')
                ->selectRaw('*, ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) as ranking', [$query_string])
                ->orderBy('ranking', 'desc')
                ->limit(40)
                ->get();

        }

        return $groups;
    }


    private function searchPosts($query_string, $type_order, $offset)
    { // this also includes de tsvectors of comments

        if ($query_string[0] !== '#') {
            $posts = $this->searchPostsFTS($query_string, $type_order, $offset);
            foreach ($posts as $post) {
                $post->topics = app('App\Http\Controllers\PostController')->post_topics($post->id);
            }
        } else {
            $posts = $this->searchPostsTopic($query_string, $type_order, $offset);
        }


        foreach ($posts as $post) {
            $post->images = Image::select('path')->where('id_post', $post->id)->get();
            $post->hasLiked = false;
            $post->isOwner = false;
            $post->auth = 0;

            $post->post_date =  Carbon::parse($post->post_date)->diffForHumans();

            if (!Auth::check()) continue;
            $post->auth = Auth::user()->id;

            if ($post->owner === Auth::user()->username) {
                $post->isOwner = true;
            }

            $like = Like::where('id_post', $post->id)->where('id_user', Auth::user()->id)->get();

            if (sizeof($like) > 0) {
                $post->hasLiked = true;
            }
        }

        return $posts;
    }


    private function searchPostsTopic($query_string, $type_order, $offset)
    {
        $posts = [];

        $topics_search = explode("#", $query_string);

        if ($topics_search[0] === "") {
            array_shift($topics_search);
        }

        for ($i = 0; $i < sizeof($topics_search); $i++) {
            $topics_search[$i] = trim($topics_search[$i]);
        }

        if (Auth::check()) {
            $posts_groups = app('App\Http\Controllers\PostController')->feed_groups()->whereHas('topics_names', function ($query) use ($topics_search) {
                $query->whereIn('topic', $topics_search);
            });
            $posts_friends = app('App\Http\Controllers\PostController')->feed_friends()->whereHas('topics_names', function ($query) use ($topics_search) {
                $query->whereIn('topic', $topics_search);
            });
            $posts = app('App\Http\Controllers\PostController')->feed_viral()->whereHas('topics_names', function ($query) use ($topics_search) {
                $query->whereIn('topic', $topics_search);
            });
             
            $posts = $posts
                ->union($posts_groups)
                ->union($posts_friends);

            $posts = DB::table(DB::raw("({$posts->toSql()}) as sub"))
                ->mergeBindings($posts->getQuery()) // you need to get underlying Query Builder
                ->distinct();
            
        } else {
            $posts = app('App\Http\Controllers\PostController')->feed_viral();
        }


        if ($type_order === "date") {
            $posts = $posts->orderBy('post_date', 'desc');
        } else if ($type_order === "likes") {
            $posts = $posts->orderBy('likes_count', 'desc');
        } else if ($type_order === "comments") {
            $posts = $posts->orderBy('comments_count', 'desc');
        }

        $posts = $posts->skip($offset)->limit(20)->get();

        foreach ($posts as $post) {
            $post->topics = app('App\Http\Controllers\PostController')->post_topics($post->id);
        }

        return $posts;
    }

    private function searchPostsFTS($query_string, $type_order, $offset)
    {

        $comments = Comment::selectRaw('id_post, count(comment.id) as comments_count, tsvector_agg(tsvectors) as tsvector_comment')
            ->groupBy('id_post');

        $posts = DB::table(DB::raw("({$comments->toSql()}) as comment"))
            ->mergeBindings($comments->getQuery())
            ->join('post', 'post.id', '=', 'id_post')
            ->whereRaw('(post.tsvectors || tsvector_comment) @@ plainto_tsquery(\'english\', ?)', [$query_string])
            ->join('user', 'user.id', '=', 'post.id_poster');

        if (Auth::check()) { // POLICIE ... Different results if logged n
            $posts = $posts
                ->whereIn('id_poster', function ($query) {
                    $id = Auth::user()->id;
                    $query1 = DB::table('friend_request')
                        ->selectRaw('id_user_sender as friend')
                        ->from('friend_request')
                        ->where('id_user_receiver', $id)
                        ->where('acceptance_status', 'Accepted');

                    $query->select('id_user_receiver as friend')
                        ->from('friend_request')
                        ->where('id_user_sender', $id)
                        ->where('acceptance_status', 'Accepted')
                        ->union($query1);
                })
                ->orWhere('user.visibility', '=', true);
        } else {
            $posts = $posts->where('user.visibility', '=', true);
        }

        $posts = $posts->whereRaw('(post.tsvectors || tsvector_comment)::tsvector @@ plainto_tsquery(\'english\', ?)', [$query_string])
            ->selectRaw('
            post.id, post.text, post.post_date, username as owner, id_poster, photo,
            comments_count,
            ts_rank((post.tsvectors || tsvector_comment)::tsvector, plainto_tsquery(\'english\', ?)) as ranking', [$query_string]);

        $posts = DB::table(DB::raw("({$posts->toSql()}) as sub"))
            ->mergeBindings($posts) // you need to get underlying Query Builder
            ->join('like_post', 'like_post.id_post', '=', 'id')
            ->selectRaw('
            id, text, post_date, owner, id_poster, photo,
            comments_count,
            count(like_post.id_user) as likes_count, ranking')
            ->groupBy('id', 'text', 'post_date', 'owner', 'id_poster', 'photo', 'comments_count', 'ranking');

        if ($type_order === "date") {
            $posts = $posts->orderBy('post_date', 'desc');
        } else if ($type_order === "likes") {
            $posts = $posts->orderBy('likes_count', 'desc');
        } else if ($type_order === "comments") {
            $posts = $posts->orderBy('comments_count', 'desc');
        } else {
            $posts = $posts->orderBy('ranking', 'desc');
        }

        $posts = $posts->skip($offset)->limit(20)->get();

        return $posts;
    }


    private function searchTopics($query_string)
    {
        // FUNCTION CALLED IN SEARCH
        if ($query_string[0] === '#') {
            $query_string = substr($query_string, 1);
        }

        $topics = Topic::where('topic', 'ILIKE', '%' . $query_string . '%')
            ->limit(30)
            ->get();

        return $topics;
    }
}
