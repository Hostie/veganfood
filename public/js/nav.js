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

  var commentSection = $('#commentSection' + mealId).is(":visible");
  console.log(commentSection);
  if (commentSection === false){
    $('#commentSection' + mealId).show();
  }
  else {
    $('#commentSection' + mealId).hide();
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
          var commentList = $('#commentList' + mealId);
          commentList.empty();

          for (const element of comments) {
              commentList.append("<div class=\"__comment\"><h1>" + element[2] + "</h1><h3>" + element[3] + "</h3></div>");
          }
          console.log(commentList);

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