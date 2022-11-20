<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Group;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function show($query)
    {
        // TODO: get query from database
        return view('pages.search');
    }


    public function search(Request $request)
    {

        $query_string = $request->route('query_string');
        $type_search = $request->route('type_search');

        $searchItems = [];

        if ($type_search === "users") {

            $searchItems = $this->searchUsers($query_string);
        } else if ($type_search === "groups") {

            $searchItems = $this->searchGroups($query_string);
        } else if ($type_search === "posts") {
            $searchItems = $this->searchPosts($query_string);
        }

        return json_encode($searchItems);
    }


    private function searchUsers($query_string)
    {

        $users = User::whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?)', [$query_string])
            ->selectRaw('*, ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) as ranking', [$query_string])
            ->orderBy('ranking', 'desc')
            ->get();

        return $users;
    }


    private function searchGroups($query_string)
    {

        $groups = Group::whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?)', [$query_string])
            ->selectRaw('*, ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) as ranking', [$query_string])
            ->orderBy('ranking', 'desc')
            ->get();

        return $groups;
    }


    private function searchPosts($query_string)
    { // this also includes de tsvectors of comments


        $comments = Comment::selectRaw('id_post, count(comment.id) as comment_count, tsvector_agg(tsvectors) as tsvector_comment')
            ->groupBy('id_post');

        $posts = DB::table(DB::raw("({$comments->toSql()}) as comment"))
            ->mergeBindings($comments->getQuery())
            ->join('post', 'post.id', '=', 'id_post')
            ->whereRaw('(post.tsvectors || tsvector_comment) @@ plainto_tsquery(\'english\', ?)', [$query_string])
            ->join('user', 'user.id', '=', 'post.id_poster')
            ->join('like_post', 'like_post.id_post', '=', 'post.id')
            ->groupBy('post.id', 'owner', 'user.photo', 'comment.tsvector_comment')
            ->selectRaw('
            post.id, post.text, post_date, username as owner, id_poster, username, photo,
            count(like_post.id_user) as likes_count,
            ts_rank((post.tsvectors || tsvector_comment)::tsvector, plainto_tsquery(\'english\', ?)) as ranking', [$query_string])
            ->orderBy('ranking', 'desc')
            ->get();

        return $posts;
    }
}
