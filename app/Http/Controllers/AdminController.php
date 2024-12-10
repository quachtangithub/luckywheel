<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

use App\Models\DanhSachGiaiThuong;
use App\Models\DanhSachNguoiDung;

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
            $ten_nguoi_nhan_giai = $giaithuong_obj->ten_nguoi_nhan_giai;
            if ($request->ma_so_nhan_giai != '' && $ten_nguoi_nhan_giai == '') {
                // neu có mã mà không có tên
                $dsnguoidung_obj = DanhSachNguoiDung::where('ma_nguoi_dung', $request->ma_so_nhan_giai)->first();
                if ($dsnguoidung_obj != null) {
                    $ten_nguoi_nhan_giai = $dsnguoidung_obj->ten_nguoi_dung;
                }
            } 
            $giaithuong_obj->ma_so_nhan_giai_thuc_te = $request->ma_so_nhan_giai;
            $giaithuong_obj->ten_nguoi_nhan_giai_thuc_te = $ten_nguoi_nhan_giai;
            $giaithuong_obj->da_nhan_giai = 1;
            $giaithuong_obj->update();
            return view('frontend.congratulation')->with('giaithuong_obj', $giaithuong_obj)->render();
        }
    }

    public function index () {
        $giaithuong_obj = DanhSachGiaiThuong::orderBy('so_thu_tu', 'asc')->get();
        return view('backend.index')->with('giaithuong_obj', $giaithuong_obj);
    }

    public function updatePrize (Request $request) {
        $request->validate([
            'noi_dung'=>'required',
            'so_thu_tu' => 'numeric|min:0'
        ], [
            'noi_dung.required' => 'Bắt buộc nhập tên giải',
            'so_thu_tu.numeric' => 'Số thứ tự phải là số',
            'so_thu_tu.min' => 'Số thứ tự phải lớn hơn 0'
        ]);

        if (($request->phan_loai_khach == 1 || $request->phan_loai_khach == 2 || $request->phan_loai_khach == 3) &&
            $request->ma_so_nhan_giai != '') {
            return redirect()->route('admin')->with('error', 'Nếu đã chọn phân loại khách thì không thể chỉ định cụ thể khách cho giải' . $request->noi_dung)
                ->with('ten_giai_thuong', $request->noi_dung);
        }

        if ($request->ma_giai_thuong == '') {
            // them moi
            $request_data = $request->except('ma_giai_thuong');
            $giaithuong_obj = DanhSachGiaiThuong::create($request_data);
            return redirect()->route('admin')->with('success', 'Thêm mới thành công ' . $giaithuong_obj->noi_dung)
                ->with('ten_giai_thuong', $request->noi_dung);
        } else {
            // cap nhat
            $giaithuong_obj = DanhSachGiaiThuong::find($request->ma_giai_thuong);
            $giaithuong_obj->noi_dung = $request->noi_dung;
            $giaithuong_obj->so_thu_tu = $request->so_thu_tu;
            $giaithuong_obj->ma_so_nhan_giai = $request->ma_so_nhan_giai;
            $giaithuong_obj->ten_nguoi_nhan_giai = $request->ten_nguoi_nhan_giai;
            $giaithuong_obj->ma_so_nhan_giai_thuc_te = $request->ma_so_nhan_giai_thuc_te;
            $giaithuong_obj->ten_nguoi_nhan_giai_thuc_te = $request->ten_nguoi_nhan_giai_thuc_te;
            $giaithuong_obj->da_nhan_giai = $request->da_nhan_giai;
            $giaithuong_obj->thoi_gian_cho = $request->thoi_gian_cho;
            $giaithuong_obj->save();
            return redirect()->route('admin')->with('success', 'Cập nhật thành công ' . $giaithuong_obj->noi_dung)
                ->with('ten_giai_thuong', $request->noi_dung);
        }
    }

    public function getConfigWinner(Request $request) {
        $giaithuong_obj = DanhSachGiaiThuong::find($request->mgt);
        if ($giaithuong_obj != null) {
            if ($giaithuong_obj->ma_so_nhan_giai != '') {
                return response()->json(['success'=> 'Lấy dữ liệu thành công', 'ma_so_nhan_giai' => $giaithuong_obj->ma_so_nhan_giai,
                    'thoi_gian_cho' => $giaithuong_obj->thoi_gian_cho]);
            } else {
                // nếu chưa cấu hình thì lấy ngẫu nhiên
                // lấy danh sách đã nhận giải
                $da_nhan_giai = DanhSachGiaiThuong::whereRaw('ma_so_nhan_giai_thuc_te is not null')->get();
                $da_nhan_giai_arr = [];
                foreach ($da_nhan_giai as $item) {
                    $da_nhan_giai_arr[] = $item->ma_so_nhan_giai_thuc_te;
                }
                $dsnguoidung_obj = DanhSachNguoiDung::whereNotIn('ma_nguoi_dung', $da_nhan_giai_arr)->inRandomOrder()->first();
                if ($dsnguoidung_obj != null) {
                    return response()->json(['success'=> 'Lấy dữ liệu thành công', 'ma_so_nhan_giai' => $dsnguoidung_obj->ma_nguoi_dung,
                        'thoi_gian_cho' => $giaithuong_obj->thoi_gian_cho]);
                } else {
                    return response()->json(['error'=> 'Lấy dữ liệu không thành công']);
                }
            }
        }
        return response()->json(['success'=> 'Lấy dữ liệu thành công', 'ma_so_nhan_giai' => '00000', 'thoi_gian_cho' => 5]);
    }

    public function user () {        
        $dsnguoidung_obj = DanhSachNguoiDung::orderBy('ma_nguoi_dung', 'asc')->get();
        return view('backend.user')->with('dsnguoidung_obj', $dsnguoidung_obj);
    }

    public function updateUser (Request $request) {
        $request->validate([
            'ma_nguoi_dung'=> 'required|unique:danh_sach_nguoi_dung,ma_nguoi_dung,' . $request->id_nguoi_dung . ',id_nguoi_dung',
            'ten_nguoi_dung' => 'required'
        ], [
            'ma_nguoi_dung.required' => 'Bắt buộc nhập mã',
            'ma_nguoi_dung.unique' => 'Mã này đã tồn tại',
            'ten_nguoi_dung.required' => 'Bắt buộc nhập tên'
        ]);

        if ($request->id_nguoi_dung == '') {
            // them moi
            $request_data = $request->except('id_nguoi_dung');
            $dsnguoidung_obj = DanhSachNguoiDung::create($request_data);
            return redirect()->route('user')->with('success', 'Thêm mới thành công ' . $dsnguoidung_obj->ma_nguoi_dung . ' - ' . $dsnguoidung_obj->ten_nguoi_dung);
        } else {
            // cap nhat
            $dsnguoidung_obj = DanhSachNguoiDung::find($request->id_nguoi_dung);
            $dsnguoidung_obj->ma_nguoi_dung = $request->ma_nguoi_dung;
            $dsnguoidung_obj->ten_nguoi_dung = $request->ten_nguoi_dung;
            $dsnguoidung_obj->save();
            return redirect()->route('user')->with('success', 'Cập nhật thành công ' . $dsnguoidung_obj->ma_nguoi_dung . ' - ' . $dsnguoidung_obj->ten_nguoi_dung);
        }
    }
}
