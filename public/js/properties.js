$(function()
{
    if($("#country").length)
    {
        // loadCities($("#country").val());
        // $("#country").change(function(){
        //     loadCities($(this).val());
        // });
    }

    if($("#category").length)
    {
        loadSubCategories($("#category").val());

        $("#category").change(function()
        {
            // console.log($(this).val());
            loadSubCategories($(this).val());
        });

    }

    if($('.datepicker').length) {
        $('.datepicker').datepicker({
            minDate:0,
            dateFormat: 'dd/mm/yy'
        });
    }

});


function loadCities(country_id)
{
    window.axios.get('/api/cities/'+country_id).then(({data})=>
    {
        if(data.success)
        {
            $("#city").find('option')
                .remove()
                .end();
            // $("#city").append($("<option>",
            //     {
            //         value: -1,
            //         text:"Todos los Estados"
            //     }
            // ));
            $.each(data.result,function(index,value){
                $("#city").append($("<option>",
                    {
                        value: value.id,
                        text:value.name
                    }
                ));
            });
            $('#city option').each(function() {
                if($(this).val() == $("#country").data("city")) {
                    $(this).prop("selected", true);
                }
            });
            // this.cities = data.result;

        }else{
            alert("Error no tiene ciudades");
        }
    }).then(()=>{
        $("#city").selectpicker("refresh");

    });
}

function loadSubCategories(category_id)
{
    window.axios.get('/api/category/children/'+category_id)
        .then(({data})=>{
            // console.log("Llegaron las sub categorias");
            if(data.success)
            {

                $("#sub_category").find('option')
                    .remove()
                    .end();
                // $("#sub_category").append($("<option>",
                //     {
                //         value: -1,
                //         text:"-Todas las Sub Categorías-"
                //     }
                // ));

                $.each(data.result,function(index,value){
                    // console.log(value);
                    // $("#sub_category").find('<option>').remove();
                    $("#sub_category").append($("<option>",
                        {
                            value: value.id,
                            text:value.name
                        }
                    ));
                });

                $('#sub_category option').each(function() {
                    // console.log($(this).val());
                    if($(this).val() == $("#category").data("child")) {

                        $(this).prop("selected", true);
                    }
                });
                // this.cities = data.result;

            }else{
                alert("Error no tiene categorías");
            }
        }).then(()=>{
        if($("#category").data("child")===-1)
            $("#sub_category").prop("selectedIndex", 1);
        // $("#sub_category").selectpicker("refresh");
    });
}

