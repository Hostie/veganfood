$(document).ready(function(){


    function getComment(mealId){

        var getCommentAjax = jQuery.ajax({
            
            type: 'GET',
            url: window.origin + /rate/getAllRate/mealId,
            dataType: 'JSON',
    
            success: function(code_html, statut) {
                console.log("yes");
            },
    
            error: function(resultat, statut, erreur) {
                console.log("no");
            },
            complete: function(resultat, statut) {
                console.log(resultat.responseJSON);
            }
        });
    }
})