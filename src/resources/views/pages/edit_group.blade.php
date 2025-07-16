@extends('layouts.app')

@section('page_title', 'Edit Profile')

@section('content')

    @if (Auth::check())
        <div style="max-width: 30em;margin:auto;margin-top:2em;" id="group_edit_page">
            <form style="width:100%;" method='post' action={{ route('editGroup', $group->name) }}
                enctype="multipart/form-data">
                {{ csrf_field() }}
                <!-- Start popup body -->
                <div class="d-flex justify-content-between align-items-top">
                    <h3 class="h3 mb-3 font-weight-normal">Edit Group</h3>
                </div>

                <label for="group_name" class="">Group Name</label>
                <input type="text" id="group_name" value="{{ $group->name }}" class="form-control mb-3"
                    placeholder="Group Name">

                <label for="group_description" class="">Description</label>
                <textarea rows="8" id="group_description" class="form-control mb-3" placeholder="Group description"
                    data-name="{{ $group->name }}" data-id="{{ $group->id }}" style="resize: none;">{{ $group->description }}</textarea>


                <?php
                $topics = [];
                for ($i = 0; $i < sizeof($group->topics); $i++) {
                    array_push($topics, $group->topics[$i]->topic->topic);
                }
                ?>

                <label for="group_edit_tags" class="" data-toggle="tooltip" data-placement="top"
                    title="You can have up to 3 tags to share what the group is about. This tags are visible in the group page">Tags</label>
                <input type="text" id="group_edit_tags" class="form-control mb-3" name="tags"
                    @if (sizeof($topics) == 3) disabled value="Max of 3 tags" @else placeholder="Add up to 3 group tags" @endif>

                <div id="group_edit_tags_container" class="mb-2">
                    <!-- TAGS INSERTED HERE WITH JS -->
                    @foreach ($topics as $topic)
                        <span id="bubble_tag_item_{{ $topic }}" class="badge bg-light me-2 p-2 mb-2 text-dark"
                            style="font-size:1.25em">{{ $topic }}
                            <a href="#" onclick="removeBubbleTag('#group_edit_tags','{{ $topic }}')">
                                <i class="fa-solid fa-circle-xmark ms-2 text-danger"></i>
                            </a>
                        </span>
                    @endforeach
                </div>

                <label for="group_photo" class="">Profile Picture</label>
                <input type="file" class="form-control" id="group_photo" name="photo">

                <div class="mt-2">
                    <label for="group_visibility" data-toggle="tooltip" data-placement="top"
                        title="Visibility influences who can view your content. If unchecked, the group is considered private and it's posts can only be seen by members. Otherwise, the content is available to everyone. Even if the group is marked as private, it can still be discovered in search and receive new join requests.">
                        Group Public Visibility</label>
                    <input class="ms-2 form-check-input" id="group_visibility" type="checkbox" role="switch"
                        id="flexSwitchCheckChecked" @if ($group->visibility) checked @endif>
                </div>

                <button class="btn btn-lg btn-primary mt-4 w-100" id="edit_group_button" type="submit">Save
                    Changes</button>

                <button class="btn btn-lg btn-outline-danger mt-4 w-100" id="delete_group_button" type="submit">Delete
                    Group</button>
                <!-- End popup body -->

            </form>
        </div>
    @endif


@endsection
