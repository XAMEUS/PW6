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
                        var newTR = document.createElement('tr');
                        newTR.innerHTML = '<td>'+element.lastname+'</td><td>'+element.firstname+'</td><td>'+element.email+'</td><td>'+element.username+'</td>';
                        newTR.onclick = function() {
                            window.document.location = $("#tbody").data("href")+"/"+element.id;
                        }
                        $("#tbody").append(newTR);

                    });
                }
            });
        }
