
$(document).ready(function(){

	$( '.categoria' ).on('click',function(){
		var origem=$(this).attr('origem');
		//console.log(origem);
        var currentCount = $('fieldset[id='+origem+'] > fieldset').length;
        var template = $('fieldset[id="'+origem+'"] > span').data('template');
       
        
        template = template.replace(/__index__/g, currentCount);

        $('fieldset[id="'+origem+'"]').append(template);

        
        $( '.removeform' ).on('click',function(){
        	//console.log(this);
        	var elemento = $(this).parent();
            $(elemento).remove();
        
        });
        
        return false;
    });
	
    $( '.removeform' ).on('click',function(){
    //	console.log(this);
    	var elemento = $(this).parent();
        $(elemento).remove();
    
    });
    
    $( "#sortable" ).sortable();
	    
});


