<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Pusher\Pusher;

use App\Models\User;
use App\Models\DanhSachGiaiThuong;
use App\Models\DanhSachNguoiDung;
use App\Notifications\LuckyWheelNotification;
use Session;
use Redirect;
use File;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet;
use App\Imports\UserImport;

class AdminController extends Controller
{
    public function login (Request $request) {        
        return view('backend.login')->with('redirect_url', $request->rdr);
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
            if ($request->redirect_url != '') {
                return redirect($request->redirect_url);
            }
            return redirect('admin');
        } else {
            return redirect()->back()->with('status', 'Email hoặc Password không chính xác');
        }
    }

    public function logout () {
        Auth::logout();
        return Redirect::to('/login');
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
            return redirect()->back()->with('error', 'Nếu đã chọn phân loại khách thì không thể chỉ định cụ thể khách cho giải ' . $request->noi_dung)
                ->with('ten_giai_thuong', $request->noi_dung)->with('ma_giai_thuong', $request->ma_giai_thuong);
        }

        if ($request->phan_loai_khach == '' && $request->ma_so_nhan_giai == '') {
            return redirect()->back()->with('error', 'Vui lòng nhập thông tin cụ thể khách sẽ nhận giải ' . $request->noi_dung)
                ->with('ten_giai_thuong', $request->noi_dung)->with('ma_giai_thuong', $request->ma_giai_thuong);
        }

        if ($request->ma_giai_thuong == '') {
            // them moi
            $request_data = $request->except('ma_giai_thuong');
            $request_data['user_id'] = Auth::user()->id;
            $giaithuong_obj = DanhSachGiaiThuong::create($request_data);
            return redirect()->back()->with('success', 'Thêm mới thành công ' . $giaithuong_obj->noi_dung)
                ->with('ten_giai_thuong', $request->noi_dung)->with('ma_giai_thuong', $request->ma_giai_thuong);
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
            $giaithuong_obj->user_id = Auth::user()->id;
            $giaithuong_obj->trang_thai = $request->trang_thai;
            $giaithuong_obj->save();
            return redirect()->back()->with('success', 'Cập nhật thành công ' . $giaithuong_obj->noi_dung)
                ->with('ten_giai_thuong', $request->noi_dung)->with('ma_giai_thuong', $request->ma_giai_thuong);
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
        $dsnguoidung_obj = DanhSachNguoiDung::orderBy('ma_nguoi_dung', 'asc')->paginate(12);
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
            return redirect()->back()->with('success', 'Thêm mới thành công ' . $dsnguoidung_obj->ma_nguoi_dung . ' - ' . $dsnguoidung_obj->ten_nguoi_dung)
                ->with('ma_nguoi_dung', $dsnguoidung_obj->ma_nguoi_dung);
        } else {
            // cap nhat
            $dsnguoidung_obj = DanhSachNguoiDung::find($request->id_nguoi_dung);
            $dsnguoidung_obj->ma_nguoi_dung = $request->ma_nguoi_dung;
            $dsnguoidung_obj->ten_nguoi_dung = $request->ten_nguoi_dung;
            $dsnguoidung_obj->chuc_danh = $request->chuc_danh;
            $dsnguoidung_obj->loai_nguoi_dung = $request->loai_nguoi_dung == 1 ? 1 : 0;
            $dsnguoidung_obj->save();
            return redirect()->back()->with('success', 'Cập nhật thành công ' . $dsnguoidung_obj->ma_nguoi_dung . ' - ' . $dsnguoidung_obj->ten_nguoi_dung)
                ->with('ma_nguoi_dung', $dsnguoidung_obj->ma_nguoi_dung);
        }
    }

    public function importUser (Request $request) {        
        $request->validate([
            'loai_nguoi_dung'=> 'required',
            'import_user_file' => 'required|mimes:xlsx'
        ], [
            'loai_nguoi_dung.required' => 'Vui lòng chọn loại người dùng',
            'import_user_file.required' => 'Vui lòng chọn tệp tin import',
            'import_user_file.mimes' => 'Vui lòng chọn đúng loại tệp tin'
        ]);
        $file_name = '';
        $ds_import = [];
        if($request->import_user_file) {
            $file_name = time() . 'import_user_file.'.$request->import_user_file->extension();  
            $request->import_user_file->move(public_path('importexcel'), $file_name);
        }
        if(File::exists(public_path('importexcel') . '/' . $file_name)) {
            $import = new UserImport();
            $excelData = Excel::toArray($import, public_path('importexcel') . '/' . $file_name);
            if (isset($excelData[0])) {   
                if ($request->delete_old_data == 1) {
                    DanhSachNguoiDung::where('loai_nguoi_dung', $request->loai_nguoi_dung)->delete();
                }           
                foreach ($excelData[0] as $excelItem) {
                    $request_data = Array(
                        'ma_nguoi_dung' => $excelItem['ma_nv'],
                        'ten_nguoi_dung' => $excelItem['ho_va_ten'],
                        'loai_nguoi_dung' => $request->loai_nguoi_dung,
                        'chuc_danh' => $excelItem['chuc_danh_cong_viec_vtvl']
                    );
                    $user_obj = DanhSachNguoiDung::create($request_data);
                    $ds_import[] = $user_obj->ma_nguoi_dung . ' - ' . $user_obj->ten_nguoi_dung . ' (' . $user_obj->chuc_danh . ')';
                }
            }
        }
        return redirect()->back()->with('success', 'Import thành công ' . count($ds_import) . ' người dùng');
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

    public function getPrize (Request $request) {
        $ds_giaithuong_obj = DanhSachGiaiThuong::orderBy('so_thu_tu', 'asc')->get();
        $ma_giai_thuong = $request->ma_giai_thuong;
        $current_giaithuong_obj = DanhSachGiaiThuong::find($ma_giai_thuong);
        $name_count = 0;
        if ($current_giaithuong_obj == null) {
            // $current_giaithuong_obj = DanhSachGiaiThuong::inRandomOrder()->first();
            $current_giaithuong_obj = new DanhSachGiaiThuong();
            $current_giaithuong_obj->ma_giai_thuong = 0;
            $current_giaithuong_obj->noi_dung = 'updating';
            $current_giaithuong_obj->phan_loai_khach = 1;
            $current_giaithuong_obj->thoi_gian_cho = 15;
            $ma_giai_thuong = 0;
            $name_count = DanhSachGiaiThuong::where('noi_dung', 'LIKE', '%GIẢI THƯỞNG BỔ SUNG%')->count();
        }
        $secret_value_admin = Session::get('secret_value_admin');
        return view('backend.prize')->with('ds_giaithuong_obj', $ds_giaithuong_obj)->with('ma_giai_thuong', $ma_giai_thuong)
            ->with('current_giaithuong_obj', $current_giaithuong_obj)->with('secret_value_admin', $secret_value_admin)
            ->with('name_count', $name_count);
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

        if ($request->ma_giai_thuong == 0 || $request->ma_giai_thuong == '') {
            // them moi
            $request_data = [];
            $request_data['noi_dung'] = $request->noi_dung;
            $request_data['so_thu_tu'] = $request->so_thu_tu;
            $request_data['ma_so_nhan_giai'] = $request->ma_so_nhan_giai;
            $request_data['ten_nguoi_nhan_giai'] = $request->ten_nguoi_nhan_giai;
            $request_data['phan_loai_khach'] = $request->phan_loai_khach;
            $request_data['thoi_gian_cho'] = $request->thoi_gian_cho;
            $request_data['user_id'] = Auth::user()->id;

            $giaithuong_themmoi_obj = DanhSachGiaiThuong::create($request_data);
            return response()->json(['success'=> 'Thêm mới giải thành công', 'ma_giai_thuong' => $giaithuong_themmoi_obj->ma_giai_thuong]);
        }

        $giaithuong_obj = DanhSachGiaiThuong::find($request->ma_giai_thuong);
        $giaithuong_obj->ma_so_nhan_giai = $request->ma_so_nhan_giai;
        $giaithuong_obj->ten_nguoi_nhan_giai = $request->ten_nguoi_nhan_giai;
        $giaithuong_obj->phan_loai_khach = $request->phan_loai_khach;
        $giaithuong_obj->thoi_gian_cho = $request->thoi_gian_cho;
        $giaithuong_obj->save();

        $secret_value_admin = $this->createSecretValueAdmin($request->secret_value_admin);

        $data = Array(
            'ma_giai_thuong' => $giaithuong_obj->ma_giai_thuong,
            'ten_giai_thuong' => $giaithuong_obj->noi_dung,
            'type' => 'begin',
            'secret_value_admin' => $secret_value_admin
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

    public function copyPrizeInControl (Request $request) {
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
        // them moi
        $secret_value_admin = $this->createSecretValueAdmin($request->secret_value_admin);
        $giai_thuong = DanhSachGiaiThuong::find($request->ma_giai_thuong);
        $ma_giai_thuong_goc = $giai_thuong->ma_giai_thuong_goc != '' ? $giai_thuong->ma_giai_thuong_goc : $giai_thuong->ma_giai_thuong;
        $giai_thuong_goc = DanhSachGiaiThuong::find($ma_giai_thuong_goc);
        if ($giai_thuong_goc != null) {
            $count_giaithuong = DanhSachGiaiThuong::where('ma_giai_thuong_goc', $ma_giai_thuong_goc)->count();
            $request_data = [];
            $request_data['ma_giai_thuong_goc'] = $ma_giai_thuong_goc;
            $request_data['noi_dung'] = $giai_thuong_goc->noi_dung . ' ' . ($count_giaithuong + 1);
            $request_data['so_thu_tu'] = $giai_thuong_goc->so_thu_tu;
            $request_data['ma_so_nhan_giai'] = '';
            $request_data['ten_nguoi_nhan_giai'] = '';
            $request_data['phan_loai_khach'] = $giai_thuong_goc->phan_loai_khach;
            $request_data['thoi_gian_cho'] = $giai_thuong_goc->thoi_gian_cho;
            $request_data['user_id'] =$giai_thuong_goc->user_id;
    
            $giaithuong_copy_obj = DanhSachGiaiThuong::create($request_data);            
    
            $data = Array(
                'ma_giai_thuong' => $giaithuong_copy_obj->ma_giai_thuong,
                'ten_giai_thuong' => $giaithuong_copy_obj->noi_dung,
                'type' => 'begin',
                'secret_value_admin' => $secret_value_admin
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
            return response()->json(['success'=> 'Tất cả sẵn sàng', 'ma_giai_thuong' => $giaithuong_copy_obj->ma_giai_thuong]);
        }
        return response()->json(['errors'=> 'Sao chép giải thưởng thất bại']);
    }

    public function createSecretValueAdmin ($secret_value_admin) {
        if(Session::has('secret_value_admin')) {
            Session::forget('secret_value_admin');
        }
        Session::put('secret_value_admin', $secret_value_admin);
        return $secret_value_admin;
    }

    public function playPrizeInControl (Request $request) {
        if ($request->id != '') {
            $secret_value_admin = $this->createSecretValueAdmin($request->secret_value_admin);
            $data = Array(
                'ma_giai_thuong' => $request->id,
                'type' => 'end',
                'secret_value_admin' => $secret_value_admin
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

    public function stopPrizeInControl (Request $request) {
        if ($request->id != '') {
            $secret_value_admin = $this->createSecretValueAdmin($request->secret_value_admin);
            $data = Array(
                'ma_giai_thuong' => $request->id,
                'type' => 'stop',
                'secret_value_admin' => $secret_value_admin
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
            return response()->json(['success'=> 'Dừng lại thành công']);
        }
        return response()->json(['error'=> 'Dừng lại không thành công']);
    }

    public function returnPrize (Request $request) {
        $secret_value_admin = $this->createSecretValueAdmin($request->secret_value_admin);
        $data = Array(
            'type' => 'returnprize',
            'secret_value_admin' => $secret_value_admin
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
        return response()->json(['success'=> 'Trở về danh sách giải thưởng thành công']);
    }

    public function updateSecretValue (Request $request) {
        if(Session::has('secret_value')) {
            Session::forget('secret_value');
        }
        Session::put('secret_value', $request->secret_value);
        return back();
    }

    public function deletePrize ($ma_giai_thuong) {
        DanhSachGiaiThuong::find($ma_giai_thuong)->delete();
        return response()->json(['success'=> 'Xóa giải thưởng thành công']);
    }
}
