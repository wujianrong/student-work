<?php

namespace App\Http\Controllers\Api;

use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Auth;

class BaseController extends Controller
{
    use Helpers;

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        //用户组（在 Laravel5.3 中对于多组用户有更加完善的支持，可以有多组用户系统，比方说前台、后台各有一组用户系统。）
        return Auth::guard('web');
    }

    protected function validatePermission($permissions)
    {
        //判断web用户组有没有该用户登陆session
        if (!$this->guard()->check() || !$this->guard()->user()->may($permissions)) {
            throw new AuthorizationException();
        }
        return true;
    }
}