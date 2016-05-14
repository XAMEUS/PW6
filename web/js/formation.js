$(document).ready(function(){

    var url = window.location.href;
    var url = url.split("/")
    var idform_p = url[url.length-1]

    $.ajax({
        url: "http://localhost:8000/formationapplicants",
        type: 'POST',
        data: {idform: idform_p},
        dataType: 'json',
        success: function(reponse) {
            if(reponse['data']==1){
                $(".apply").html('<p>Already applied for this formation</p>');
            }else{
                $(".apply").html('<button id="applybutton" class="btn btn-lg btn-primary">Apply for this formation</button>');
            }
        }
    });

    $(".apply").on("click","#applybutton", function() {
        $.ajax({
            url: "http://localhost:8000/formationaddapplicant",
            type: 'POST',
            data: {idform: idform_p},
            dataType: 'json',
            success: function() {
                $(".apply").html('<p>Already applied for this formation</p>');
            }
        });
    });

});
