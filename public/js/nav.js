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

jQuery(function($) {
  $('.shipping__cart').on('click', function() {
      $('.nav--responsive__none').toggleClass("nav--responsive__active", true).toggleClass("nav--responsive__none", false);
      $('.overlay').toggle();
      setTimeout(function(){
          $('.overlay').addClass("is-open");
      }, 300);
      $('.header-nav-burger').addClass('is-animate');
      $('body').addClass('overflow');
  });
  $('.overlay').on('click', function() {
      $('.nav--responsive__active').toggleClass("nav--responsive__active", false).toggleClass("nav--responsive__none", true);
      $('.overlay').removeClass("is-open");
      setTimeout(function(){
          $('.overlay').toggle();
      }, 300);
      $('.header-nav-burger').removeClass('is-animate');
      $('body').removeClass('overflow');
  });
  
  //var heroHeight;
  var headerHeight;
  var screenHeight;
  //heroHeight = $('.hero__home').height();
  function resize() {
      screenHeight = $(window).innerHeight();
      headerHeight = $('.header__main').innerHeight();
      valueHero = screenHeight - headerHeight;
      $('.hero__home').css('height', valueHero);
  }

  resize();

  $(window).resize(resize);

  // Pour gérer sur plusieurs evênements PLUS PROPRE
  // $(window).on('load,resize', function () {});
})