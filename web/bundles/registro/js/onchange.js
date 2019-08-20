var $field = $("#form_statuses");

$(document).ready(function(){

    $field.change(function(){

        var $form = $(this).closest('form');
        // Simulate form data, but only include the selected field value.
        var data = {};
        data[$field.attr("name")] = $field.val();
        // Submit data via AJAX to the form's action path.

        $('#form_status').attr('readonly', true);

        if ($field.val()=='Otro') {
            $.ajax({
                url: $form.attr('action'),
                type: $form.attr('method'),
                data: data,
                success: function (html) {

                    $("#form_status").replaceWith(
                        // ... with the returned one from the AJAX response.
                        $(html).find("#form_status")
                        //$("select:first").replaceWith("Hello world!"
                    );
                    $('#form_status').attr('readonly', false);
                    // Position field now displays the appropriate positions.
                }
            });
        }

    });

});


$('select').on('change', function() {
    if (this.value == "Estudiante de licenciatura" || this.value == "Estudiante de posgrado" ){
        alert( "Es necesario que envíes tu historial académico y la información de un profesor quien haya accedido a escribir una referencia de apoyo." );
    }
});

