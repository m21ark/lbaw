<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Group;
use App\Models\User;
use App\Models\Comment;
use App\Models\Topic;
use App\Models\Image;
use App\Models\Like;
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

        $query_string = $request->route('query_string');
        $type_search = $request->route('type_search');

        if ($query_string === '*') $query_string = '';

        $searchItems = [];

        if ($type_search === "users") {
            $searchItems = $this->searchUsers($query_string);
        } else if ($type_search === "groups") {
            $searchItems = $this->searchGroups($query_string);
        } else if ($type_search === "posts") {
            $searchItems = $this->searchPosts($query_string);
        } else if ($type_search === "topics") {
            $searchItems = $this->searchTopics($query_string);
        }

        return json_encode($searchItems);
    }


    private function searchUsers($query_string)
    {   // this also includes de tsvectors of bio
        // FUNCTION CALLED IN SEARCH
        $users = User::whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?)', [$query_string])
            ->orWhere('username', 'LIKE', '%' . $query_string . '%')
            ->selectRaw('*, ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) as ranking', [$query_string])
            ->orderBy('ranking', 'desc')
            ->limit(40)
            ->get();

        return $users;
    }


    private function searchGroups($query_string)
    {   // this also includes de tsvectors of bio
        // FUNCTION CALLED IN SEARCH
        $groups = Group::whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?)', [$query_string])
            ->orWhere('name', 'LIKE', '%' . $query_string . '%')
            ->selectRaw('*, ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) as ranking', [$query_string])
            ->orderBy('ranking', 'desc')
            ->limit(40)
            ->get();

        return $groups;
    }


    private function searchPosts($query_string)
    {   // this also includes de tsvectors of comments
        // FUNCTION CALLED IN SEARCH

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
            ->join('like_post', 'like_post.id_post', '=', 'post.id')
            ->groupBy('post.id', 'owner', 'user.photo', 'comments_count', 'comment.tsvector_comment')
            ->selectRaw('
            post.id, post.text, post_date, username as owner, id_poster, username, photo,
            comments_count,
            count(like_post.id_user) as likes_count,
            ts_rank((post.tsvectors || tsvector_comment)::tsvector, plainto_tsquery(\'english\', ?)) as ranking', [$query_string])
            ->orderBy('ranking', 'desc')
            ->limit(20)
            ->get();

        foreach ($posts as $post) {
            $post->images = Image::select('path')->where('id_post', $post->id)->get();
            $post->hasLiked = false;
            $post->isOwner = false;
            $post->auth = 0;

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


    private function searchTopics($query_string)
    {
        // FUNCTION CALLED IN SEARCH

        $topics = Topic::where('topic', 'LIKE', '%' . $query_string . '%')
            ->limit(30)
            ->get();

        return $topics;
    }
}
