<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DanhSachGiaiThuong;

class IndexController extends Controller
{
    public function index () {
        return view('frontend.index');
    }

    public function frameContainer () {
        $danhsachgiaithuong = DanhSachGiaiThuong::orderBy('da_nhan_giai', 'asc')->orderBy('so_thu_tu', 'asc')->get();
        return view('frontend.frame_container')->with('danhsachgiaithuong', $danhsachgiaithuong)->render();
    }
}
