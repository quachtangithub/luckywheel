<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

use App\Models\DanhSachGiaiThuong;

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

    public function updateWinner (Request $request) {
        $giaithuong_obj = DanhSachGiaiThuong::find($request->ma_giai_thuong);
        if ($giaithuong_obj !=  null) {
            $giaithuong_obj->ma_so_nhan_giai = $request->ma_so_nhan_giai;
            $giaithuong_obj->ten_nguoi_nhan_giai = 'HỒ QUỐC TUẤN';
            $giaithuong_obj->update();
            return view('frontend.congratulation')->with('giaithuong_obj', $giaithuong_obj)->render();
        }
    }
}
