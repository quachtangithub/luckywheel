<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DanhSachGiaiThuong;

class IndexController extends Controller
{
    public function index () {
        $danhsachgiaithuong = DanhSachGiaiThuong::orderBy('so_thu_tu', 'asc')->get();
        return view('frontend.index')->with('danhsachgiaithuong', $danhsachgiaithuong);
    }
}
