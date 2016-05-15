$(document).ready(function(){

        $(".formationRow").click(function() {
            window.document.location = $(this).data("href");
        });

        $(".formationvRow").click(function() {
            $($(this).data("fa")).slideToggle();
        });

        $(".acceptbutton").on("click", function(e){
            e.stopPropagation();

            fid = $(this).data("f");
            aid = $(this).data("a");
            rmApplicant(fid,aid);
            addIntraining(fid,aid);
            s = ".f"+fid+"a"+aid;
            $(s).empty();
            $(s).hide();

        });

        $(".refusebutton").on("click", function(e){
            e.stopPropagation();
            fid = $(this).data("f");
            aid = $(this).data("a");
            rmApplicant(fid,aid);
            s = ".f"+fid+"a"+aid;
            $(s).empty();
            $(s).hide();

        });



});


function rmApplicant(f,a){
    $.ajax({
        url: "http://localhost:8000/formations/rmapplicant",
        type: 'POST',
        data: {idform: f, iduser: a},
        dataType: 'json',
        success: function() {
        }
    });
}

function addIntraining(f,a){
    $.ajax({
        url: "http://localhost:8000/formations/addintraining",
        type: 'POST',
        data: {idform: f, iduser: a},
        dataType: 'json',
        success: function() {
        }
    });
}
