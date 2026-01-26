var max_upload = null;
$(function(){
    // console.log("Loaded!!!");
    // calulate_max_photos_upload();
    max_upload = parseInt($(".clone").attr('data-max'));
    // console.log("MAX UPLOAD:",max_upload);

    $(".btn-add-more").click(function(){
        if($(".clones").length >= max_upload)
            return;

            var html = $(".clone").html();
            $(".img_div").after(html);
            calulate_max_photos_upload();
    });

    $("body").on("click",".btn-remove",function(){
        $(this).parents(".control-group").remove();
        calulate_max_photos_upload();
    });


    $("#gallery .photo i.act-delete").click(function(e){
        // console.log("Antes de Borrar:",max_upload);
        if(confirm('Desea eliminiar esta foto?'))
        {
            if(confirm('Desea eliminarla de verdad ?'))
                deletePhoto($(this));
        }

    });
});

function calulate_max_photos_upload() {
    // $("#max-photos-title strong").html($(".clones").length + " de " + $(".clone").data('max'));
}

//Delete Property Photo By PhotoID
function deletePhoto(obj) {
    let productId = $(obj).data("id");
    let key = $(obj).data("key");
    let token = $(obj).data("token");

    console.log("deleting",[productId,key,token]);
    window.axios.post("/photos/delete/"+productId+"/"+key,{
        data: {
            _token:token,
            key:key
        }
    }).then(({data})=>{
        // console.log(data);
            if(data.success)
            {
                $(obj).parent().remove();
                max_upload = 5 - parseInt(data.total); //new value
                // window.reload();
                window.location.reload();
                // console.log("Despues de Borrar:",max_upload);
                // $(".clone").attr("data-max",parseInt(5-(parseInt(data.total))));

            }else{
                alert("Error al borrar");
            }
    });
    // window.axios.get('/photos/'+photo_id)
    //     .then(({data})=>{
    //     if(data.success)
    //     {
    //         $(obj).parent().remove();
    //         max_upload = 5 - parseInt(data.total); //new value
    //         // window.reload();
    //         window.location.reload();
    //         // console.log("Despues de Borrar:",max_upload);
    //         // $(".clone").attr("data-max",parseInt(5-(parseInt(data.total))));
    //
    //     }else{
    //         alert("Error al borrar");
    //     }
    // });
}
