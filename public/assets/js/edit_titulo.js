$('.combobox').combobox();
$(".numeric").numeric({ decimal : ",",  negative : false, scale: 2 });
$( ".vlrface").keyup(function() {

    //fazendo a soma de acordo c a digitacao no teclado
    var sum = 0;
    var y = document.getElementsByClassName('vlrface');
    for(var i = 0, len = y.length; i < len; i++) {
        var valor = y[i].value;
        valor = valor.toString();

        if(valor == '')
        {
            valor = 0;
        }
        
        valor = valor.toString();
        valor = valor.replace(',', '.');  
       
        sum = parseFloat(valor) + sum;
        

      sum = Math.round((sum + Number.EPSILON) * 100) / 100
    }

    //passando o valor pro span
    var span = document.getElementById("vlrfacemod");
    var valorspan = sum;
    valorspan = valorspan.toString();
    valorspan = valorspan.replace('.', ',');  
    span.textContent = ' R$ ' +valorspan;
    
    //passando a nova soma para o element hidden 
    document.getElementById("mod_totalvlrface").value = sum;
  });