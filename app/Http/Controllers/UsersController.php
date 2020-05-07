<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Mail;
use Log;

class UsersController extends Controller
{
    // DS
    public function __construct() {
        $this->middleware('auth', [
            'except' => ['show', 'create', 'store', 'index', 'confirmEmail']
        ]);
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    public function create() {
        return view('users.create');
    }

    public function update(User $user, Request $request) {
        $this->authorize('update', $user);
        $this->validate($request, [
            'name' => 'required|max:50',
            'password' => 'nullable|confirmed|min:6',
        ]);

        $data = [];
        $data['name'] = $request->name;
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);

        session()->flash('success', '个人资料更新成功！');

        return redirect()->route('users.show', $user->id);
    }

    public function show(User $user) {
        return view('users.show', compact('user'));
    }

    public function edit(User $user) {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    public function store(Request $request) {
        $this->validate($request, [
            'name' => 'required|unique:users|max:50',
            'email' =>'required|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // send confirm email
        Log::debug('Debug:', ['store function']);
        $this->sendEmailConfirmationTo($user);

        // Auth::login($user);
        // session()->flash('success', '欢迎, 您将在这里开启一段新的旅程~');
        session()->flash('success', '验证邮件已经发生到你的注册邮箱, 请注意查收.');
        return redirect()->route('home');
    }

    protected function sendEmailConfirmationTo($user) {
        $view = 'emails.confirm';
        $data = compact('user');
        // $from = 'david@weibo.com';
        // $from = env('MAIL_USERNAME', '');
        // $name = 'david';
        $to = $user->email;
        $subject = "感谢注册 Weibo 应用！请确认你的邮箱.";

        Log::info('Send confirmation email: ');
        // Log::info('From: ', [$from]);
        Log::info('To: ', [$to]);
        
        Mail::send($view, $data, function ($message)  use ($to, $subject) {
            Log::debug('Debug:', ['sendEmailConfirmationTo']);
            $message->to($to)->subject($subject);

            // $message->from('john@johndoe.com', 'John Doe');
            // $message->sender('john@johndoe.com', 'John Doe');
            // $message->to('john@johndoe.com', 'John Doe');
            // $message->cc('john@johndoe.com', 'John Doe');
            // $message->bcc('john@johndoe.com', 'John Doe');
            // $message->replyTo('john@johndoe.com', 'John Doe');
            // $message->subject('Subject');
            // $message->priority(3);
            // $message->attach('pathToFile');
        });
    }

    public function confirmEmail($token) {
        $user = User::where('activation_token', $token)->firstOrFail();

        $user->activated = true;
        $user->activation_token = null;
        $user->save();

        Auth::login($user);
        Log::info('用户登录成功:', [$user->email]);
        session()->flash('success', '恭喜你，激活成功！');
        return redirect()->route('users.show', [$user]);
    }
}
