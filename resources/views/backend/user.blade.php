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
        <div class="backend_container">
            @include('backend.logout')
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
                            <a href="{{route('prize')}}" class="inside_item">
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
                @php
                    $error = false;
                    $success = false;
                    $ma_nguoi_dung = '';
                    if (Session::get('ma_nguoi_dung')) {
                        $ma_nguoi_dung = Session::get('ma_nguoi_dung');
                        Session::forget('ma_nguoi_dung');
                    }
                @endphp
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
                <div class="row">
                    <div class="col-md-6">
                        <div class="item">
                            <div class="title">THÊM MỚI NGƯỜI DÙNG</div>
                            <form action="{{route('user')}}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" name="id_nguoi_dung" />
                                <div class="row">
                                    <div class="col-md-3">
                                        <input class="form-control" name="ma_nguoi_dung" placeholder="Mã ..." />
                                    </div>
                                    <div class="col-md-6">
                                        <input class="form-control" name="ten_nguoi_dung" placeholder="Tên người dùng ..." />  
                                    </div>
                                    <div class="col-md-3">
                                        <input class="form-control" name="chuc_danh" placeholder="Chức danh ..." />
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
                        <div class="item">
                            <div class="title">IMPORT TỪ EXCEL</div>
                            <form action="{{route('import_user')}}" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label for="excel_import">File excel:</label>
                                    <input type="file" name="import_user_file" class="form-control-file">
                                </div>
                                <div class="form-group">
                                    <input type="radio" id="import_user_0" name="loai_nguoi_dung" value="0">
                                    <label for="import_user_0">Khách bên ngoài</label> &nbsp; &nbsp; &nbsp;
                                    <input type="radio" id="import_user_1" name="loai_nguoi_dung" value="1">
                                    <label for="import_user_1">Nhân viên bệnh viện</label>
                                </div>
                                <input type="checkbox" id="delete_old_data" name="delete_old_data" value="1">
                                <label for="delete_old_data"> Xóa dữ liệu cũ </label><br>
                                <button class="btn btn-warning btn-sm" type="submit">Import danh sách người dùng</button> 
                            </form>
                        </div>
                    </div>
                    <div class="col-md-12 form-group">
                        <div style="width: 60%;
                                margin: auto;
                                margin-top: 2rem;">
                            <label for="user_search">Tìm kiếm người dùng</label>
                            <input class="form-control" name="user_search" id="user_search" 
                                placeholder="Tìm kiếm người dùng ..." />
                        </div>
                    </div>
                    @foreach ($dsnguoidung_obj as $dsnguoidung_item)
                    <div class="col-md-3 item_user" data-user="{{strtoupper($dsnguoidung_item->ma_nguoi_dung . ' ' . $dsnguoidung_item->ten_nguoi_dung) ?? ''}}">
                        <div class="item
                            {{$error && $ma_nguoi_dung == $dsnguoidung_item->ma_nguoi_dung ? 'error_active' : ''}}
                            {{$success && $ma_nguoi_dung == $dsnguoidung_item->ma_nguoi_dung ? 'success_active' : ''}}">
                            <form action="{{route('user')}}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" name="id_nguoi_dung" value="{{$dsnguoidung_item->id_nguoi_dung ?? ''}}" />
                                <div class="row">
                                    <div class="col-md-3">
                                        <input class="form-control" name="ma_nguoi_dung" 
                                            value="{{$dsnguoidung_item->ma_nguoi_dung ?? ''}}" placeholder="Mã ..." />
                                    </div>
                                    <div class="col-md-6">
                                        <input class="form-control" name="ten_nguoi_dung" 
                                            value="{{$dsnguoidung_item->ten_nguoi_dung ?? ''}}" placeholder="Tên người dùng ..." /> 
                                    </div>
                                    <div class="col-md-3">
                                        <input class="form-control" name="chuc_danh" 
                                            value="{{$dsnguoidung_item->chuc_danh ?? ''}}" placeholder="Chức danh ..." /> 
                                    </div>
                                    <div class="col-md-10">
                                        <div class="form-group" style="display: flex;">
                                            <input type="checkbox" class="form-control form-check-input" 
                                                id="loai_nguoi_dung_{{$dsnguoidung_item->id_nguoi_dung}}" name="loai_nguoi_dung" value="1" 
                                                {{isset($dsnguoidung_item->loai_nguoi_dung) && $dsnguoidung_item->loai_nguoi_dung == 1 ? 'checked' : ''}}>
                                            &nbsp;&nbsp;<label for="loai_nguoi_dung_{{$dsnguoidung_item->id_nguoi_dung}}" class="form-check-label">Là nhân viên bệnh viện</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
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
                    <div class="col-md-12">
                        {!! $dsnguoidung_obj->links() !!}
                    </div>
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