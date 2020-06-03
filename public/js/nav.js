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

          commentList.append("<hr>");
          for (const element of comments) {
              commentList.append("<div class=\"__comment\"><h1>" + element[2] + "</h1><h3>" + element[3] + "</h3></div>");
          }
          commentList.append("<hr>");
          console.log(commentList);

      }
  });
};