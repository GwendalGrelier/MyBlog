$(document).ready(function () {  
    $(".description").each(function () {
        var description = $(this).html();
        description = description.split(" ");
        if (description.length > 50) {
            $(this).siblings("p.showMoreBtn").removeClass("hiddenBtn");
        } else {
            $(this).removeClass("hiddenContent");
        }   
    });

    
    $(".showMoreBtn").click(function() {
       
        if ($(this).siblings("p.description").hasClass('hidenContent')) {
            $("p.description").addClass("hidenContent");
            $(this).siblings("p.description").removeClass("hidenContent");
        } else {
            $("p.description").addClass("hidenContent");
        }

    })
});