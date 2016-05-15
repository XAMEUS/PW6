$(document).ready(function(){

    myJoursformation();

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

            $.ajax({
                url: "http://localhost:8000/acceptApplicantFormation",
                type: 'POST',
                data: {iduser: aid, idform: fid},
                dataType: 'json',
                success: function(reponse){
                    if(reponse["error"] == 1){
                        alert("Insufficient budget");
                    }else if(reponse["error"] == 2){
                        alert("Applicant doesn't have enough days");
                    }else{
                        alert("ok");
                        myBudgetformation();
                        s = ".f"+fid+"a"+aid;
                        $(s).empty();
                        $(s).hide();
                    }
                }
            });
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

function myJoursformation(){
    $.ajax({
        url: "http://localhost:8000/myJoursformation",
        type: 'POST',
        dataType: 'json',
        success: function(reponse){
            $("#joursformations").html("Jours de formations restant : "+reponse["data"]);
        }
    });
}

function myBudgetformation(){
    $.ajax({
        url: "http://localhost:8000/myBudgetformation",
        type: 'POST',
        dataType: 'json',
        success: function(reponse){
            $("#budgetformations").html("Budget disponible : "+reponse["data"]+"€");
        }
    });
}

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
