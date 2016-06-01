
$(document).ready(function(){

	//var data = $.parseJSON($.Envia_Servidor('', '/empresa/buscaempresa') );
 //   console.log(availableTags);
	$( '#clienteempresa' ).autocomplete({
		source: '/servicos/getempresa',
		select: function(e, ui) {
            e.preventDefault(); // <--- Prevent the value from being inserted.
            $("#clienteempresa").attr('codigo', ui.item.value);

            $(this).val(ui.item.label);
        }
	});
	

});
