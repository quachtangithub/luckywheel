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
            <div class="header">
                <div class="row">
                    <div class="col-md-3">
                        <div class="item">
                            <a href="{{route('admin')}}" class="inside_item">
                                GIẢI THƯỞNG
                            </a>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="item">
                            <a href="{{route('user')}}" class="inside_item active">
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
                    <div class="col-md-6">
                        <div class="item">
                            <form action="{{route('user')}}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" name="id_nguoi_dung" />
                                <div class="row">
                                    <div class="col-md-4">
                                        <input class="form-control" name="ma_nguoi_dung" placeholder="Mã ..." />
                                    </div>
                                    <div class="col-md-8">
                                        <input class="form-control" name="ten_nguoi_dung" placeholder="Tên người dùng ..." />  
                                    </div>
                                    <div class="col-md-10">
                                        <div class="form-group" style="display: flex;">
                                            <input type="checkbox" class="form-control form-check-input" 
                                                id="loai_nguoi_dung" name="loai_nguoi_dung" value="1" />
                                            &nbsp;&nbsp;<label for="loai_nguoi_dung" class="form-check-label">Là nhân viên bệnh viên</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-warning btn-sm">Lưu</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <input class="form-control" name="user_search" id="user_search" placeholder="Tìm kiếm người dùng ..." />
                    </div>
                    @foreach ($dsnguoidung_obj as $dsnguoidung_item)
                    <div class="col-md-3 item_user" data-user="{{strtoupper($dsnguoidung_item->ma_nguoi_dung . ' ' . $dsnguoidung_item->ten_nguoi_dung) ?? ''}}">
                        <div class="item">
                            <form action="{{route('user')}}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" name="id_nguoi_dung" value="{{$dsnguoidung_item->id_nguoi_dung ?? ''}}" />
                                <div class="row">
                                    <div class="col-md-4">
                                        <input class="form-control" name="ma_nguoi_dung" 
                                            value="{{$dsnguoidung_item->ma_nguoi_dung ?? ''}}" placeholder="Mã ..." />
                                        @if ($errors->has('ma_nguoi_dung'))
                                            <span class="text-danger">{{ $errors->first('ma_nguoi_dung') }}</span>
                                        @endif
                                    </div>
                                    <div class="col-md-8">
                                        <input class="form-control" name="ten_nguoi_dung" 
                                            value="{{$dsnguoidung_item->ten_nguoi_dung ?? ''}}" placeholder="Tên người dùng ..." />                                      
                                        @if ($errors->has('ten_nguoi_dung'))
                                            <span class="text-danger">{{ $errors->first('ten_nguoi_dung') }}</span>
                                        @endif
                                    </div>
                                    <div class="col-md-10">
                                        <div class="form-group" style="display: flex;">
                                            <input type="checkbox" class="form-control form-check-input" 
                                                id="loai_nguoi_dung_{{$dsnguoidung_item->id_nguoi_dung}}" name="loai_nguoi_dung" value="1" 
                                                {{isset($dsnguoidung_item->loai_nguoi_dung) && $dsnguoidung_item->loai_nguoi_dung == 1 ? 'checked' : ''}}>
                                            &nbsp;&nbsp;<label for="loai_nguoi_dung_{{$dsnguoidung_item->id_nguoi_dung}}" class="form-check-label">Là nhân viên bệnh viện</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-warning btn-sm">Lưu</button>
                                        <button class="btn btn-danger btn-sm delete" onclick="delete_user({{$dsnguoidung_item->id_nguoi_dung ?? 0}})">
                                            <i class='fa fa-trash'></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <script>
            function delete_user(id) {
                if (confirm('Bạn có chắc muốn xóa người dùng này?')) {
                    if (id == '') {
                        alert('Dữ liệu không hợp lệ');
                    } else {
                        let url = "{{route('user/delete',':id')}}";
                        url = url.replace(':id', id);
                        $.ajax({
                            url: url,
                            method: 'GET',
                            contentType: false,
                            processData: false,
                            success: function(result){
                                if (result.success) {
                                    alert(result.success);
                                    window.location.reload();
                                } else if (result.errors) {
                                    alert(result.errors);
                                }
                            }
                        });
                    }
                }
            }
        </script>
    </body>
</html>