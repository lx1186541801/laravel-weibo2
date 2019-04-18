<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;
use Auth;
use Mail;

class UsersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', [
            'except'    =>  ['show', 'create', 'store', 'index', 'confirmEmail']
        ]);
        $this->middleware('guest', [
            'only'  => ['create']
        ]);
    }


    //显示用户列表
    public function index(Request $request)
    {
        $users = User::paginate(20);

//        if ($request->ajax()) {
//            $view = view('users._user_info',compact(['users']))->render();
//            return response()->json(['html'=>$view]);
//        }

        return view('users.index', compact(['users']));
    }

    // 注册
    public function create()
    {
        return view('users.create');
    }

    // 显示个人页面
    public function show(User $user)
    {
        $statuses = $user->statuses()->orderBy('created_at', 'desc')->paginate(10);

        return view('users.show', compact('user', 'statuses'));
    }

    public function store(Request $request)
    {
        $rule = [
            'name'  => 'required|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password'  => 'required|confirmed|min:6'
        ];
        $this->validate($request, $rule);

        $user = User::create([
            'name'  => $request->name,
            'email'  => $request->email,
            'password'  => bcrypt($request->password)
        ]);
        $this->sendEmailConfirmationTo($user);
        session()->flash('success', "验证邮件已发送到你的注册邮箱上，请注意查收!");
        return redirect('/');
    }

    // 显示修改页面
    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    // 修改动作
    public function update(User $user, Request $request)
    {
        $this->authorize('update', $user);
        $this->validate($request, [
            'name' => 'required|max:50',
            'password' => 'nullable|confirmed|min:6'
        ]);


        $data = [];
        $data['name'] = $request->name;
        if($request->password) {
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);

        session()->flash('success', '个人资料更新存在！');
        return redirect()->route('users.show', [$user]);
    }

    //删除用户
    public function destroy(User $user)
    {
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success', '成功删除用户！');
        return back();
    }

    //发送邮件
    protected function sendEmailConfirmationTo($user)
    {
        $view = 'emails.confirm';
        $data = compact('user');
        $to = $user->email;
        $subject = "感谢注册 WEIBO App 应用！ 请确认你的邮箱";

        Mail::send($view, $data, function($message) use ($to, $subject) {
            $message->to($to)->subject($subject);
        });
    }

    // 激活功能
    public function confirmEmail($token)
    {
        $user = User::where('activation_token', $token)->firstOrFail();
        $user->activated = true;
        $user->activation_token = null;
        $user->save();

        Auth::login($user);
        session()->flash('success', '激活成功！');
        return redirect()->route('users.show', [$user]);
    }

    // 获取粉丝

    public function followers(User $user)
    {
        $users = $user->followers()->paginate(30);
        $title = $user->name . "的粉丝";
        return view('users.show_follow', compact('users', 'title'));
    }

    // 获取关注的人
    public function followings(User $user)
    {
        $users = $user->followings()->paginate(30);
        $title = $user->name . '的关注';
        return view('users.show_follow', compact('users','title'));
    }

}
