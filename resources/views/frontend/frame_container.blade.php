<script src="{{asset('public/js/frontend.js')}}"></script>
<div class="lucky_numbers">
    <div class="sub_title" id="tengiaithuong">VÒNG QUAY MAY MẮN</div>
    <div class="start_btn_group">
        <a id="start" autofocus class="custom_animation_btn">BẮT ĐẦU</a>        
        <a id="stop" class="custom_animation_btn">Dừng lại</a>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div id="myBar">                        
            </div>
            <!-- <img class="lion_img" src='{{asset("public/images/lion_background.png")}}' /> -->
            <div class="row item_container">
                <div class="col-xs-2-10 item_div">
                    <div class="item" id="number_1"></div>
                </div>
                <div class="col-xs-2-10 item_div">
                    <div class="item" id="number_2"></div>
                </div>
                <div class="col-xs-2-10 item_div">
                    <div class="item" id="number_3"></div>
                </div>
                <div class="col-xs-2-10 item_div">
                    <div class="item" id="number_4"></div>
                </div>
                <div class="col-xs-2-10 item_div">
                    <div class="item" id="number_5"></div>
                </div>
            </div>
        </div><br>
        <div class="back_to_prize" onclick="frameContainer()">Trở về danh sách giải</div>
    </div>            
</div>

<div class="list-group">
    <div class="sub_title">DANH SÁCH GIẢI THƯỞNG</div>
    <div class="row">
        @php $stt = 1; @endphp
        @foreach ($danhsachgiaithuong as $giaithuong)
        @if ($stt == 1 || $stt == 3)
            <div class="col-md-3 col-sm-4"></div>
        @endif
        <div class="col-md-3 col-sm-4" style="padding: 0.2rem; min-height: 6rem;">
            <div class="list-group-item prize_all {{$giaithuong->da_nhan_giai == 1 ? 'active' : ''}}" 
                data-magiaithuong="{{$giaithuong->ma_giai_thuong ?? ''}}" 
                data-tengiaithuong="{{$giaithuong->noi_dung ?? ''}}"
                id="prize_{{$giaithuong->ma_giai_thuong ?? ''}}">
                @if ($giaithuong->da_nhan_giai == 0)
                    <i class="fa-solid fa-award"></i>
                @endif
                <p class="prize">{{$giaithuong->noi_dung ?? ''}}</p>
                @if ($giaithuong->ten_nguoi_nhan_giai_thuc_te != '' && $giaithuong->da_nhan_giai == 1) 
                    <span class="emphasize">{{$giaithuong->ten_nguoi_nhan_giai_thuc_te ?? ''}}</span>
                @endif
            </div>
        </div>
        @php $stt++; @endphp
        @endforeach
    </div>
</div>