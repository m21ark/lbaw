<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Group;
use App\Models\User;
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


    public function search(Request $request) {

        $query_string = $request->route('query_string');
        $type_search = $request->route('type_search');
        
        $searchItems = [];

        if ($type_search === "users") {

            $searchItems = $this->searchUsers($query_string);

        } else if ($type_search === "groups") {

            $searchItems = $this->searchGroups($query_string);

        } else if ($type_search === "posts") {


        }
        
        return json_encode($searchItems);
    }
    

    private function searchUsers($query_string) {

        $users = User::
        whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?)', [$query_string])
        ->selectRaw('*, ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) as ranking', [$query_string])
        ->orderBy('ranking', 'desc')
        ->get();

        return $users;
    }


    private function searchGroups($query_string) {

        $groups = Group::
        whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?)', [$query_string])
        ->selectRaw('*, ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) as ranking', [$query_string])
        ->orderBy('ranking', 'desc')
        ->get();

        return $groups;
    }

}
