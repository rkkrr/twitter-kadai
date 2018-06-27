<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
        
    public function microposts()
    {
        return $this->hasMany(Micropost::class);
    }

   public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }
    
    public function follow($userId)
    {  
    // confirm if already following
    $exist = $this->is_following($userId);
    // confirming that it is not you
    $its_me = $this->id == $userId;

    if ($exist || $its_me) {
        // do nothing if already following
        return false;
    } else {
        // follow if not following
        $this->followings()->attach($userId);
        return true;
    }
    }

public function unfollow($userId)
{
    // confirming if already following
    $exist = $this->is_following($userId);
    // confirming that it is not you
    $its_me = $this->id == $userId;


    if ($exist && !$its_me) {
        // stop following if following
        $this->followings()->detach($userId);
        return true;
    } else {
        // do nothing if not following
        return false;
    }
}
public function followings()
{
    return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    //多対多の関係を作りたい、ユーザーフォローテーブルにとんでユーザIDとフォローIDとをつなげてあげる
}

public function is_following($userId) {
    return $this->followings()->where('follow_id', $userId)->exists();
}

  public function feed_microposts()
    {
        $follow_user_ids = $this->followings()-> pluck('users.id')->toArray();
        $follow_user_ids[] = $this->id;
        return Micropost::whereIn('user_id', $follow_user_ids);
    }
    
public function favouritings()
{
    return $this->belongsToMany(Micropost::class, 'user_favourite', 'user_id', 'micropost_id')->withTimestamps();
}


public function favourites($micropostId)
{       

        // confirm if already favouriting
    $exist = $this->is_favouriting($micropostId);

    if ($exist) {
        // do nothing if already favouriting
        return print 'false';
    } else {
        // favourite if not favouriting
        $this->favouritings()->attach($micropostId);
        return true;
    }
}

public function unfavourites($micropostId)
{
      // confirming if already following
    $exist = $this->is_favouriting($micropostId);
   

    if ($exist) {
        // stop favouriting if favouriting
        $this->favouritings()->detach($micropostId);
        return true;
    } else {
        // do nothing if not following
        return false;
    }
}

           
 public function is_favouriting($micropostId) {
    return $this->favouritings()->where('micropost_id', $micropostId)->exists();
}      

}


// 
?>
