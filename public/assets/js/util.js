function validate(evt) {
    var theEvent = evt || window.event;
  
    // Handle paste
    if (theEvent.type === 'paste') {
        key = event.clipboardData.getData('text/plain');
    } else {
    // Handle key press
        var key = theEvent.keyCode || theEvent.which;
        key = String.fromCharCode(key);
    }
    var regex = /[0-9]|\./;
    if( !regex.test(key) ) {
      theEvent.returnValue = false;
      if(theEvent.preventDefault) theEvent.preventDefault();
    }
  }

  //Limite de caracteres campo obs
  $(document).on("input", "#obs", function() {
    var limite = 455;
    var informativo = "caracteres restantes";
    var caracteresDigitados = $(this).val().length;
    var caracteresRestantes = limite - caracteresDigitados;

    if (caracteresRestantes <= 0) {
        var comentario = $("textarea[name=obs]").val();
        $("textarea[name=obs]").val(comentario.substr(0, limite));
        $(".caracteres").text("0 " + informativo);
    } else {
        $(".caracteres").text(caracteresRestantes + " " + informativo);
    }
});

//TIMER SESSAO 
var timer;
const COUNTER_KEY = 'my-counter';

function countDown(i, callback) {
  //callback = callback || function(){};
  timer = setInterval(function() {
    minutes = parseInt(i / 60, 10);
    seconds = parseInt(i % 60, 10);

    minutes = minutes < 10 ? "0" + minutes : minutes;
    seconds = seconds < 10 ? "0" + seconds : seconds;

    document.getElementById("displayDiv").innerHTML = "Sua sessão irá expirar em  "  + minutes + ":" + seconds;

    if ((i--) > 0) {
      window.sessionStorage.setItem(COUNTER_KEY, i);
    } else {
      window.sessionStorage.removeItem(COUNTER_KEY);
      clearInterval(timer);
      callback();
    }
  }, 1000);
}
// Example starter JavaScript for disabling form submissions if there are invalid fields
(function() {
  'use strict';
  window.addEventListener('load', function() {

    var countDownTime = window.sessionStorage.getItem(COUNTER_KEY) || 3600;
    countDown(countDownTime, function() {
        swal({
        icon: "error",
        title: "Seu tempo acabou!",
        text: "Você será redirecionado para a página de login."
        }).then( function() {
            // this gets run after the OK button is clicked
            document.getElementById('logout-form').submit();
        });
     });

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();