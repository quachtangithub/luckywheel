<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DanhSachGiaiThuong extends Model
{
    protected $table = "danh_sach_giai_thuong";
    protected $primaryKey = "ma_giai_thuong";
    protected $fillable = [
        'ma_giai_thuong',
        'noi_dung',
        'hinh_anh',
        'so_thu_tu',
        'ma_so_nhan_giai',
        'ten_nguoi_nhan_giai',
        'da_nhan_giai',
        'thoi_gian_cho'
    ];
}
