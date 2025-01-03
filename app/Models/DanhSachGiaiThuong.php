<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DanhSachGiaiThuong extends Model
{
    protected $table = "danh_sach_giai_thuong";
    protected $primaryKey = "ma_giai_thuong";
    protected $fillable = [
        'ma_giai_thuong',
        'ma_giai_thuong_goc',
        'noi_dung',
        'hinh_anh',
        'so_thu_tu',
        'ma_so_nhan_giai',
        'ten_nguoi_nhan_giai',
        'phan_loai_khach',
        'ma_so_nhan_giai_thuc_te',
        'ten_nguoi_nhan_giai_thuc_te',
        'da_nhan_giai',
        'thoi_gian_cho',
        'user_id',
        'trang_thai'
    ];
}
