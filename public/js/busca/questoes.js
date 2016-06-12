
$(document).ready(function(){

	
	
	var area = $.parseJSON($.Envia_Servidor('', '/questoes/buscaarea') );
 //   console.log(availableTags);
	$( '#area' ).autocomplete({
		source: area
	});
	
	var norma = $.parseJSON($.Envia_Servidor('', '/questoes/buscanorma') );
	 //   console.log(availableTags);
		$( '#normaRegulamentadora' ).autocomplete({
			source: norma
		}); 
		
	var padrao = $.parseJSON($.Envia_Servidor('', '/questoes/buscapadrao') );
	 //   console.log(availableTags);
		$( '#novopadrao' ).autocomplete({
			source: padrao
		});
		//var selecao;
		$( '#sortable' ).sortable({
	        update: function( event, ui ) {
	            var ordem= $('#sortable').sortable('toArray');
	            var x, name, valor, acao;
	           
	            
	            for(x=0;x<ordem.length;x++){//exibir
	            	name = $('#'+ordem[x]+'> #posicao').attr("name");
	            	 $('#'+ordem[x]+'> #posicao').attr("value", x);
	            	 valor =  $('#'+ordem[x]+'> #posicao').attr("value");
	         //   	 console.log(ordem[x], name, valor);
	            	 
	            //	 $.Envia_Servidor('', '/questoes/atualizaposicao');
	            }
	           
	        }
	    });

	    
	    $( '#adicionar' ).on('click',function(){
	        var currentCount = $('#sortable > li').length;
	        
	        var padrao= $('#novopadrao').val();
	        //console.log(selecao);
	        var html = '<li id=\"lista_'+currentCount+'\" class=\"ui-state-default ui-sortable-handle\">';
	        html +='<span class=\"ui-icon ui-icon-arrowthick-2-n-s\"></span>';
	        html += '<input id=\"posicao\" type="hidden" value=\"'+currentCount+'\" name=\"padrao['+currentCount+'][posicao]\">';
	        html +='<input id=\"padrao\" type=\"text\"  value=\"'+padrao+'\" name=\"padrao['+currentCount+'][descricao]\">';
	        html +='<button class=\"btn btn-danger remover\" lista=\"'+currentCount+'\" type=\"button\">Remover</button>';
	        html +='</li>';
	    
	        $('#sortable').append(html);
	        
	        
	        $( '.remover' ).on('click',function(){
	            var lista= $(this).attr('lista');
	         //   console.log(lista);
	            $('#lista_'+lista).remove();
	        
	        });
	    });
	    
	    $( '.remover' ).on('click',function(){
            var lista= $(this).attr('lista');
          //  console.log(lista);
            $('#lista_'+lista).remove();
        
        });
	    

});
