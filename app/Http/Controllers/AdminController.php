<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class AdminController extends Controller
{
    public function login () {
        return view('backend.login');
    }

    public function loginAdmin (Request $request) {
        $request->validate([
            'email'=>'required',
            'password' => 'required'
        ], [
            'email.required' => 'Nhập tên đăng nhập',
            'password.required' => 'Nhập mật khẩu'
        ]);
        
        $login = [
            'email' => $request->email,
            'password' => $request->password
        ];
        if (Auth::attempt($login)) {
            return redirect('admin');
        } else {
            return redirect()->back()->with('status', 'Email hoặc Password không chính xác');
        }
    }
}
