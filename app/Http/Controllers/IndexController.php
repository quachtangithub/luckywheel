<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\DanhSachGiaiThuong;
use Session;

class IndexController extends Controller
{
    public function index () {
        $secret_value = Session::get('secret_value');
        return view('frontend.index')->with('secret_value', $secret_value);
    }

    public function frameContainer () {
        $user_id = Auth::user()->id;
        $danhsachgiaithuong = DanhSachGiaiThuong::where('user_id', $user_id)->where('trang_thai', 1)
            ->orderBy('da_nhan_giai', 'asc')->orderBy('so_thu_tu', 'asc')->get();
        return view('frontend.frame_container')->with('danhsachgiaithuong', $danhsachgiaithuong)->render();
    }
}
