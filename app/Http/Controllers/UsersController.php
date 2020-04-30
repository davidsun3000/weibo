<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Mail;

class UsersController extends Controller
{
    // DS
    public function __construct() {
        $this->middleware('auth', [
            'except' => ['show', 'create', 'store', 'index', 'confirmEmail']
        ]);
    }

    public function create() {
        return view('users.create');
    }

    public function show(User $user) {
        return view('users.show', compact('user'));
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
        $this->sendEmailConfirmationTo($user);

        // Auth::login($user);
        // session()->flash('success', '欢迎, 您将在这里开启一段新的旅程~');
        session()->flash('success', '验证邮件已经发生到你的注册邮箱, 请注意查收.');
        return redirect()->route('home');
    }

    protected function sendEmailConfirmationTo($user) {
        $view = 'emails.confirm';
        $data = compact('user');
        #$from = 'david@weibo.com';
        $name = 'david';
        $to = $user->email;
        $subject = "感谢注册 Weibo 应用！请确认你的邮箱.";
        
        Mail::send($view, $data, function ($message)  use ($from, $name, $to, $subject) {
            $message->from($from, $name)->to($to)->subject($subject);

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
        session()->flash('success', '恭喜你，激活成功！');
        return redirect()->route('users.show', [$user]);
    }
}
