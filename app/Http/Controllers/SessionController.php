<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SessionController extends Controller
{
    // DS
    public function create() {
        return view('Sessions.create');
    }

    public function store(Request $request) {
        $credentials = $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            // login
            session()->flash('success', '欢迎回来!');
            return redirect()->route('users.show', [Auth::user()]);
        } else {
            // fail
            session()->flash('danger', '您的邮箱和密码不匹配');
            return redirect()->back()->withInput();
        }
        return;
    }

    public function destroy() {
        Auth::logout();
        session()->flash('sucess', '您已经成功退出系统');
        return redirect('login');
    }
}
