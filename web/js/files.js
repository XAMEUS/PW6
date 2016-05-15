$(document).ready(function(){



    $(".deletebutton").on("click", function() {
        f = $(this).data("f");
        s = $(this).data("id");
        $.ajax({
            url: "http://localhost:8000/files/delete",
            type: 'POST',
            data: {file: f},
            dataType: 'json',
            success: function(reponse) {
                $(s).empty();
                $(s).hide();
            }
        });

    });

});
