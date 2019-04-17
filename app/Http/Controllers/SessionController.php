<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SessionController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest', [
            'only'  => ['create']
        ]);
    }

    //登陆页面
    public function create()
    {
        return view('users.login');
    }

    //登陆动作
    public function store(Request $request)
    {
        $rules = [
            'email' => 'required|email|max:255',
            'password'  =>  'required'
        ];
        $credentials = $this->validate($request, $rules);
        if (Auth::attempt($credentials, $request->has('remember'))) {
            $fallback = route('users.show', [Auth::user()]);
            session()->flash('success', '欢迎回来！');
            return redirect()->intended($fallback);
        } else {
            session()->flash('danger', '很抱歉，您的邮箱和密码不匹配');
            return redirect()->back()->withInput();
        }
    }

    //退出动作
    public function destory()
    {
        Auth::logout();
        session()->flash('success', '退出成功!');
        return redirect('login');
    }
}
