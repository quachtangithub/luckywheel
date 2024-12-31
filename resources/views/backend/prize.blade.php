<!DOCTYPE html>
<html lang="en" style="height: auto;">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="refresh" content="{{Cache::get('page_reloading_time')}}">
        <title>Bệnh viện SIS</title>
        <link rel="stylesheet" href="{{asset('public/css/frontend.css')}}">
        <link href="{{asset('public/libs/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
        <script src="{{asset('public/libs/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('public/libs/bootstrap/js/bootstrap.min.js')}}"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    </head>
    <body>
       <div class="row" style="width: fit-content;
                margin: auto;
                margin-top: 3rem;
                background-color: #fff;
                padding: 1rem;
                border-radius: 1rem;
                box-shadow: 1px 1px 10px #919191;">
            <div class="sub_title">ĐIỀU KHIỂN QUAY SỐ</div>
            <div class="col-md-3 col-xs-0"></div>
            <div class="col-md-6 col-xs-12">
                <form id="formData">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="secret_value_admin">Khóa bí mật</label>
                        <input class="form-control" id="secret_value_admin" 
                            name="secret_value_admin" value="{{$secret_value_admin ?? ''}}" />
                    </div>
                    <div class="form-group">
                        <label for="ma_giai_thuong">Giải thưởng</label>
                        <select name="ma_giai_thuong" id="ma_giai_thuong" class="form-control">
                            <option value="0"
                                {{$ma_giai_thuong == 0 || $ma_giai_thuong == '' ? 'selected' : ''}}>THÊM MỚI GIẢI THƯỞNG</option>
                            @foreach ($ds_giaithuong_obj as $ds_giaithuong_item)
                            <option value="{{$ds_giaithuong_item->ma_giai_thuong ?? ''}}" 
                                {{$ds_giaithuong_item->ma_giai_thuong == $ma_giai_thuong ? 'selected' : ''}}>
                                {{$ds_giaithuong_item->noi_dung ?? ''}}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @if ($ma_giai_thuong != 0 && $ma_giai_thuong != '')
                        <button type="button" class="btn btn-danger btn-sm" id="copy_prize">Sao chép giải thưởng</button>
                    @endif
                    @if ($ma_giai_thuong == 0 || $ma_giai_thuong == '')
                        <div class="form-group">
                            <label for="noi_dung">Tên giải cần thêm mới</label>
                            <input class="form-control" id="noi_dung" 
                                name="noi_dung" value="GIẢI THƯỞNG BỔ SUNG{{$name_count > 0 ? ' (' . $name_count. ')' : ''}}" />
                        </div>
                        <div class="form-group">
                            <label for="so_thu_tu">Số thứ tự</label>
                            <input class="form-control" id="so_thu_tu" 
                                name="so_thu_tu" value="100" />
                        </div>
                    @endif
                    <br>
                    <div class="form-group">
                        <input type="radio" class="form-check-input" id="tat_ca_khach" 
                            name="phan_loai_khach" value="1" {{$current_giaithuong_obj->phan_loai_khach == 1 ? 'checked': ''}}>
                        <label class="form-check-label" for="tat_ca_khach">
                            Lấy ngẫu nhiên tất cả khách
                        </label><br>

                        <input type="radio" class="form-check-input" id="khach_noi_bo" 
                            name="phan_loai_khach" value="2" {{$current_giaithuong_obj->phan_loai_khach == 2 ? 'checked': ''}}>
                        <label class="form-check-label" for="khach_noi_bo">Lấy ngẫu nhiên khách nội bộ</label><br>

                        <input type="radio" class="form-check-input" 
                            id="khach_ben_ngoai" 
                            name="phan_loai_khach" value="3" 
                            {{$current_giaithuong_obj->phan_loai_khach == 3 ? 'checked': ''}}>
                        <label class="form-check-label" 
                            for="khach_ben_ngoai">
                            Lấy ngẫu nhiên khách bên ngoài
                        </label><br>

                        <input type="radio" class="form-check-input" 
                            id="khach_chi_dinh" 
                            name="phan_loai_khach" value="" 
                            {{$current_giaithuong_obj->phan_loai_khach == '' ? 'checked': ''}}>
                        <label class="form-check-label" 
                            for="khach_chi_dinh">
                            Khách chỉ định
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="ma_so_nhan_giai">Mã số khách chỉ định: </label>
                        <input class="form-control" name="ma_so_nhan_giai" id="ma_so_nhan_giai" 
                            value="{{$current_giaithuong_obj->ma_so_nhan_giai ?? ''}}" />
                    </div>        
                    <div class="form-group">
                        <label for="ten_nguoi_nhan_giai">Tên khách chỉ định: </label>
                        <input class="form-control" name="ten_nguoi_nhan_giai" id="ten_nguoi_nhan_giai" 
                            value="{{$current_giaithuong_obj->ten_nguoi_nhan_giai ?? ''}}" />
                    </div>       
                    <div class="form-group">
                        <label for="thoi_gian_cho">Thời gian quay số: </label>
                        <input class="form-control" name="thoi_gian_cho" id="thoi_gian_cho" 
                            value="{{$current_giaithuong_obj->thoi_gian_cho ?? ''}}" />
                    </div><br>
                    <div class="row">
                        @if ($ma_giai_thuong == 0 || $ma_giai_thuong == '')
                            <div class="col-md-5 col-sm-5 col-xs-5 col-5 text-center">
                                <button type="button" class="btn btn-warning circle_button" id="prestart">
                                    <div class="circle_button_label">Thêm mới giải</div>
                                </button>
                            </div>
                        @else
                            <div class="col-md-3 col-sm-3 col-xs-3 col-3 text-center">
                                <button type="button" class="btn btn-warning circle_button" id="prestart">
                                    <div class="circle_button_label">Khởi động</div>
                                </button>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-3 col-3 text-center">
                                <button type="button" class="btn btn-danger circle_button" id="finish" 
                                    data-id="{{$ds_giaithuong_item->ma_giai_thuong ?? ''}}">
                                    <div class="circle_button_label">Bắt đầu</div>
                                </button>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-3 col-3 text-center">
                                <button type="button" class="btn btn-warning circle_button" id="stop_btn"
                                    data-id="{{$ds_giaithuong_item->ma_giai_thuong ?? ''}}">
                                    <div class="circle_button_label">Dừng lại</div>
                                </button>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-3 col-3 text-center">
                                <button type="button" class="btn btn-warning circle_button" id="returnprize">
                                    <div class="circle_button_label">Danh sách</div>
                                </button>
                            </div>
                        @endif
                    </div> 
                </form>               
            </div>
            <div class="col-md-3 col-xs-0"></div>
       </div>
       <script>
        $(document).ready(function() {
            $('#copy_prize').on('click', function(e) {
                $('#thong_bao').html('');
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                var formData = new FormData(document.getElementById('formData'));
                var actionUrl = "{{route('copyprizeincontrol')}}";
                $.ajax({
                    type: "POST",
                    url: actionUrl,
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(result) {
                        if (result.success) {
                            alert(result.success);
                            let redirect_url = "{{ route('prize')}}?ma_giai_thuong=" + result.ma_giai_thuong;
                            window.location.href = redirect_url;
                        } else {
                            alert(result.error);
                        }
                    }
                });
            });

            $('select#ma_giai_thuong').on('change', function (e) {
                var optionSelected = this.value;
                var route = "{{route('prize')}}?ma_giai_thuong=" + optionSelected;
                window.location.href = route;
            });

            $("#prestart").on('click', function(e) {
                $('#thong_bao').html('');
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                var formData = new FormData(document.getElementById('formData'));
                var actionUrl = "{{route('updateprizeincontrol')}}";
                $.ajax({
                    type: "POST",
                    url: actionUrl,
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(result) { 
                        if (result.success) {
                            // alert(result.success);
                            let redirect_url = "{{ route('prize')}}?ma_giai_thuong=" + result.ma_giai_thuong;
                            window.location.href = redirect_url;
                        } else {
                            alert(result.error);
                        }
                    }
                });
            });

            $('#finish').on('click', function () {
                let ma_giai_thuong = $(this).data('id');
                if (confirm("Bạn có chắc muốn bắt đầu")) {
                    $('#thong_bao').html('');
                    let secret_value_admin = $('#secret_value_admin').val();
                    let url = "{{route('play',':id')}}";
                    url = url.replace(':id', ma_giai_thuong);
                    url = url + '?secret_value_admin=' + secret_value_admin;
                    $.ajax({
                        url: url,
                        method: 'GET',
                        contentType: false,
                        processData: false,
                        success: function(result){
                            if (result.success) {
                                // alert(result.success);
                                location.reload();
                            } else if (result.errors) {
                                alert(result.error);
                            }
                        }
                    });
                }
            });

            $('#stop_btn').on('click', function () {
                let ma_giai_thuong = $(this).data('id');
                if (confirm("Bạn có chắc muốn hủy")) {
                    $('#thong_bao').html('');
                    let secret_value_admin = $('#secret_value_admin').val();
                    let url = "{{route('stop',':id')}}";
                    url = url.replace(':id', ma_giai_thuong);
                    url = url + '?secret_value_admin=' + secret_value_admin;
                    $.ajax({
                        url: url,
                        method: 'GET',
                        contentType: false,
                        processData: false,
                        success: function(result){
                            if (result.success) {
                                // alert(result.success);
                                location.reload();
                            } else if (result.errors) {
                                alert(result.error);
                            }
                        }
                    });
                }
            });

            $('#returnprize').on('click', function () {
                if (confirm("Bạn có chắc muốn quay lại danh sách giải thưởng")) {
                    let secret_value_admin = $('#secret_value_admin').val();
                    let url = "{{route('returnprize')}}" + '?secret_value_admin=' + secret_value_admin;
                    $.ajax({
                        url: url,
                        method: 'GET',
                        contentType: false,
                        processData: false,
                        success: function(result){
                            if (result.success) {
                                // alert(result.success);
                                location.reload();
                            } else if (result.errors) {
                                alert(result.error);
                            }
                        }
                    });
                }
            });
        });
       </script>
    </body>
</html>