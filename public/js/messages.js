$(function(){
    /**
     * Send MEssage to Owner Product
     * */
    $("#send-email-form").on("submit",function(e){
        e.preventDefault();
        if(confirm("Desea enviar este mensaje?"))
        {
            let form = $(this);
            $(form).find("button").hide();
            let url= $(form).attr("action");
            window.axios.post(url,$(form).serialize()).then((response)=>{
                // Swal.fire({
                //     title: "Éxito",
                //     text: response.data.msg,
                //     icon: "success",
                //     confirmButtonText: 'Aceptar'
                // })
                alert("Su mensaje fue enviado correctamente");
                $(form).find("#message").val("");
                $(form).find("button").show();
            })
        }

    });
});
