"use strict";
const selectSubCategoryDefaultOption = $('#sub_category').data('option');
const urlParams = new URLSearchParams(window.location.search);
const countryID = urlParams.get('country_id');

$(function () {



    if ($("#category").data("child") === -1) {
        $("#sub_category").prop("selectedIndex", 0);
    } else ;

    if ($("#category").data("property") === -1) {
        $("#category").prop("selectedIndex", 1);
    } else ;

    if ($("#country").val() == -1)
        $("#city").parent().hide();

    loadSubCategories($("#category").val());

    $("#category").change(function () {
        loadSubCategories($(this).val());
    });
});

function loadSubCategories(category_id) {
    if (category_id < 1) {
        // console.log('category all');
        $("#sub_category").find('option')
            .remove()
            .end();
        $("#sub_category").empty();
        $("#sub_category").append($("<option>",
            {
                value: '-1',
                text: selectSubCategoryDefaultOption
            }
        ));
        $("#sub_category").prop("selectedIndex", 0);
        return false;
    } else {
        $("#sub_category").parent().show();
    }


    if (category_id > 0) {
        // console.log('category', category_id);
        window.axios.get('/api/category/children/' + category_id)
            .then(({data}) => {
                if (data.success) {
                    $("#sub_category").find('option')
                        .remove()
                        .end();
                    $("#sub_category").append($("<option>",
                        {
                            value: '-1',
                            text: selectSubCategoryDefaultOption
                        }
                    ));

                    $.each(data.result, function (index, value) {
                        // console.log(value);
                        // $("#sub_category").find('<option>').remove();
                        $("#sub_category").append($("<option>",
                            {
                                value: value.id,
                                text: value.name
                            }
                        ));
                    });

                    $('#sub_category option').each(function () {
                        if ($(this).val() == $("#category").data("child")) {

                            $(this).prop("selected", true);
                        }
                    });
                } else {
                    alert("Error no tiene categorías");
                }
            }).then(() => {
            if ($("#category").data("child") === -1)
                $("#sub_category").prop("selectedIndex", 0);
        });
    } else {
        $("#sub_category").find('option')
            .remove()
            .end();
        $("#sub_category").append($("<option>",
            {
                value: '-1',
                text: selectSubCategoryDefaultOption
            }
        ));
        $("#sub_category").prop("selectedIndex", 0);
    }

}
