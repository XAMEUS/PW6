$(document).ready(function(){

    var url = window.location.href;
    var url = url.split("/")
    var idform_p = url[url.length-1]

    applybuttonshow(idform_p);

    $(".apply").on("click","#applybutton", function() {
        $.ajax({
            url: "http://localhost:8000/formations/joinformation",
            type: 'POST',
            data: {idform: idform_p},
            dataType: 'json',
            success: function() {
                applybuttonshow(idform_p);
            }
        });
    });

});

function applybuttonshow(idform_p){
    $.ajax({
        url: "http://localhost:8000/formationapplicant",
        type: 'POST',
        data: {idform: idform_p},
        dataType: 'json',
        success: function(reponse) {
            if(reponse['data']==1){
                $(".apply").html('<p>Already applied for this formation</p>');
            }else if(reponse['data']==2){
                $(".apply").html('<p>Already in this formation</p>');
            }else{
                $(".apply").html('<button id="applybutton" class="btn btn-lg btn-primary">Apply for this formation</button>');
            }
        }
    });
}
