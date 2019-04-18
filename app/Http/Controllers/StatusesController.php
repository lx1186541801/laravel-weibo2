<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
class StatusesController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }

    //创建动作
    public function  store(Request $request)
    {
        $this->validate($request, [
            'content'   => 'required|max:250',
        ]);
        Auth::user()->statuses()->create([
            'content' => $request['content']
        ]);
        session()->flash('success', '发布成功！');
        return redirect()->back();
    }
}
