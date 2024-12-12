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
        <script src="https://js.pusher.com/4.4/pusher.min.js"></script>
    </head>
    <body> 
        <input type="hidden" id="frame_container_url" value="{{route('framecontainer')}}" />
        <input type="hidden" id="audio_url" value="{{asset('public/audio/amthanhnen.mp3')}}" />  
        <input type="hidden" id="magiaithuong" value="" />
        <input type="hidden" id="update_winner_url" value="{{route('updatewinner')}}" />
        <input type="hidden" id="config_winner_url" value="{{route('getconfigwinner')}}" />
        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
        <div class="frame_container" id="frame_container">
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
            <div id="frame_data"></div>
        </div>
        <div class="modal fade" id="resultModel" tabindex="-1" role="dialog" 
            aria-labelledby="resultModelLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered full_modal_dialog" role="document">
                <div id="congratulation"></div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                frameContainer();
            });
            
            function frameContainer () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var url = $('#frame_container_url').val();
                $('#frame_data').html();
                $.ajax({
                    url: url,
                    type: 'GET',
                    contentType: false,
                    processData: false,
                    success: function(result){     
                        $('#frame_data').html(result);    
                    }
                });
            }
        </script>
        <script type="text/javascript">
            var pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
                encrypted: true,
                cluster: "ap1"
            });
            var channel = pusher.subscribe('NotificationEvent');
            channel.bind('send-message', function(data) {
                if (data.type == 'begin') {
                    $('.lucky_numbers .item').html('');
                    for (let i = 1; i <= 5; i++) {
                        document.getElementById('number_' + i).classList.remove('active');
                    }
                    var magiaithuong = data.ma_giai_thuong;
                    var tengiaithuong = data.ten_giai_thuong;
                    $('#tengiaithuong').html(tengiaithuong);
                    $('#magiaithuong').val(magiaithuong);
                    $('#resultModel').modal('hide'); 
                    $('.lucky_numbers').show();                    
                    Array.from(document.querySelectorAll('.prize_all')).forEach(function(el) { 
                        el.classList.remove('item_active');
                    });
                    document.getElementById('prize_' + magiaithuong).classList.add('item_active');
                    $('#start').show();
                } else if (data.type == 'end') {
                    document.body.classList.add("backgroundAnimated");     
                    audio.play();  
                    $('#start').hide();
                    getConfigWinner();
                }   else if (data.type == 'returnprize') {
                    $('#resultModel').modal('hide'); 
                    frameContainer();
                } 
            });
        </script>
    </body>
</html>