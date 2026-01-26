$(function(){
    if($("#confirmed").length)
    {

        if($("#confirmed").is(':checked'))
        {
            $("#BtnSubmit").prop('disabled',false);
        }else{
            $("#BtnSubmit").prop('disabled',true);
        }

        $("#confirmed").change(function(){
            if($(this).is(':checked'))
            {
               $("#BtnSubmit").prop('disabled',false);
            }else{
                $("#BtnSubmit").prop('disabled',true);
            }
        });
    }
    if($("#country").length)
    {
        // loadCities($("#country").val());
        //
        // $("#country").change(function(){
        //     // console.log($(this).val());
        //     loadCities($(this).val());
        // });
    }

});
//
// function loadCities(country_id)
// {
//     window.axios.get('/api/cities/'+country_id).then(({data})=>
//     {
//         console.log(data);
//         if(data.success)
//         {
//             $("#city").find('option')
//                 .remove()
//                 .end();
//             // $("#city").append($("<option>",
//             //     {
//             //         value: -1,
//             //         text:"Todos los Estados"
//             //     }
//             // ));
//             $.each(data.result,function(index,value){
//                 $("#city").append($("<option>",
//                     {
//                         value: value.id,
//                         text:value.name
//                     }
//                 ));
//             });
//             $('#city option').each(function() {
//                 if($(this).val() == $("#country").data("city")) {
//                     $(this).prop("selected", true);
//                 }
//             });
//             // this.cities = data.result;
//
//         }else{
//             alert("Error no tiene estados");
//         }
//     }).then(()=>{
//         $("#city").selectpicker("refresh");
//
//     });
// }
