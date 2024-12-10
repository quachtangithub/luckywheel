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
        <script src="{{asset('public/libs/dat-gui/0.7.2/dat.gui.js')}}"></script>
        <script src="{{asset('public/libs/animejs/3.2.0/anime.min.js')}}"></script>
        <script src="{{asset('public/js/smoke_animation.js')}}"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    </head>
    <body style="background-image: url('{{asset("public/images/full_background.png")}}'); background-attachment:fixed;
                background-repeat: no-repeat;
                background-size: cover; background-color: #fff2e1;"> 
        <div class="frame_container">   
            <input type="hidden" id="audio_url" value="{{asset('public/audio/amthanhnen.mp3')}}" />  
            <input type="hidden" id="magiaithuong" value="" />
            <input type="hidden" id="update_winner_url" value="{{route('updatewinner')}}" />
            <input type="hidden" id="config_winner_url" value="{{route('getconfigwinner')}}" />
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            <div class="background">
                <img class="top_background" src='{{asset("public/images/top_background.png")}}' />       
                <img class="left_background_header" src='{{asset("public/images/spring_cake.png")}}' />
            </div>
            <div class="header">
                <img class="logo" src='{{asset("public/images/logo.png")}}' />
            </div>
            <div class="left_background">
                <img src='{{asset("public/images/left_background.png")}}' />
            </div>
            <div class="lucky_numbers">
                <div class="main_title" id="tengiaithuong">VÒNG QUAY MAY MẮN</div>
                <div class="row">
                    <div class="col-md-12">
                        <div id="myBar">                        
                        </div>
                        <img class="lion_img" src='{{asset("public/images/lion_background.png")}}' />
                        <div class="row item_container">
                            <div class="col-md-2 col-sm-2 item_div">
                                <div class="item" id="number_1"></div>
                            </div>
                            <div class="col-md-2 col-sm-2 item_div">
                                <div class="item" id="number_2"></div>
                            </div>
                            <div class="col-md-2 col-sm-2 item_div">
                                <div class="item" id="number_3"></div>
                            </div>
                            <div class="col-md-2 col-sm-2 item_div">
                                <div class="item" id="number_4"></div>
                            </div>
                            <div class="col-md-2 col-sm-2 item_div">
                                <div class="item" id="number_5"></div>
                            </div>
                            <div class="col-md-2 col-sm-2 item_div">
                                <button class="custom_button" id="start" autofocus>
                                    <img class="icon" src='{{asset("public/images/play_icon.png")}}' />
                                </button>
                            </div>
                        </div>
                        <!-- <div class="col-md-12">
                            <figure class="swing">
                                <img src='{{asset("public/images/pham_long_khanh.png")}}' width="200" >
                            </figure>  
                        </div> -->
                    </div>
                </div>            
            </div>
            
            <div class="list-group">
                <div class="sub_title">DANH SÁCH GIẢI THƯỞNG</div>
                <div class="row">
                    @php $stt = 1; @endphp
                    @foreach ($danhsachgiaithuong as $giaithuong)
                    @if ($stt == 1 || $stt == 5)
                        <div class="col-md-2 col-sm-3"></div>
                    @endif
                    <div class="col-md-2 col-sm-3" style="padding: 0.2rem; min-height: 6rem;">
                        <div class="list-group-item {{$giaithuong->da_nhan_giai == 1 ? 'active' : ''}}" data-magiaithuong="{{$giaithuong->ma_giai_thuong ?? ''}}" 
                            data-tengiaithuong="{{$giaithuong->noi_dung ?? ''}}">
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
            <div class="modal fade" id="resultModel" tabindex="-1" role="dialog" 
                aria-labelledby="resultModelLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered full_modal_dialog" role="document">
                    <div id="congratulation"></div>
                </div>
            </div>
        </div>
        <script src="{{asset('public/js/frontend.js')}}"></script>
    </body>
</html>