/*

 SmoothScroll({
  stepSize: 100,
  // Acceleration
  accelerationDelta : 80,  // 50
  accelerationMax   : 4,   // 3
});
*/

var tlMap = new TimelineLite();
var hammerelmt = false;

var controller = new ScrollMagic.Controller();

$(document).ready(function () {

  //$('body').css('height',3000);

  $( "#menu-toggle" ).on('click', bugerMenuClickEvent);
  //$(window).on('scroll', onScroll);

  //pageInit();
  initBarbaJs();

  



});



/*========================================================*/
/*======================= Page Init ======================*/
/*========================================================*/
function pageInit() {

  controller = controller.destroy(true);
  controller = new ScrollMagic.Controller();

  //var el = document.querySelector('.barba-container');
  if( $('.side-gallery-container').length ){
    var el = document.querySelector('.side-gallery-container');
    SimpleScrollbar.initEl(el);
  }


  if( $('.album-archive-container').length ){

    var el2 = document.querySelector('.album-archive-container');
    SimpleScrollbar.initEl(el2);

    var isTouchDevice = ('ontouchstart' in window || 'onmsgesturechange' in window);
    if ( isTouchDevice ){
        console.log(1);
        if( typeof hammerelmt === 'object' ) hammerelmt.destroy();

        hammerelmt = new Hammer( $('.album-archive-container')[0] );
        
        hammerelmt.on('tap', function(ev) {
          if( !$(ev.target).hasClass('on') && !$(ev.target).parents('.item-link').hasClass('on')){
            $('.item-link').removeClass('on');
            setTimeout(function(){
              $(ev.target).addClass('on');
            }, 200);
          }
        });
        console.log(hammerelmt);
    }else{
      $('.album-archive-container').find('.item-content')
        .on('mouseenter', function(){
          $target = $(this).find('.item-link');
          if( !$target.hasClass('on') ){
            $('.item-link').removeClass('on');;
            $target.addClass('on');
          }
        }).on('mouseleave',function(){
          $('.item-link').removeClass('on');;
        });
    }

    if( $(window).width() < 768 ){

      $('.album-item-wrapper').each(function( i ){
        var $this = this;
        var scene = new ScrollMagic.Scene({
            triggerElement:this,
            //duration: 100,    // the scene should last for a scroll distance of 100px
            triggerHook: 0.5,
            duration: $(this).height(), // hide 10% before exiting view (80% + 10% from bottom)
            offset: 0    // start this scene after scrolling for 50px
        })
        .setClassToggle("on") // add class to reveal
        .on("enter", function (event){
          $($this).find('.item-link').addClass('on');
        })
        .on("leave", function (event){
          $($this).find('.item-link').removeClass('on');
        })
        //.addIndicators() // add indicators (requires plugin)
        .addTo(controller); // assign the scene to the controller

      });
    }

  }

  $('.side-gallery-item').on('click', clickRightGaleryItem);

  /*
  
  $boxes.on('mouseenter', function(){
    $target = $(this).find('.item-link');
    if( !$target.hasClass('on') ){
      $('.item-link').removeClass('on');;
      $target.addClass('on');
    }
    
  });
  */
  //Slider 
  
  if( $('.swiper-container').length ){



  }




  if( $('.swiper-container').length ){
    TweenLite.set('.swiper-container',{opacity:0});
    var mySwiper = new Swiper ('.swiper-container', {
      // Optional parameters
      loop: true,
      effect:'fade',
      speed:1000,
      autoplay: {
        delay: 4000,
      },
      // Navigation arrows
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      on: {
        init: function () {
          TweenLite.to('.swiper-container',1,{
            opacity:1,
            ease: Power1.easeInOut,
          });
        },
      }
    });
  }

}

/*========================================================*/
/*=================== Right Galery click =================*/
/*========================================================*/
function clickRightGaleryItem(){
  var url = $(this).data('url');
  var id = $(this).data('id');
  var $oldImg = $('.image-place').find('.image-place-container');

    if( !$('.image-place').find('#'+id).length ) {
      var $img = $('<div class="image-place-container" id="'+id+'"><img src="'+url+'" alt=""/></div>');
      TweenLite.set($img,{opacity:0, y:20});
      $('.image-place').append($img);
      TweenLite.to($img,1,{
        opacity:1, 
        y:0, 
        ease: Power1.easeInOut
      });
      TweenLite.to('.content-container',0.5,{
        opacity:0,
        ease: Power1.easeInOut,
      });
      $img.on('click',function(){
        closeRightGalery($(this));
        TweenLite.to('.content-container',0.5,{
          opacity:1,
          ease: Power1.easeInOut,
        });
      });
    }
    else{
      TweenLite.to('.content-container',0.5,{
        opacity:1,
        ease: Power1.easeInOut,
      });
    }

    closeRightGalery($oldImg);
}
function closeRightGalery(elmt){
  TweenLite.to(elmt,1,{
    opacity:0,
    ease: Power1.easeInOut,
    onComplete: function(){
      elmt.remove();
    }
  });
}

/*========================================================*/
/*======================= Scroll ====================*/
/*========================================================*/
function onScroll(){

  var scrollTop = $(window).scrollTop();
  if (scrollTop >= 100) {
    $('.header-wrapper').addClass('scrolled');
  } else {
    $('.header-wrapper').removeClass('scrolled');
  } 
}


/*========================================================*/
/*======================= Burger menu ====================*/
/*========================================================*/
function bugerMenuClickEvent(event) {
  if ( !$('.icon-burger').hasClass('open') ){
    $('.icon-burger').addClass('open');
    $('.navigation-wrapper').addClass('open');
  } else {
    closeMenu();
  }
}

function closeMenu(){
  if ( $('.icon-burger').hasClass('open') ){
    $('.navigation-wrapper').removeClass('open');
    $('.icon-burger').removeClass('open');
  }
}

/*========================================================*/
/*======== Barba js Transition manager ===================*/
/*========================================================*/
function initBarbaJs() {

  Barba.Dispatcher.on('linkClicked', barbaLinkClicked);
  Barba.Dispatcher.on('newPageReady', barbaNewPageReady);
  Barba.Dispatcher.on('transitionCompleted', barbaTransitionCompleted);

  var FadeTransition = Barba.BaseTransition.extend({
    start: function() {
      Promise
        .all([this.newContainerLoading, this.fadeOut()])
        .then(this.fadeIn.bind(this));
    },

    fadeOut: function() {
      var deferred = Barba.Utils.deferred();
      /*
      TweenLite.to('.transitionLayer', 1, {
        scaleY:1,
        ease: Power2.easeIn,
        onComplete: function(){
          $(this.oldContainer).css({ opacity: 0 });
          deferred.resolve();
        }
      });
      */
      TweenLite.to(this.oldContainer, 1, {
        opacity: 0,
        ease: Power2.easeOut,
        onComplete: function(){
          deferred.resolve();
        }
      });

      return deferred.promise;

    },

    fadeIn: function() {
      var _this = this;
      var $el = $(this.newContainer);

      var elemt = [];
      var toFade = [];

      if ( $el.find('.album-item-container').length )
        elemt.push( $el.find('.album-item-container') );
      if ( $el.find('.side-gallery-item').length )
        elemt.push( $el.find('.side-gallery-item') );
      if ( $el.find('.content-container h1, .content-container article').length )
        elemt.push( $el.find('.content-container h1, .content-container article') );
      

      elemt.forEach(function(element) {
         TweenLite.set(element,{opacity:0, y:20});
      });

      if ( $el.find('.contact-wrapper').length )
        toFade.push( $el.find('.contact-wrapper') );
      if ( $el.find('.slider-wrapper').length )
        toFade.push( $el.find('.slider-wrapper') );

      toFade.forEach(function(element) {
         TweenLite.set(element,{opacity:0});
      });

      TweenLite.set('.transitionLayer', {scaleY:0} );
      $(this.oldContainer).hide();

      $el.css({
        visibility : 'visible',
        opacity : 0
      });

      $el.animate({ opacity: 1 }, 50, function() {
        toFade.forEach(function(element) {
            TweenMax.staggerTo(element, 1, {
              opacity:1, 
              ease: Power1.easeInOut
            },0.3);

        });
        elemt.forEach(function(element) {
            TweenMax.staggerTo(element, 1, {
              opacity:1, 
              y:0,
              ease: Power1.easeInOut
            },0.3);

        });

        _this.done();
      });
    }
  });

  Barba.Pjax.getTransition = function() {
    /**
     * Here you can use your own logic!
     * For example you can use different Transition based on the current page or link...
     */

    return FadeTransition;
  };




  Barba.Pjax.start();

}

function barbaNewPageReady(currentStatus, oldStatus, container, newPageRawHTML){
  var regexp = /\<body.*\sclass=["'](.+?)["'].*\>/gi,
      match = regexp.exec(newPageRawHTML);
  if(match && match[1])  document.body.setAttribute('class', match[1]);

  var newMenu = $(newPageRawHTML).find('.barbabreadcrumb');
  //console.log(newMenu.html());
  $('.barbabreadcrumb').html(newMenu.html());

  
}

function barbaTransitionCompleted(currentStatus, prevStatus){
  pageInit();
}


function barbaLinkClicked(HTMLElement, MouseEvent){
  closeMenu();
}



