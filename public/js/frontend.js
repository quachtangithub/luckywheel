//Some parameters
var audio = document.getElementById("bg-sound");

var results = "";

var number = 1;
$(document).ready(function() {
    var duration_setting = 15;
    var duration_device = duration_setting / 5;
    duration_device = duration_device.toFixed();
    var winner = '12345';
    var winner_arr = winner.split('');
    $("#start").click(function() { 
        document.body.classList.add("backgroundAnimated");       
        $('#start').hide();
        startRandom(1, duration_setting - duration_device * 4, winner_arr);
        startRandom(2, duration_setting - duration_device * 3, winner_arr);
        startRandom(3, duration_setting - duration_device * 2, winner_arr);
        startRandom(4, duration_setting - duration_device, winner_arr);
        startRandom(5, duration_setting, winner_arr);
        startRandom(6, duration_setting);
    });
});

function startRandom(number, duration_setting, winner_arr = []) {
    var started = new Date().getTime();
    var output = $('#number_' + number);
    // load();
    var duration = 1000 * duration_setting;
    animationTimer = setInterval(function() {
        let distance_time = new Date().getTime() - started;
        if (distance_time > duration) {
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
                    document.body.classList.remove("backgroundAnimated");
                    $('#winner').html(winner);
                    $('#resultModel').modal('show'); 
                }, 1000);
            }
        } else {
            output.text(
                getRandomInt(9)
            );
        }
    }, 50);
}

function getRandomInt(max) {
    return Math.floor(Math.random() * max);
}

// congratolation

$(document).ready(function() {
const Confettiful = function(el) {
    this.el = el;
    this.containerEl = null;
    
    this.confettiFrequency = 3;
    this.confettiColors = ['#EF2964', '#00C09D', '#2D87B0', '#48485E','#EFFF1D'];
    this.confettiAnimations = ['slow', 'medium', 'fast'];
    
    this._setupElements();
    this._renderConfetti();
  };
  
  Confettiful.prototype._setupElements = function() {
    const containerEl = document.createElement('div');
    const elPosition = this.el.style.position;
    
    if (elPosition !== 'relative' || elPosition !== 'absolute') {
      this.el.style.position = 'relative';
    }
    
    containerEl.classList.add('confetti-container');
    
    this.el.appendChild(containerEl);
    
    this.containerEl = containerEl;
  };
  
  Confettiful.prototype._renderConfetti = function() {
    this.confettiInterval = setInterval(() => {
      const confettiEl = document.createElement('div');
      const confettiSize = (Math.floor(Math.random() * 3) + 7) + 'px';
      const confettiBackground = this.confettiColors[Math.floor(Math.random() * this.confettiColors.length)];
      const confettiLeft = (Math.floor(Math.random() * this.el.offsetWidth)) + 'px';
      const confettiAnimation = this.confettiAnimations[Math.floor(Math.random() * this.confettiAnimations.length)];
      
      confettiEl.classList.add('confetti', 'confetti--animation-' + confettiAnimation);
      confettiEl.style.left = confettiLeft;
      confettiEl.style.width = confettiSize;
      confettiEl.style.height = confettiSize;
      confettiEl.style.backgroundColor = confettiBackground;
      
      confettiEl.removeTimeout = setTimeout(function() {
        confettiEl.parentNode.removeChild(confettiEl);
      }, 3000);
      
      this.containerEl.appendChild(confettiEl);
    }, 25);
  };
  
  window.confettiful = new Confettiful(document.querySelector('.js-container'));
});
  
  



