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
        <div class="background-image">
            <img src='{{asset("public/images/backend_background.png")}}' />
        </div>
        <div class="backend_container">
            <div class="header">
                <div class="row">
                    <div class="col-md-6">
                        <div class="item">
                            <div class="inside_item active">
                                GIẢI THƯỞNG
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="item">
                            <div class="inside_item">
                                DANH SÁCH KHÁCH MỜI
                            </div>
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
                    <div class="col-md-3">
                        <div class="item">
                            <div class="inside_item">
                                <form action="{{route('updateprize')}}" method="POST">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <input type="hidden" name="ma_giai_thuong" value="" />
                                        <input class="form-control" name="noi_dung" value="" 
                                            placeholder="Tên giải ... " />
                                        @if ($errors->has('noi_dung'))
                                            <span class="text-danger">{{ $errors->first('noi_dung') }}</span>
                                        @endif
                                        <input class="form-control" name="so_thu_tu" value="" 
                                            placeholder="Số thứ tự ... " />
                                        @if ($errors->has('so_thu_tu'))
                                            <span class="text-danger">{{ $errors->first('so_thu_tu') }}</span>
                                        @endif
                                        <input class="form-control" name="ma_so_nhan_giai" value="" 
                                            placeholder="Mã số nhận giải ... " />
                                        <input class="form-control" name="ten_nguoi_nhan_giai" value="" 
                                            placeholder="Tên người nhận giải ... " />
                                        <button type="submit" class="btn btn-warning">Lưu</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @foreach ($giaithuong_obj as $giaithuong_item)
                    <div class="col-md-3">
                        <div class="item {{$giaithuong_item->ma_so_nhan_giai != '' ? 'active' : ''}}">
                            <div class="inside_item">
                                <form action="{{route('updateprize')}}" method="POST">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <input type="hidden" name="ma_giai_thuong" value="{{$giaithuong_item->ma_giai_thuong ?? ''}}" />
                                        <input class="form-control" name="noi_dung" value="{{$giaithuong_item->noi_dung ?? ''}}" 
                                            placeholder="Tên giải ... " />
                                        @if ($errors->has('noi_dung'))
                                            <span class="text-danger">{{ $errors->first('noi_dung') }}</span>
                                        @endif
                                        <input class="form-control" name="so_thu_tu" value="{{$giaithuong_item->so_thu_tu ?? ''}}" 
                                            placeholder="Số thứ tự ... " />
                                        @if ($errors->has('so_thu_tu'))
                                            <span class="text-danger">{{ $errors->first('so_thu_tu') }}</span>
                                        @endif
                                        <input class="form-control" name="ma_so_nhan_giai" value="{{$giaithuong_item->ma_so_nhan_giai ?? ''}}" 
                                            placeholder="Mã số nhận giải ... " />
                                        <input class="form-control" name="ten_nguoi_nhan_giai" value="{{$giaithuong_item->ten_nguoi_nhan_giai ?? ''}}" 
                                            placeholder="Tên người nhận giải ... " />
                                        <button type="submit" class="btn btn-warning">Lưu</button>
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