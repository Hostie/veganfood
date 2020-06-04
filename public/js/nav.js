$(document).ready(function() {
  $(document).delegate('.open', 'click', function(event){
    $(this).addClass('oppenned');
    event.stopPropagation();
  })
  $(document).delegate('body', 'click', function(event) {
    $('.open').removeClass('oppenned');
  })
  $(document).delegate('.cls', 'click', function(event){
    $('.open').removeClass('oppenned');
    event.stopPropagation();
  });

});

function displayComment(mealId) {

  var commentSection = $('#commentItem' + mealId).is(":visible");
  console.log(commentSection);
  if (commentSection === false){
    $('#commentItem' + mealId).show();
  }
  else {
    $('#commentItem' + mealId).hide();
  }
};

function getComment(mealId){

  var getCommentAjax = jQuery.ajax({
      
      type: 'GET',
      url: window.origin + '/rate/getAllRate/' + mealId,
      dataType: 'JSON',

      success: function(code_html, statut) {
          console.log("yes");
      },

      error: function(resultat, statut, erreur) {
          console.log("no");
      },
      complete: function(resultat, statut) {
          //console.log(resultat.responseJSON);

          var comments = resultat.responseJSON.commentAndRateArray;

          console.log(comments);
          var commentList = $('#commentList' + mealId);
          commentList.empty();

          if (comments[0] != "Aucun commentaire de disponnible"){
            for (const element of comments) {
              commentList.append("<div class=\"__comment\"><h1>" + element[2] + "</h1><h3>" + element[3] + "</h3></div>");
            }
          }
          else {
            commentList.append("<div class=\"__emptyComment\"><h1>Aucun commentaire pour ce plat.</h1></div>");
          }

      }
  });
};

function AddComment(mealId){
  var comment = $("#__commentInput"+mealId).val();
  console.log(comment);
  var note = $("#__rateInput"+mealId).val();

  if (comment != "" && note != "note"){
    var AddCommentAjax = jQuery.ajax({
      
      type: 'GET',
      url: window.origin + '/rate/add/' + mealId + '/' + comment + '/' + note,
      dataType: 'JSON',

      success: function(code_html, statut) {
        console.log("Your comment has been well added.");
      },

      error: function(resultat, statut, erreur) {
        console.log("You comment hasn't been well added.");
      },
      complete: function(resultat, statut) {
        console.log("AddComment completed.")
      }
    });
  }

  getComment(mealId);

};