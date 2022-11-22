<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{

    public static function areFriends(User $user1, User $user2)
    {
        return DB::table('friend_request')
            ->where('id_user_sender', $user1->id)
            ->where('id_user_receiver', $user2->id)->where('acceptance_status', 'Accepted')->exists() ||
            DB::table('friend_request')
            ->where('id_user_sender', $user2->id)
            ->where('id_user_receiver', $user1->id)->where('acceptance_status', 'Accepted')->exists();
    }

    public function show($id)
    {
        // TODO: use id to get post from database
        $post   = Post::withCount('likes', 'comments')->find($id);

        if($post == null)
            return view('pages.not_found');

        // policy, nr_comments_post
        if (!$post->owner->visibility) {
            $this->authorize('view', $post);
        }
        return view('pages.post', ['post' => $post]);
    }

    public function feed(Request $request)
    {
        $posts = [];

        if ($request->route('type_feed') === "for_you") {
            //$this->authorize('feed', $posts);
            $posts = $this->feed_for_you()->limit(20)->get();
        } else if ($request->route('type_feed') === "friends") {
            //$this->authorize('feed', $posts);
            $posts = $this->feed_friends()->limit(20)->get();
        } else if ($request->route('type_feed') === "groups") {
            //$this->authorize('feed', $posts);
            $posts = $this->feed_groups()->limit(20)->get();
        } else if ($request->route('type_feed') === "viral") {
            $posts = $this->feed_viral()->limit(20)->get();
        }

        return json_encode($posts);
    }


    public function create(Request $request)
    {

        // TODO: Não dá para criar posts se for owner e n percebo pq :(

        $post = new Post();

        if ($request->input('group_name') != null) {
            $post->id_group = Group::where('name', $request->input('group_name'))->first()->id;
        }

        $this->authorize('create', $post);

        $post->text = $request->input('text');
        $post->id_poster = Auth::user()->id;

        // TODO : ADD IMAGES

        $post->save();
    }

    public function delete($id)
    {
        $post = Post::find($id);
        $this->authorize('delete', $post);
        DB::table('post')->where('id', $id)->delete();
        return $post;
    }

    public function edit($id, Request $request)
    {
        $post = Post::find($id);
        $this->authorize('edit', $post);
        $post->text = $request->input('text');
        $post->save();
        return $post;
    }

    private function feed_friends()
    {

        $posts = Post::join('user', 'user.id', '=', 'post.id_poster')
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
            ->select('post.id', 'post.text', 'post_date', 'username as owner', 'photo')
            ->withCount('likes', 'comments');

        return $posts;
    }

    private function feed_groups()
    {
        $posts = Post::whereIn('id_group', function ($query) {
            $id = Auth::user()->id;
            $query->select('id_group')
                ->from('group_join_request')
                ->where('id_user', $id)
                ->where('acceptance_status', 'Accepted');
        })
            ->join('user', 'user.id', '=', 'post.id_poster')
            ->select('post.id', 'post.text', 'post_date', 'username as owner', 'photo')
            ->withCount('likes', 'comments');

        return $posts;
    }

    private function feed_viral()
    {
        $posts_filtered = Post::join('user', 'user.id', '=', 'post.id_poster')
            ->join('like_post', 'like_post.id_post', '=', 'post.id')
            ->where('visibility', true)
            ->select('post.id', 'post.text', 'post_date', 'username as owner', 'photo', DB::raw('count(like_post.id_user) as likes_count'))
            ->withCount('comments')
            ->groupBy('post.id', 'user.id');

        $posts = DB::table(DB::raw("({$posts_filtered->toSql()}) as sub"))
            ->mergeBindings($posts_filtered->getQuery()) // you need to get underlying Query Builder
            ->selectRaw(' *, (likes_count /EXTRACT(epoch FROM (CURRENT_DATE - post_date))) as ranking')
            ->orderBy('ranking', 'desc');

        return $posts;
    }

    private function feed_for_you()
    {

        $posts_filtered_groups = $this->feed_groups();
        $posts_groups = DB::table(DB::raw("({$posts_filtered_groups->toSql()}) as sub"))
            ->mergeBindings($posts_filtered_groups->getQuery()) // you need to get underlying Query Builder
            ->selectRaw(' *, (likes_count /EXTRACT(epoch FROM (CURRENT_DATE - post_date))) as ranking');

        $posts_filtered_friends = $this->feed_friends();
        $posts_friends = DB::table(DB::raw("({$posts_filtered_friends->toSql()}) as sub"))
            ->mergeBindings($posts_filtered_friends->getQuery()) // you need to get underlying Query Builder
            ->selectRaw(' *, (likes_count /EXTRACT(epoch FROM (CURRENT_DATE - post_date))) as ranking');

        $posts = $this->feed_viral()
            ->union($posts_groups)
            ->union($posts_friends)
            ->distinct()
            ->orderBy('ranking', 'desc');

        return $posts;
    }
}
