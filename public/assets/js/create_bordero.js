$(document).ready(function () {
  var nb = $('.element').length;
  $("#totaltitulos").text(" "+nb);

  var sum_amount = 0;
  $('.vlrface').each(function(){
    sum_amount += +$(this).val();
    $('#vlrfacemod').text("R$ "+sum_amount);
  })

    $('.novo-titulo').on('click', '.clone', function (e) {
        e.preventDefault();
        //$('.clone').closest('.novo-titulo').find('.element').first().clone(true, true).appendTo('.results');
        var clone = $('.clone').closest('.novo-titulo').find('.element').first().clone(true, true);
        clone.find("input").val("");     
        clone.find("select").val("");  
        $("input").each(function(i) {
          var nb = $('.element').length;
          var j = nb+1;
          this.id= this.id.replace('['+nb+']', '['+j+']');
        });
        $("select").each(function(i) {
          var nb = $('.element').length;
          var j = nb+1;
          this.id= this.id.replace('['+nb+']', '['+j+']');
        });
        clone.appendTo('.results');
        $( "a" ).removeClass( "disabled" );
        var nb = $('.element').length;
        $("#totaltitulos").text(" "+nb);
        //passando a nova soma para o element hidden 
        //document.getElementById("mod_totalvlrface").value = sum;
    });
    $(document).on('click', '.remove', function(e) {
      e.preventDefault();
     $(this).parents('.element').remove();
     var nb = $('.element').length;
     if(nb==1)
     {
        $( "a.remove" ).addClass( "disabled" )
     }
     $("#totaltitulos").text(" "+nb);

     var sum_amount = 0;
     $('.vlrface').each(function(){
      value = $(this).val();
     
      value = value.toString();
      value = value.replace(',', '.');  

       sum_amount += +value;
       $('#vlrfacemod').text("R$ "+sum_amount);
     })
  });
  $('.update-confirm').on('click', function (event) {
    event.preventDefault();
    swal({
        title: 'Você tem certeza?',
        text: 'Avalie se informou que não é um robô e \n que todos os campos foram preenchidos!',
        icon: 'warning',
        buttons: ["Cancelar", "Sim"],
        closeOnClickOutside: false,
        dangerMode: false,
    }).then(function(value) {
        if (value) {
            $("#updateForm").submit();
        }
    });
});
});

