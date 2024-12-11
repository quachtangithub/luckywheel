<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Pusher\Pusher;

use App\Models\User;
use App\Models\DanhSachGiaiThuong;
use App\Models\DanhSachNguoiDung;
use App\Notifications\LuckyWheelNotification;

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
        $giaithuong_obj = DanhSachGiaiThuong::orderBy('so_thu_tu', 'asc')->paginate(5);
        return view('backend.index')->with('giaithuong_obj', $giaithuong_obj);
    }

    public function updatePrize (Request $request) {
        $request->validate([
            'noi_dung'=>'required',
            'so_thu_tu' => 'numeric|min:0',
            'ma_so_nhan_giai' => 'digits_between:0,9'
        ], [
            'noi_dung.required' => 'Bắt buộc nhập tên giải',
            'so_thu_tu.numeric' => 'Số thứ tự phải là số',
            'so_thu_tu.min' => 'Số thứ tự phải lớn hơn 0',
            'ma_so_nhan_giai.digits_between' => 'Mã số nhận giải phải từ 0 đến 9'
        ]);

        if (($request->phan_loai_khach == 1 || $request->phan_loai_khach == 2 || $request->phan_loai_khach == 3) &&
            $request->ma_so_nhan_giai != '') {
            return redirect()->route('admin')->with('error', 'Nếu đã chọn phân loại khách thì không thể chỉ định cụ thể khách cho giải' . $request->noi_dung)
                ->with('ten_giai_thuong', $request->noi_dung);
        }

        if ($request->phan_loai_khach == '' && $request->ma_so_nhan_giai == '') {
            return redirect()->route('admin')->with('error', 'Vui lòng nhập thông tin cụ thể khách sẽ nhận giải ' . $request->noi_dung)
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
            $giaithuong_obj->phan_loai_khach = $request->phan_loai_khach;
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
                // nếu có cấu hình người nhận giải cụ thể
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
                // if 
                $dsnguoidung_obj = DanhSachNguoiDung::whereNotIn('ma_nguoi_dung', $da_nhan_giai_arr);
                if ($giaithuong_obj->phan_loai_khach == 2) {
                    // nội bộ
                    $dsnguoidung_obj = $dsnguoidung_obj->where('loai_nguoi_dung', 1);
                } elseif ($giaithuong_obj->phan_loai_khach == 3) {
                    // khach moi ben ngoai
                    $dsnguoidung_obj = $dsnguoidung_obj->where('loai_nguoi_dung', 0);
                }
                $dsnguoidung_data = $dsnguoidung_obj->inRandomOrder()->first();
                if ($dsnguoidung_data != null) {
                    return response()->json(['success'=> 'Lấy dữ liệu thành công', 'ma_so_nhan_giai' => $dsnguoidung_data->ma_nguoi_dung,
                        'thoi_gian_cho' => $giaithuong_obj->thoi_gian_cho]);
                } else {
                    return response()->json(['error'=> 'Lấy dữ liệu không thành công']);
                }
            }
        }
        return response()->json(['success'=> 'Lấy dữ liệu thành công', 'ma_so_nhan_giai' => '00000', 'thoi_gian_cho' => 5]);
    }

    public function deleteUser ($id_nguoi_dung) {
        DanhSachNguoiDung::find($id_nguoi_dung)->delete();
        return response()->json(['success'=> 'Xóa người dùng thành công']);
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
            $dsnguoidung_obj->loai_nguoi_dung = $request->loai_nguoi_dung == 1 ? 1 : 0;
            $dsnguoidung_obj->save();
            return redirect()->route('user')->with('success', 'Cập nhật thành công ' . $dsnguoidung_obj->ma_nguoi_dung . ' - ' . $dsnguoidung_obj->ten_nguoi_dung);
        }
    }

    public function testnotification ($id) {
        // $user = User::find(1);
        $data = Array(
            'ma_giai_thuong' => $id
        );
        $options = array(
            'cluster' => 'ap1',
            'encrypted' => true
        );

        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );

        $pusher->trigger('NotificationEvent', 'send-message', $data);
    }

    public function getPrize ($id) {
        $ds_giaithuong_obj = DanhSachGiaiThuong::orderBy('so_thu_tu', 'asc')->get();
        $current_giaithuong_obj = DanhSachGiaiThuong::find($id);
        if ($current_giaithuong_obj == null) {
            $current_giaithuong_obj = DanhSachGiaiThuong::inRandomOrder()->first();
            $id = $current_giaithuong_obj->ma_giai_thuong;
        }
        return view('frontend.prize')->with('ds_giaithuong_obj', $ds_giaithuong_obj)->with('ma_giai_thuong', $id)
            ->with('current_giaithuong_obj', $current_giaithuong_obj);
    }

    public function updatePrizeInControl (Request $request) {
        $request->validate([
            'ma_giai_thuong'=>'required',
            'ma_so_nhan_giai' => 'digits_between:0,9'
        ], [
            'ma_giai_thuong.required' => 'Bắt buộc nhập mã giải thưởng',
            'ma_so_nhan_giai.digits_between' => 'Mã số nhận giải phải từ 0 đến 9'
        ]);

        if (($request->phan_loai_khach == 1 || $request->phan_loai_khach == 2 || $request->phan_loai_khach == 3) &&
            $request->ma_so_nhan_giai != '') {
            return response()->json(['error'=> 'Nếu đã chọn phân loại khách thì không thể chỉ định cụ thể khách cho giải', 'ma_giai_thuong' => $request->ma_giai_thuong]);
        }

        if ($request->phan_loai_khach == '' && $request->ma_so_nhan_giai == '') {
            return response()->json(['error'=> 'Vui lòng nhập thông tin cụ thể khách sẽ nhận giải', 'ma_giai_thuong' => $request->ma_giai_thuong]);
        }

        $giaithuong_obj = DanhSachGiaiThuong::find($request->ma_giai_thuong);
        $giaithuong_obj->ma_so_nhan_giai = $request->ma_so_nhan_giai;
        $giaithuong_obj->ten_nguoi_nhan_giai = $request->ten_nguoi_nhan_giai;
        $giaithuong_obj->phan_loai_khach = $request->phan_loai_khach;
        $giaithuong_obj->thoi_gian_cho = $request->thoi_gian_cho;
        $giaithuong_obj->save();

        $data = Array(
            'ma_giai_thuong' => $giaithuong_obj->ma_giai_thuong,
            'ten_giai_thuong' => $giaithuong_obj->noi_dung,
            'type' => 'begin'
        );
        $options = array(
            'cluster' => 'ap1',
            'encrypted' => true
        );

        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );

        $pusher->trigger('NotificationEvent', 'send-message', $data);
        return response()->json(['success'=> 'Tất cả sẵn sàng', 'ma_giai_thuong' => $request->ma_giai_thuong]);
    }

    public function playPrizeInControl ($id) {
        if ($id != '') {
            $data = Array(
                'ma_giai_thuong' => $id,
                'type' => 'end'
            );
            $options = array(
                'cluster' => 'ap1',
                'encrypted' => true
            );
    
            $pusher = new Pusher(
                env('PUSHER_APP_KEY'),
                env('PUSHER_APP_SECRET'),
                env('PUSHER_APP_ID'),
                $options
            );
    
            $pusher->trigger('NotificationEvent', 'send-message', $data);
            return response()->json(['success'=> 'Bắt đầu thành công']);
        }
        return response()->json(['error'=> 'Khởi động không thành công']);
    }
}
