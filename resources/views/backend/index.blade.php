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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    </head>
    <body>
        <!-- <div class="background-image">
            <img src='{{asset("public/images/backend_background.png")}}' />
        </div> -->
        <div class="backend_container">
            <div class="header">
                <div class="row">                    
                    <div class="col-md-6">
                        <div class="item">
                            <a href="{{route('admin')}}" class="inside_item active">
                                GIẢI THƯỞNG
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="item">
                            <a href="{{route('user')}}" class="inside_item">
                                DANH SÁCH KHÁCH MỜI
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content">
                @if(Session::has('success'))
                    <div class="alert alert-success">
                        {{ Session::get('success') }}
                        @php
                            Session::forget('success');
                        @endphp
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-4" style="padding: 0.1rem;">
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
                                                @if ($errors->has('noi_dung'))
                                                    <span class="text-danger">{{ $errors->first('noi_dung') }}</span>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                <input class="form-control" name="so_thu_tu" value="" 
                                                    placeholder="Số thứ tự ... " />
                                                @if ($errors->has('so_thu_tu'))
                                                    <span class="text-danger">{{ $errors->first('so_thu_tu') }}</span>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="checkbox" class="form-control form-check-input" 
                                                        id="da_nhan_giai" name="da_nhan_giai" value="1">
                                                    <label for="is_admin" class="form-check-label"> Đã nhận giải</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <input class="form-control" name="ma_so_nhan_giai" value="" 
                                                    placeholder="Mã số nhận giải ... " />
                                            </div>
                                            <div class="col-md-6">
                                                <input class="form-control" name="ten_nguoi_nhan_giai" value="" 
                                                    placeholder="Tên người nhận giải ... " />
                                            </div>
                                            <div class="col-md-6">
                                                <input class="form-control" name="thoi_gian_cho" value="" 
                                                    placeholder="Thời gian chờ ... " />
                                            </div>
                                            <div class="col-md-6">
                                                <button type="submit" class="btn btn-warning">Lưu</button>
                                            </div>
                                        </div>                                        
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @foreach ($giaithuong_obj as $giaithuong_item)
                    <div class="col-md-2" style="padding: 0.1rem;">
                        <div class="item {{$giaithuong_item->da_nhan_giai == 1 ? 'active' : ''}}">
                            <div class="inside_item">
                                <form action="{{route('updateprize')}}" method="POST">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <input type="hidden" name="ma_giai_thuong" value="{{$giaithuong_item->ma_giai_thuong ?? ''}}" />
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="noi_dung">Tên giải: </label>
                                                <input class="form-control" name="noi_dung" id="noi_dung" value="{{$giaithuong_item->noi_dung ?? ''}}" 
                                                placeholder="Tên giải ... " />
                                                @if ($errors->has('noi_dung'))
                                                    <span class="text-danger">{{ $errors->first('noi_dung') }}</span>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                <label for="so_thu_tu">STT: </label>
                                                <input class="form-control" name="so_thu_tu" id="so_thu_tu" value="{{$giaithuong_item->so_thu_tu ?? ''}}" 
                                                    placeholder="Số thứ tự ... " />
                                                @if ($errors->has('so_thu_tu'))
                                                    <span class="text-danger">{{ $errors->first('so_thu_tu') }}</span>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="checkbox" class="form-control form-check-input" 
                                                        id="da_nhan_giai" name="da_nhan_giai" value="1" 
                                                        {{isset($giaithuong_item->da_nhan_giai) && $giaithuong_item->da_nhan_giai == 1 ? 'checked' : ''}}>
                                                    <label for="is_admin" class="form-check-label"> Đã nhận giải</label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <label for="ma_so_nhan_giai">Mã nhận giải: </label>
                                                <input class="form-control" name="ma_so_nhan_giai" id="ma_so_nhan_giai" value="{{$giaithuong_item->ma_so_nhan_giai ?? ''}}" 
                                                placeholder="Mã số nhận giải ... " />
                                            </div>
                                            <div class="col-md-12">
                                                <label for="ten_nguoi_nhan_giai">Tên nhận giải: </label>
                                                <input class="form-control" name="ten_nguoi_nhan_giai" id="ten_nguoi_nhan_giai" value="{{$giaithuong_item->ten_nguoi_nhan_giai ?? ''}}" 
                                                placeholder="Tên người nhận giải ... " />
                                            </div>
                                            <div class="col-md-6">
                                                <label for="thoi_gian_cho">Thời gian chờ: </label>
                                                <input class="form-control" name="thoi_gian_cho" id="thoi_gian_cho" value="{{$giaithuong_item->thoi_gian_cho ?? ''}}" 
                                                    placeholder="Thời gian chờ ... " />
                                            </div>
                                            <div class="col-md-6">
                                                <button type="submit" class="btn btn-warning">Lưu</button>
                                            </div>
                                        </div>                                        
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </body>
</html>