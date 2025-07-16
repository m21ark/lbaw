<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Group;
use App\Models\Image;
use App\Models\User;
use App\Models\Like;
use App\Models\PostTopic;
use App\Models\Topic;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;


class PostController extends Controller
{

    public static function areFriends(User $user1, User $user2)
    {   // THIS IS A FUNCTION ... IT DOES NOT NEED A POLICY
        return DB::table('friend_request')
            ->where('id_user_sender', $user1->id)
            ->where('id_user_receiver', $user2->id)->where('acceptance_status', 'Accepted')->exists() ||
            DB::table('friend_request')
            ->where('id_user_sender', $user2->id)
            ->where('id_user_receiver', $user1->id)->where('acceptance_status', 'Accepted')->exists();
    }

    public function show($id, Request $request)
    {
        $request->validate([
            'id' => 'integer|exists:post,id'
        ]);

        
        $post = Post::withCount('likes', 'comments')->find($id);

        if ($post == null)
            return abort('404');

        // POLICY
        if (!$post->owner->visibility) {
            $this->authorize('view', $post);
        }
        return view('pages.post', ['post' => $post]);
    }

    public function feed(Request $request)
    {

        $request->validate([
            'type_feed' => 'sometimes|string|required'
        ]);

        $posts = [];
        $offset = $request->route('offset');

        // We just need to check if the user can access for_you, friends, groups
        // In other words, the user has only to be authenticated, else 401 error is returned
        // Authorization inside fee_for_you ,friends and viral functions

        if ($request->route('type_feed') === "for_you") {
            $posts = $this->feed_for_you();
        } else if ($request->route('type_feed') === "friends") {
            $posts = $this->feed_friends();
        } else if ($request->route('type_feed') === "groups") {
            $posts = $this->feed_groups();
        } else if ($request->route('type_feed') === "viral") {
            $posts = $this->feed_viral();
        }


        $posts = DB::table(DB::raw("({$posts->toSql()}) as sub"))
            ->mergeBindings($posts->getQuery()) // you need to get underlying Query Builder
            ->distinct()
            ->selectRaw(' *, (likes_count /EXTRACT(epoch FROM (CURRENT_DATE - post_date))) as ranking')
            ->orderBy('ranking', 'desc');


        $posts = $posts->skip($offset)->limit(20)->get();


        foreach ($posts as $post) {
            $post->images = Image::select('path')->where('id_post', $post->id)->get();
            $post->topics = $this->post_topics($post->id);
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

        return json_encode($posts);
    }


    public function create(Request $request)
    {

        $request->validate([
            'group_name' => 'sometimes|string',
            'text' => 'string|min:0|max:1000',
            'photos.*' => 'image|mimes:jpg,jpeg,png,ico|min:0|max:50000' // 50MB per image
        ]);

        DB::beginTransaction();
        $post = new Post();

        if ($request->input('group_name') != null) {
            $post->id_group = Group::where('name', strip_tags($request->input('group_name')))->first()->id;
        }

        $this->authorize('create', $post); // POLICY

        $post->text = strip_tags($request->input('text'));
        $post->id_poster = Auth::user()->id;

        $post->save();

        $this->upload_img($request, $post);
        $this->add_topics($request, $post);

        DB::commit();

        return response()->json(['success' => 'Post created successfully.']);
    }

    private function add_topics(Request $request, Post $post)
    {   // THIS IS A FUNCTION ... no need for POLICY
        if ($request->input('tags') != null) {

            $topics = explode(' ', strip_tags($request->input('tags')));

            foreach ($topics as $topic) {

                $topic_ = Topic::where('topic', $topic)->first();
                if ($topic_ === null) {
                    $topic_ = new Topic();
                    $topic_->topic = $topic;
                    $topic_->save();
                }

                $post_topic = new PostTopic();
                $post_topic->id_post = $post->id;
                $post_topic->id_topic = $topic_->id;
                $post_topic->save();
            }
        }
    }

    public function upload_img(Request $request, Post $post)
    { // THIS IS A FUNCTION ... no need for POLICY
        if ($request->hasFile('photos')) {
            $i = 0;
            foreach ($request->photos as $imagefile) {
                $image = new Image;
                $path = 'image/img' . $post->id . '_' . $i . '.jpg';
                $image->path = $path;
                $image->id_post = $post->id;
                $image->save();
                try {
                    $imagefile->move(public_path('image/'), 'img' . $post->id . '_' . $i . '.jpg');
                } catch (Exception $e) {
                    DB::rollBack();
                }
                $i++;
            }
        }
    }

    public function delete($id, Request $request)
    {
        $request->validate([
            'id' => 'integer|exists:post,id'
        ]);

        $post = Post::find($id);
        $this->authorize('delete', $post); // POLICY
        DB::table('post')->where('id', $id)->delete();
        return $post;
    }

    public function edit(Request $request, $id)
    {

        $request->validate([
            'id' => 'integer|exists:post,id',
            'group_name' => 'sometimes|string',
            'text' => 'string|min:0|max:1000',
            'photos.*' => 'image|mimes:jpg,jpeg,png,ico|min:1|max:50000' // 50MB per image
        ]);

        DB::beginTransaction();

        $post = Post::find($id);

        $this->authorize('update', $post); // POLICY


        $post->text = strip_tags($request->input('text'));

        File::delete($post->images->pluck('path')->toArray());
        $post->images()->delete();
        $post->save();

        $this->upload_img($request, $post);
        $this->edit_topics($request, $post);

        DB::commit();
    }

    private function edit_topics(Request $request, Post $post)
    { // THIS IS A FUNCTION ... no need for POLICY
        $post->topics()->delete();
        if ($request->input('tags') != null) {

            $topics = explode(' ', strip_tags($request->input('tags')));

            foreach ($topics as $topic) {

                $topic_ = Topic::where('topic', $topic)->first();

                if ($topic_ === null) {
                    $topic_ = new Topic();
                    $topic_->topic = $topic;
                    $topic_->save();
                }

                $post_topic = new PostTopic();
                $post_topic->id_post = $post->id;
                $post_topic->id_topic = $topic_->id;
                $post_topic->save();
            }
        }
    }

    public function feed_friends()
    {    // NOT AN API

        if (!Auth::check()) { // Authorization
            return response()->json(['Please login' => 401]);
        }

        $posts = Post::join('user', 'user.id', '=', 'post.id_poster')
            ->with('images')
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

    public function feed_groups()
    { // NOT AN API
        if (!Auth::check()) {
            return response()->json(['Please login' => 401]);
        }

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

    public function feed_viral()
    { // NOT AN API
        $posts = Post::join('user', 'user.id', '=', 'post.id_poster')
            ->where('visibility', true)
            ->select('post.id', 'post.text', 'post_date', 'username as owner', 'photo')
            ->withCount('likes', 'comments');

        return $posts;
    }

    public function feed_for_you()
    { // NOT AN API
        if (!Auth::check()) {
            return response()->json(['Please login' => 401]); // Authorization
        }

        $posts_groups = $this->feed_groups();

        $posts_friends = $this->feed_friends();

        $posts = $this->feed_viral()
            ->union($posts_groups)
            ->union($posts_friends);


        return $posts;
    }

    public function post_topics($post_id)
    {
        $topics = PostTopic::where('id_post', $post_id)
            ->join('topic', 'topic.id', '=', 'post_topic.id_topic')
            ->select('topic.topic')
            ->get();
        return $topics;
    }
}
