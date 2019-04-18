<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
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

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $table = 'users';

    //gravatar头像和侧边栏
    public function gravatar($size = '100')
    {
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }

    //生成 随机激活码
    public static function boot()
    {
        parent::boot();
        static::creating(function($user) {
            $user->activation_token = str_random(30);
        });
    }

    // 指明一对多个微博
    public function statuses()
    {
        return $this->hasMany(Status::class);
    }

    // 获取当前用户发布的微博信息
    public function feed()
    {
        return $this->statuses()->orderBy('created_at', 'desc');
    }

    // 一个用户的多个粉丝
    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id');
    }

    // 一个用户的多个关注
    public function followings()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id');
    }

    // 关注方法
    public function follow($user_ids)
    {
        if(!is_array($user_ids)) {
            $user_ids = compact('user_ids');
        }
        $this->followers()->sync($user_ids, false);
    }

    // 取消关注
    public function unfollow($user_ids)
    {
        if( !is_array($user_ids)) {
            $user_ids = compact('user_ids');
        }
        $this->followings()->detach($user_ids);
    }

    // 判断当前用户是否 关注了user_id
    public function isFollowign($user_id)
    {
        return $this->followings->contains($user_id);
    }



}
