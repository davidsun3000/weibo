<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Log;

class SessionController extends Controller
{
    // DS
    public function create() {
        return view('sessions.create');
    }

    public function store(Request $request) {
        $credentials = $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            // login
            if (Auth::user()->activated) {
                session()->flash('success', '欢迎回来!');
                Log::info('User login: ', [$request->email]);
                $fallback = route('users.show', Auth::user());
                return redirect()->intended($fallback);
            } else {
                session()->flash('danger', '您的账号还没有激活!');
                Log::info('User login(Not activated): ', [$request->email]);
                return redirect()->back()->withInput();
            }
        } else {
            // fail
            Auth::logout();
            Log::info('User logout: ', [$request->email]);
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
