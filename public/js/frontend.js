//Some parameters
var audio = document.getElementById("bg-sound");
var audio_url = $('#audio_url').val();
var audio = new Audio(audio_url);
var results = "";

var number = 1;
$(document).ready(function() {  
    $("#start").click(function() { 
        document.body.classList.add("backgroundAnimated");     
        audio.play();  
        $('#start').hide();
        getConfigWinner();
    });

    $('.list-group-item').on('click', function () {
        var magiaithuong = $(this).data('magiaithuong');
        var tengiaithuong = $(this).data('tengiaithuong');
        $('#tengiaithuong').html(tengiaithuong);
        $('#magiaithuong').val(magiaithuong);
        $('.lucky_numbers').show();
        $('#start').show();
    });
});

function getConfigWinner () {
    var magiaithuong = $('#magiaithuong').val();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var url = $('#config_winner_url').val() + '?mgt=' + magiaithuong;
    $.ajax({
        // dataType: 'json',
        url: url,
        type: 'GET',
        contentType: false,
        processData: false,
        success: function(result){            
            var duration_setting = result.thoi_gian_cho;
            var duration_device = duration_setting / 5;
            duration_device = duration_device.toFixed();  
            var started = new Date().getTime();
            var winner = result.ma_so_nhan_giai;
            var winner_arr = winner.split('');
            startRandom(1, started, duration_setting - duration_device * 4, winner_arr);
            startRandom(2, started, duration_setting - duration_device * 3, winner_arr);
            startRandom(3, started, duration_setting - duration_device * 2, winner_arr);
            startRandom(4, started, duration_setting - duration_device, winner_arr);
            startRandom(5, started, duration_setting, winner_arr);
            // startRandom(6, started, duration_setting);         
        }
    });
}

function startRandom(number, started, duration_setting, winner_arr = []) {
    var output = $('#number_' + number);
    // load();
    var duration = 1000 * duration_setting;
    let animationTimer = setInterval(function() {
        let distance_time = Number(new Date().getTime() - started);
        if (distance_time >= duration) {
            let current_number = '';
            if (typeof winner_arr[number - 1] !== 'undefined' && winner_arr[number - 1] != '') {
                current_number = winner_arr[number - 1];
            }
            output.text(current_number);
            document.getElementById('number_' + number).classList.add("active");
            clearInterval(animationTimer); 
            if (number >= 5) {
                setTimeout(function() {
                    var winner = '';
                    for (let i = 1; i <= 5; i++) {
                        winner = winner + $('#number_' + i).text();
                    }              
                        
                    audio.pause();
                    audio.currentTime = 0;     
                    document.body.classList.remove("backgroundAnimated"); 
                    console.log(distance_time + '///' + duration);
                    // cap nhat nguoi thang giai
                    update_winner(winner);
                    
                    // $('#winner').html(winner);
                    $('#resultModel').modal('show'); 
                }, 100);
            }
        } else {
            output.text(
                getRandomInt(9)
            );
        }
    }, 50);
}

function update_winner (ma_so_nhan_giai) {
    let ma_giai_thuong = $('#magiaithuong').val();
    let url = $('#update_winner_url').val();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    $('#congratulation').html('');
    $.ajax({
        url: url,
        method: 'POST',
        data: {
            "_token": $('#token').val(),
            ma_giai_thuong: ma_giai_thuong,
            ma_so_nhan_giai: ma_so_nhan_giai
        },
        success: function(result){
            $('#congratulation').html(result);
        }
    });
}

function getRandomInt(max) {
    return Math.floor(Math.random() * max);
}




  
  



