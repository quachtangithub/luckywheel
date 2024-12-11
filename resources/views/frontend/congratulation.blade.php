<div class="fireworks">
    <img class="flower_left_background" src='{{asset("public/images/flower_left_background.png")}}' />
    <img class="flower_right_background" src='{{asset("public/images/flower_left_background.png")}}' />
    <div class="fw-text-wrapper">
        <h1 class="fw-text-overlay">
            <span class="letters letters-1">{{$giaithuong_obj->noi_dung ?? ''}}</span>
            <span class="letters letters-2">{{$giaithuong_obj->ma_so_nhan_giai_thuc_te ?? ''}}</span>
            <span class="letters letters-3">{{$giaithuong_obj->ten_nguoi_nhan_giai_thuc_te ?? ''}}</span>
        </h1>
    </div>
    <div id="canvas-container"></div>
</div>
<div class="modal-content">
    <div class="js-container container" style="top: 0 !important; left: 0 !important;background-color: #930000; border: none;"></div>
    <div class="popup_background">
        <div class="popup_background_frame">
            <img src='{{asset("public/images/left_background.png")}}' />
            <!-- <div class="left_popup_background">
                <img class="swing" style="box-shadow: none;" src='{{asset("public/images/female_lion_dance.png")}}'>
            </div>
            <div class="right_popup_background">
                <img class="swing" style="box-shadow: none;" src='{{asset("public/images/lion_background.png")}}'>
            </div> -->
        </div>
    </div>
    <div style="text-align:center;margin-top:30px;position: fixed;width:100%;height:100%;left:0px;">
        <div class="checkmark-circle">
            <img src='{{asset("public/images/logo-untext.png")}}'>
        </div>
        <div id="prize_customer">
            <h1>XIN CHÚC MỪNG!</h1>
            <h1>{{$giaithuong_obj->noi_dung ?? ''}}</h1>
            <p id="winner">
                {{$giaithuong_obj->ma_so_nhan_giai_thuc_te ?? ''}}
            </p>
            <div style="background-color: #9d0000a8;">
                <p id="winner_name" class="sub_title_popup">{{$giaithuong_obj->ten_nguoi_nhan_giai_thuc_te ?? ''}}</p>
            </div>
            <a class="btn submit-btn btn-danger custom_button" id="close_prize_customer" type="submit">TIẾP TỤC</a>
        </div>
    </div>  
</div>
<script>
    $(document).ready(function() {
        setTimeout(function() {
            $('#prize_customer').show();
        }, 5000);
        $('#close_prize_customer').on('click', function(e) {
            e.preventDefault();
            $('#resultModel').modal('hide'); 
            frameContainer();
        });
    });
</script>
<script src="{{asset('public/js/fireworks.js')}}"></script>
<link rel="stylesheet" href="{{asset('public/css/congratulation.css')}}">