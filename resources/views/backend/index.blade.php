<!DOCTYPE html>
<html lang="en" style="height: auto;">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="refresh" content="{{Cache::get('page_reloading_time')}}">
        <title>Bệnh viện SIS</title>
        <link rel="stylesheet" href="{{asset('public/css/backend.css')}}">
        <link href="{{asset('public/libs/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
        <script src="{{asset('public/libs/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('public/libs/bootstrap/js/bootstrap.min.js')}}"></script>        
        <script src="{{asset('public/js/backend.js')}}"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    </head>
    <body>
        <!-- <div class="background-image">
            <img src='{{asset("public/images/backend_background.png")}}' />
        </div> -->
        
        <div class="backend_container">
            @include('backend.logout')
            <div class="header">
                <div class="row">                    
                    <div class="col-md-3">
                        <div class="item">
                            <a href="{{route('admin')}}" class="inside_item active">
                                GIẢI THƯỞNG
                            </a>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="item">
                            <a href="{{route('user')}}" class="inside_item">
                                DANH SÁCH KHÁCH MỜI
                            </a>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="item">
                            <a href="{{route('prize', 0)}}" class="inside_item">
                                ĐIỀU KHIỂN TRỰC TIẾP
                            </a>
                        </div>
                    </div>     
                    <div class="col-md-3">
                        <div class="item">
                            <a href="{{route('/')}}" class="inside_item">
                                QUAY SỐ
                            </a>
                        </div>
                    </div>                  
                    <div class="col-md-12">
                        <br>
                        <input class="form-control" name="prize_search" id="prize_search" placeholder="Tìm kiếm giải thưởng ..." />
                    </div>
                </div>
            </div>
            @php 
                $ten_giai_thuong = '';
                if (Session::get('ten_giai_thuong')) {
                    $ten_giai_thuong = Session::get('ten_giai_thuong');
                    Session::forget('ten_giai_thuong');
                }
                $error = false;
                $success = false;
                $ma_giai_thuong = '';
                if (Session::get('ma_giai_thuong')) {
                    $ma_giai_thuong = Session::get('ma_giai_thuong');
                    Session::forget('ma_giai_thuong');
                }
            @endphp
            <div class="content">
                @if(Session::has('success'))
                    <div class="alert alert-success">
                        {{ Session::get('success') }}
                        @php
                            $success = true;
                            Session::forget('success');
                        @endphp
                    </div>
                @endif
                @if(Session::has('errors'))
                    <div class="alert alert-danger">
                        {{ implode(', ', $errors->all(':message')) }}
                        @php
                            $error = true;
                            Session::forget('errors');
                        @endphp
                    </div>
                @endif
                @if(Session::has('error'))
                    <div class="alert alert-danger">
                        {{ Session::get('error') }}
                        @php
                            $error = true;
                            Session::forget('error');
                        @endphp
                    </div>
                @endif
                <div class="row">
                    <div class="col-lg-2 col-md- item_prize" style="padding: 0.1rem;">
                        <div class="item">
                            <div class="title">THÊM MỚI GIẢI THƯỞNG</div>
                            <div class="inside_item">
                                <form action="{{route('updateprize')}}" method="POST">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <input type="hidden" name="ma_giai_thuong" value="" />
                                        <div class="row">
                                            <div class="col-md-12">
                                                <input class="form-control" name="noi_dung" value="" 
                                                placeholder="Tên giải ... " />
                                            </div>
                                            <div class="col-md-6">
                                                <input class="form-control" name="so_thu_tu" value="" 
                                                    placeholder="STT ... " />
                                            </div>
                                            <div class="col-md-6">
                                                <input class="form-control" name="thoi_gian_cho" value="" 
                                                    placeholder="Thời gian ... " />
                                            </div>
                                            <p style="margin-top: 1rem; border-bottom: 0.01rem solid #d7d7d7;">Cấu hình cụ thể khách</p>
                                            <div class="col-md-12">
                                                <input type="radio" class="form-check-input" id="tat_ca_khach" 
                                                    name="phan_loai_khach" value="1">
                                                <label class="form-check-label" for="tat_ca_khach">Lấy ngẫu nhiên tất cả khách</label><br>
                                                <input type="radio" class="form-check-input" id="khach_noi_bo" 
                                                    name="phan_loai_khach" value="2">
                                                <label class="form-check-label" for="khach_noi_bo">Lấy ngẫu nhiên khách nội bộ</label><br>
                                                <input type="radio" class="form-check-input" id="khach_ben_ngoai" 
                                                    name="phan_loai_khach" value="3">
                                                <label class="form-check-label" for="khach_ben_ngoai">
                                                    Lấy ngẫu nhiên khách bên ngoài
                                                </label><br>
                                                <input type="radio" class="form-check-input" id="khach_chi_dinh" 
                                                    name="phan_loai_khach" value="">
                                                <label class="form-check-label" for="khach_chi_dinh">
                                                    Chỉ định cụ thể khách
                                                </label>
                                            </div>
                                            <div class="col-md-12">
                                                <input class="form-control" name="ma_so_nhan_giai" value="" 
                                                    placeholder="Mã số nhận giải ... " />
                                            </div>
                                            <div class="col-md-12">
                                                <input class="form-control" name="ten_nguoi_nhan_giai" value="" 
                                                    placeholder="Tên người nhận giải ... " />
                                            </div>
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-warning">Lưu</button>
                                            </div>
                                        </div>                                        
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @foreach ($giaithuong_obj as $giaithuong_item)
                    <div class="col-lg-2 col-md-2 item_prize" style="padding: 0.1rem;"
                        data-user="{{strtoupper($giaithuong_item->noi_dung) ?? ''}}">
                        <div class="item {{$giaithuong_item->da_nhan_giai == 1 ? 'active' : ''}}
                            {{$error && $ma_giai_thuong == $giaithuong_item->ma_giai_thuong ? 'error_active' : ''}}
                            {{$success && $ma_giai_thuong == $giaithuong_item->ma_giai_thuong ? 'success_active' : ''}}">
                            <div class="inside_item">
                                <form action="{{route('updateprize')}}" method="POST">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <input type="hidden" name="ma_giai_thuong" value="{{$giaithuong_item->ma_giai_thuong ?? ''}}" />
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="noi_dung">Tên giải: </label>
                                                <input class="form-control" name="noi_dung" id="noi_dung" 
                                                    value="{{$giaithuong_item->noi_dung ?? ''}}" />
                                            </div>
                                            <div class="group_inside_item">
                                                <span class="group_title">Thông tin cấu hình</span>
                                                <br>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <input type="radio" class="form-check-input" id="tat_ca_khach_{{$giaithuong_item->ma_giai_thuong ?? ''}}" 
                                                            name="phan_loai_khach" value="1" {{$giaithuong_item->phan_loai_khach == 1 ? 'checked': ''}}>
                                                        <label class="form-check-label" for="tat_ca_khach_{{$giaithuong_item->ma_giai_thuong ?? ''}}">Tất cả khách</label><br>
                                                        <input type="radio" class="form-check-input" id="khach_noi_bo_{{$giaithuong_item->ma_giai_thuong ?? ''}}" 
                                                            name="phan_loai_khach" value="2" {{$giaithuong_item->phan_loai_khach == 2 ? 'checked': ''}}>
                                                        <label class="form-check-label" for="khach_noi_bo_{{$giaithuong_item->ma_giai_thuong ?? ''}}">Khách nội bộ</label><br>
                                                        <input type="radio" class="form-check-input" 
                                                            id="khach_ben_ngoai_{{$giaithuong_item->ma_giai_thuong ?? ''}}" 
                                                            name="phan_loai_khach" value="3" 
                                                            {{$giaithuong_item->phan_loai_khach == 3 ? 'checked': ''}}>
                                                        <label class="form-check-label" 
                                                            for="khach_ben_ngoai_{{$giaithuong_item->ma_giai_thuong ?? ''}}">
                                                            Khách bên ngoài
                                                        </label><br>
                                                        <input type="radio" class="form-check-input" 
                                                            id="khach_chi_dinh_{{$giaithuong_item->ma_giai_thuong ?? ''}}" 
                                                            name="phan_loai_khach" value="" 
                                                            {{$giaithuong_item->phan_loai_khach == '' ? 'checked': ''}}>
                                                        <label class="form-check-label" 
                                                            for="khach_chi_dinh_{{$giaithuong_item->ma_giai_thuong ?? ''}}">
                                                            Khách chỉ định
                                                        </label>
                                                    </div>
                                                </div>
                                                <p style="padding: 1rem 0 0 !important;
                                                    border-bottom: 0.01rem solid #d7d7d7;">Cấu hình cụ thể khách</p>
                                                <div class="row">
                                                    <div class="col-md-4" style="padding: 0 0.3rem;">
                                                        <label for="ma_so_nhan_giai">Mã: </label>
                                                        <input class="form-control" name="ma_so_nhan_giai" id="ma_so_nhan_giai" 
                                                            value="{{$giaithuong_item->ma_so_nhan_giai ?? ''}}" />
                                                    </div>
                                                    <div class="col-md-8" style="padding: 0 0.3rem;">
                                                        <label for="ten_nguoi_nhan_giai">Tên: </label>
                                                        <input class="form-control" name="ten_nguoi_nhan_giai" id="ten_nguoi_nhan_giai" 
                                                            value="{{$giaithuong_item->ten_nguoi_nhan_giai ?? ''}}" />
                                                    </div>                                                   
                                                </div>                                                
                                            </div>
                                            <div class="col-md-6" style="padding: 0 0.3rem;">
                                                <label for="so_thu_tu">Số thứ tự: </label>
                                                <input class="form-control" name="so_thu_tu" id="so_thu_tu" 
                                                    value="{{$giaithuong_item->so_thu_tu ?? ''}}" />
                                            </div>
                                            <div class="col-md-6" style="padding: 0 0.3rem;">
                                                <label for="thoi_gian_cho">Thời gian: </label>
                                                <input class="form-control" name="thoi_gian_cho" id="thoi_gian_cho" 
                                                    value="{{$giaithuong_item->thoi_gian_cho ?? ''}}" />
                                            </div>
                                            <div class="group_inside_item">
                                                <a class="prize_hide_btn group_title" onClick="showHideDiv('prize_{{$giaithuong_item->ma_giai_thuong ?? ''}}')">Thông tin thực lãnh</a>
                                                <br>
                                                <div class="row prize_hide" id="prize_{{$giaithuong_item->ma_giai_thuong ?? ''}}" style="display: none;">
                                                    <div class="col-md-12" style="padding: 0 0.3rem;">
                                                        <div class="form-group" style="display: flex;">
                                                            <input type="checkbox" class="form-control form-check-input" 
                                                                id="da_nhan_giai" name="da_nhan_giai" value="1" 
                                                                {{isset($giaithuong_item->da_nhan_giai) && $giaithuong_item->da_nhan_giai == 1 ? 'checked' : ''}}>
                                                            <label for="" class="form-check-label"> &nbsp; Đã nhận giải </label>
                                                        </div>
                                                    </div>                        
                                                    <div class="col-md-4" style="padding: 0 0.3rem;">
                                                        <label for="ma_so_nhan_giai_thuc_te">Mã: </label>
                                                        <input class="form-control" name="ma_so_nhan_giai_thuc_te" 
                                                            id="ma_so_nhan_giai_thuc_te" 
                                                            value="{{$giaithuong_item->ma_so_nhan_giai_thuc_te ?? ''}}" />
                                                    </div>
                                                    <div class="col-md-8" style="padding: 0 0.3rem;">
                                                        <label for="ten_nguoi_nhan_giai_thuc_te">Tên: </label>
                                                        <input class="form-control" name="ten_nguoi_nhan_giai_thuc_te" 
                                                            id="ten_nguoi_nhan_giai_thuc_te" 
                                                            value="{{$giaithuong_item->ten_nguoi_nhan_giai_thuc_te ?? ''}}" />
                                                    </div>
                                                </div>                                                
                                            </div>
                                            <div class="col-md-12 text-center" style="padding-top: 1rem 0.3rem;">
                                                <button type="submit" class="btn btn-warning">Lưu</button>
                                            </div>
                                        </div>                                        
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach                    
                    <div class="col-md-12">
                        {!! $giaithuong_obj->links() !!}
                    </div>
                </div>
            </div>
        </div>
       
    </body>
</html>