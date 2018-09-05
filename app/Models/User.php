<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword;

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
    protected $hidden = ['password', 'remember_token'];

    public function gravatar($size='100')
    {
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";

        // 为 gravatar 方法传递的参数 size 指定了默认值 100；
// 通过 $this->attributes['email'] 获取到用户的邮箱；
// 使用 trim 方法剔除邮箱的前后空白内容；
// 用 strtolower 方法将邮箱转换为小写；
// 将小写的邮箱使用 md5 方法进行转码；
// 将转码后的邮箱与链接、尺寸拼接成完整的 URL 并返回；
    }


    public static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->activation_token = str_random(30);
        });
    }


    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }
    public function statuses()
    {
        return $this->hasMany(Status::class);
    }

    public function feed()
    {
        return $this->statuses()->orderBy('created_at', 'desc');
    }
}
