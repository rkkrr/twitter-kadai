<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserFavouritesController extends Controller
{
    public function store(Request $request, $id)
    {
        \Auth::user()->favourites($id);
        return redirect()->back();
    }//POST

    public function destroy($id)
    {
        \Auth::user()->unfavourites($id);
        return redirect()->back();
    }//DELETE
    
    public function favourites($id)
    {
        $user = User::find($id);
        $favourites = $user->favouritings()->paginate(10);

        $data = [
            'user' => $user,
            'users' => $favouritings,
        ];

        $data += $this->counts($user);
        return view('users.favourites', $data);
    
    }//GET $userなのは、ぺージを表示したときに、タイムラインフォロー数フォロワー数すべて数が表示されるから、全部必要

    }