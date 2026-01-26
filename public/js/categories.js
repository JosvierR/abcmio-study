$(function(){
    $(".act-delete button").click(function(e){
        var form = $(this).parent();;
        e.preventDefault();
        if(confirm('Desea eliminiar esta categoría?'))
        {
            if(confirm('Desea eliminarla de verdad ?'))
                form.submit();
        }
    })
});
