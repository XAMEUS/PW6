$(document).ready(function(){


    $("#recherche").on("change paste keyup", function() {
        affiche_users($(this).val())
    });
});

function affiche_users(recherche_p)
        {
            $.ajax({
                //url: "{{ path('userlist') }}",
                url: "http://localhost:8000/userlist",
                type: 'POST',
                data: {recherche: recherche_p},
                dataType: 'json',
                success: function(reponse) {
                    $("#tbody").empty();
                    $.each(JSON.parse(reponse['data']), function(index, element) {
                        console.log(element);
                        $("#tbody").append('<tr><td>'+element.lastname+'</td><td>'+element.firstname+'</td><td>'+element.email+'</td><td>'+element.username+'</td></tr>');
                    });
                }
            });
        }
