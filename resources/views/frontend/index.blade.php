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
        <div class="background">
            <img class="top_background" src='{{asset("public/images/top_background.png")}}' />
        </div>   
        <div class="header">
            <img class="logo" src='{{asset("public/images/logo.png")}}' />
        </div>
        <div class="left_background">
            <img src='{{asset("public/images/left_background.png")}}' />
        </div>
        <div class="lucky_numbers">
            <div class="main_title">VÒNG QUAY MAY MẮN</div>
            <div class="row">
                <div class="col-md-12">
                    <div id="myBar">
                        
                    </div>
                    <img class="lion_img" src='{{asset("public/images/lion_background.png")}}' />
                    <div class="row item_container">
                        <div class="col-md-2 col-sm-2 item_div">
                            <div class="item" id="number_1">0</div>
                        </div>
                        <div class="col-md-2 col-sm-2 item_div">
                            <div class="item" id="number_2">0</div>
                        </div>
                        <div class="col-md-2 col-sm-2 item_div">
                            <div class="item" id="number_3">0</div>
                        </div>
                        <div class="col-md-2 col-sm-2 item_div">
                            <div class="item" id="number_4">0</div>
                        </div>
                        <div class="col-md-2 col-sm-2 item_div">
                            <div class="item" id="number_5">0</div>
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
            <div class="sub_title">KẾT QUẢ GIẢI THƯỞNG</div>
            <div class="row">
                <div class="col-md-2 col-sm-3">
                    <div class="list-group-item">
                        <i class="fa-solid fa-award"></i>
                        <p>giải 1: thuốc cúc dịch - <span class="emphasize">Hồ Quốc Tuấn</span></p>
                    </div>
                </div>
                <div class="col-md-2 col-sm-3">
                    <div class="list-group-item">
                        <i class="fa-solid fa-award"></i>
                        <p>giải 1: thuốc cúc dịch - <span class="emphasize">Hồ Quốc Tuấn</span></p>
                    </div>
                </div>
                <div class="col-md-2 col-sm-3">
                    <div class="list-group-item">
                        <i class="fa-solid fa-award"></i>
                        <p>giải 1: thuốc cúc dịch - <span class="emphasize">Hồ Quốc Tuấn</span></p>
                    </div>
                </div>
                <div class="col-md-2 col-sm-3">
                    <div class="list-group-item">
                        <i class="fa-solid fa-award"></i>
                        <p>giải 1: thuốc cúc dịch - <span class="emphasize">Hồ Quốc Tuấn</span></p>
                    </div>
                </div>
                <div class="col-md-2 col-sm-3">
                    <div class="list-group-item">
                        <i class="fa-solid fa-award"></i>
                        <p>giải 1: thuốc cúc dịch - <span class="emphasize">Hồ Quốc Tuấn</span></p>
                    </div>
                </div>
                <div class="col-md-2 col-sm-3">
                    <div class="list-group-item">
                        <i class="fa-solid fa-award"></i>
                        <p>giải 1: thuốc cúc dịch - <span class="emphasize">Hồ Quốc Tuấn</span></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="resultModel" tabindex="-1" role="dialog" 
            aria-labelledby="resultModelLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered full_modal_dialog" role="document">
                <div class="modal-content">
                    <div class="js-container container" style="top:0px !important;"></div>
                    <div style="text-align:center;margin-top:30px;position:  fixed;width:100%;height:100%;top:0px;left:0px;">
                        <div class="checkmark-circle">
                            <div class="background"></div>
                            <div class="checkmark draw"></div>
                        </div>
                        <h1>XIN CHÚC MỪNG!</h1>
                        <p id="winner"></p>
                        <a href="{{route('/')}}" class="btn submit-btn" type="submit">TIẾP TỤC</a>
                    </div>  
                </div>
            </div>
        </div>
        <script src="{{asset('public/js/frontend.js')}}"></script>
    </body>
</html>