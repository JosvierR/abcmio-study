$(function () {

    // $("#navbarSupportedContent").addClass("show");
    $('[data-toggle="tooltip"]').tooltip()

    // $('.summernote').summernote({
    //     height: 300,
    //     tabsize: 2,
    //     followingToolbar: true,
    // });

    if ($('textarea.summernote').length) {

        $('textarea.summernote').summernote({
            height: 300,   //set editable area's height
            // width:500,
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']]
            ]
        });
    }


//    Property Preview image before submit
    $("#picture").change(function () {
        readURL(this);
    });
    if ($("#country_table").length) {
        $("#country_table").DataTable({
            ordering: true,
            pageLength: 100
        });
    }
});

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#img_preview').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}



