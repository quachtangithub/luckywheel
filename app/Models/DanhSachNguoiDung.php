<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DanhSachNguoiDung extends Model
{
    protected $table = "danh_sach_nguoi_dung";
    protected $primaryKey = "id_nguoi_dung";
    protected $fillable = [
        'id_nguoi_dung',
        'ma_nguoi_dung',
        'ten_nguoi_dung',
        'loai_nguoi_dung'
    ];
}
