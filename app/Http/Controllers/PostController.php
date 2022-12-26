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

        // TODO: use id to get post from database
        $post = Post::withCount('likes', 'comments')->find($id);

        if ($post == null)
            return view('pages.404');

        // POLICY
        if (!$post->owner->visibility) {
            $this->authorize('view', $post);
        }
        return view('pages.post', ['post' => $post]);
    }

    public function feed(Request $request)
    {

        $request->validate([
            'type_feed' => 'sometimes|string|required',
            'type_order' => 'string'
        ]);

        $posts = [];
        $offset = $request->route('offset');

        // TODO ... PROVAVELMENTE ESTAS QUERIES ESTÃO A TORNAR O RETRIVAL DOS POSTS MAIS LENTO ... mudar

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

        if ($request->route('type_order') === "popularity") {
            $posts = DB::table(DB::raw("({$posts->toSql()}) as sub"))
                ->mergeBindings($posts->getQuery()) // you need to get underlying Query Builder
                ->selectRaw(' *, (likes_count /EXTRACT(epoch FROM (CURRENT_DATE - post_date))) as ranking')
                ->orderBy('ranking', 'desc');
        } else if ($request->route('type_order') === "date") {
            $posts = $posts->orderBy('post_date', 'desc');
        } else if ($request->route('type_order') === "likes") {
            $posts = $posts->orderBy('likes_count', 'desc');
        }

        // TODO ... lembro-me que em ltw meti o offset e o limit enquanto fazia o order é capaz de ajudar
        $posts = $posts->skip($offset)->limit(5)->get();


        // TODO: pass the current log in user to js in order to know if the post is theirs or not

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

        return json_encode($posts);
    }


    public function create(Request $request)
    {

        $request->validate([
            'group_name' => 'sometimes|string',
            'text' => 'string|min:0|max:1000',
            'tags' => 'string',
            'photos.*' => 'image|mimes:jpg,jpeg,png,ico|min:1|max:50000' // 50MB per image
        ]);

        DB::beginTransaction();
        $post = new Post();

        if ($request->input('group_name') != null) {
            $post->id_group = Group::where('name', $request->input('group_name'))->first()->id;
        }

        $this->authorize('create', $post); // POLICY

        $post->text = $request->input('text');
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

            $topics = explode(' ', $request->input('tags'));

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
            'tags' => 'string',
            'photos.*' => 'image|mimes:jpg,jpeg,png,ico|min:1|max:50000' // 50MB per image
        ]);

        DB::beginTransaction();

        $post = Post::find($id);

        $this->authorize('update', $post);

        $post->text = $request->input('text'); // POLICY

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

            $topics = explode(' ', $request->input('tags'));

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

    private function feed_friends()
    { // NOT AN API

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

    private function feed_groups()
    { // NOT AN API
        if (!Auth::check()) { // Authorization
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

    private function feed_viral()
    { // THIS IS A FUNCTION ... no need for POLICY ... Its a guest functionality
        $posts = Post::join('user', 'user.id', '=', 'post.id_poster')
            ->where('visibility', true)
            ->select('post.id', 'post.text', 'post_date', 'username as owner', 'photo')
            ->withCount('likes', 'comments');

        return $posts;
    }

    private function feed_for_you()
    {// NOT AN API
        if (!Auth::check()) {
            return response()->json(['Please login' => 401]); // Authorization
        }

        $posts_groups = $this->feed_groups();

        $posts_friends = $this->feed_friends();

        $posts = $this->feed_viral()
            ->union($posts_groups)
            ->union($posts_friends)
            ->distinct();


        return $posts;
    }
}
