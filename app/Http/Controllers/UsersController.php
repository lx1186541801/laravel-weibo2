<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class UsersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', [
            'except'    =>  ['show', 'create', 'store', 'index']
        ]);
        $this->middleware('guest', [
            'only'  => ['create']
        ]);
    }


    //显示用户列表
    public function index()
    {
        $users = User::paginate(10);
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
        return view('users.show', compact('user'));
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
        Auth::login($user);
        session()->flash('success', "注册成功!");
        return redirect()->route('users.show', [$user]);
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
        return redirect()->route('users.show', $user->id);
    }

    //删除用户
    public function destroy(User $user)
    {
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success', '成功删除用户！');
        return back();
    }

}
