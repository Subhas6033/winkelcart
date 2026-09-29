
'use strict';
$(document).on("click", ".delete_row", function(e) {
  //e.preventDefault();
  Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, delete it!'
  }).then((result) => {
    if (result.value) {
      $(this).parent().submit();
    }
  });
  return false;
});
$(document).on("keyup", '.decimal-number', function() {
  var $this = $(this);
  $this.val($this.val().replace(/[^\d.]/g, ''));
});
$(document).on("keyup", '.integer-number', function() {
  var $this = $(this);
  $this.val($this.val().replace(/[^\d]/g, ''));
});
$(document).on("keyup", '.mendatory', function() {
  $(this).css('border', '')
});
$(document).on("change", '.mendatory', function() {
  $(this).css('border', '')
});
$(document).on("focus", '.mendatory', function() {
  $(this).css('border', '')
});
$("document").ready(function() {
  setTimeout(function() {
    $("p.alert").remove();
  }, 3000); // 5 secs

});
// (function ($) {

  
//     $(window).on('load', function () {
//         $(".loader").fadeOut();
//         $("#preloder").delay(200).fadeOut("slow");

      
//         $('.featured__controls li').on('click', function () {
//             $('.featured__controls li').removeClass('active');
//             $(this).addClass('active');
//         });
//         if ($('.featured__filter').length > 0) {
//             var containerEl = document.querySelector('.featured__filter');
//             var mixer = mixitup(containerEl);
//         }
//     });

   
//     $('.set-bg').each(function () {
//         var bg = $(this).data('setbg');
//         $(this).css('background-image', 'url(' + bg + ')');
//     });

//     //Humberger Menu
//     $(".humberger__open").on('click', function () {
//         $(".humberger__menu__wrapper").addClass("show__humberger__menu__wrapper");
//         $(".humberger__menu__overlay").addClass("active");
//         $("body").addClass("over_hid");
//     });

//     $(".humberger__menu__overlay").on('click', function () {
//         $(".humberger__menu__wrapper").removeClass("show__humberger__menu__wrapper");
//         $(".humberger__menu__overlay").removeClass("active");
//         $("body").removeClass("over_hid");
//     });

  
//     $(".mobile-menu").slicknav({
//         prependTo: '#mobile-menu-wrap',
//         allowParentLinks: true
//     });

//     $(".categories__slider").owlCarousel({
//         loop: true,
//         margin: 0,
//         items: 4,
//         dots: false,
//         nav: true,
//         navText: ["<span class='fa fa-angle-left'><span/>", "<span class='fa fa-angle-right'><span/>"],
//         animateOut: 'fadeOut',
//         animateIn: 'fadeIn',
//         smartSpeed: 1200,
//         autoHeight: false,
//         autoplay: true,
//         responsive: {

//             0: {
//                 items: 1,
//             },

//             480: {
//                 items: 2,
//             },

//             768: {
//                 items: 3,
//             },

//             992: {
//                 items: 4,
//             }
//         }
//     });


//     $('.hero__categories__all').on('click', function(){
//         $('.hero__categories ul').slideToggle(400);
//     });

//     $(".latest-product__slider").owlCarousel({
//         loop: true,
//         margin: 0,
//         items: 1,
//         dots: false,
//         nav: true,
//         navText: ["<span class='fa fa-arrow-right'><span/>", "<span class='fa fa-arrow-right'><span/>"],
//         smartSpeed: 1200,
//         autoHeight: false,
//         autoplay: true
//     });

  
//     $(".product__discount__slider").owlCarousel({
//         loop: true,
//         margin: 0,
//         items: 3,
//         dots: true,
//         smartSpeed: 1200,
//         autoHeight: false,
//         autoplay: true,
//         responsive: {

//             320: {
//                 items: 1,
//             },

//             480: {
//                 items: 2,
//             },

//             768: {
//                 items: 2,
//             },

//             992: {
//                 items: 3,
//             }
//         }
//     });

   
//     $(".product__details__pic__slider").owlCarousel({
//         loop: true,
//         margin: 20,
//         items: 4,
//         dots: true,
//         smartSpeed: 1200,
//         autoHeight: false,
//         autoplay: true
//     });

//     var rangeSlider = $(".price-range"),
//         minamount = $("#minamount"),
//         maxamount = $("#maxamount"),
//         minPrice = rangeSlider.data('min'),
//         maxPrice = rangeSlider.data('max');
//     rangeSlider.slider({
//         range: true,
//         min: minPrice,
//         max: maxPrice,
//         values: [minPrice, maxPrice],
//         slide: function (event, ui) {
//             minamount.val('$' + ui.values[0]);
//             maxamount.val('$' + ui.values[1]);
//         }
//     });
//     minamount.val('$' + rangeSlider.slider("values", 0));
//     maxamount.val('$' + rangeSlider.slider("values", 1));

//     $("select").niceSelect();

//     $('.product__details__pic__slider img').on('click', function () {

//         var imgurl = $(this).data('imgbigurl');
//         var bigImg = $('.product__details__pic__item--large').attr('src');
//         if (imgurl != bigImg) {
//             $('.product__details__pic__item--large').attr({
//                 src: imgurl
//             });
//         }
//     });



    

// $('document').ready(function() {
//     // Back to top
//     var backTop = $(".back-to-top");
    
//     $(window).scroll(function() {
//       if($(document).scrollTop() > 400) {
//         backTop.css('visibility', 'visible');
//       }
//       else if($(document).scrollTop() < 400) {
//         backTop.css('visibility', 'hidden');
//       }
//     });
    
//     backTop.click(function() {
//       $('html').animate({
//         scrollTop: 0
//       }, 1000);
//       return false;
//     });
//   });
  

//     var proQty = $('.pro-qty');
//     proQty.prepend('<span class="dec qtybtn">-</span>');
//     proQty.append('<span class="inc qtybtn">+</span>');
//     proQty.on('click', '.qtybtn', function () {
//         var $button = $(this);
//         var oldValue = $button.parent().find('input').val();
//         if ($button.hasClass('inc')) {
//             var newVal = parseFloat(oldValue) + 1;
//         } else {
           
//             if (oldValue > 0) {
//                 var newVal = parseFloat(oldValue) - 1;
//             } else {
//                 newVal = 0;
//             }
//         }
//         $button.parent().find('input').val(newVal);
//     });

// })(jQuery);



// let slideIndex = 0;
// showSlides();


// function nextSlide() {
//   slideIndex++;
//   showSlides();
//   timer = _timer; 
// }

// function prevSlide() {
//   slideIndex--;
//   showSlides();
//   timer = _timer;
// }


// function currentSlide(n) {
//   slideIndex = n - 1;
//   showSlides();
//   timer = _timer;
// }

// function showSlides() {
//   let slides = document.querySelectorAll(".mySlides");
//   let dots = document.querySelectorAll(".dots");

//   if (slideIndex > slides.length - 1) slideIndex = 0;
//   if (slideIndex < 0) slideIndex = slides.length - 1;
  
  
//   slides.forEach((slide) => {
//     slide.style.display = "none";
//   });
  
  
//   slides[slideIndex].style.display = "block";
  
//   dots.forEach((dot) => {
//     dot.classList.remove("active");
//   });
  
//   dots[slideIndex].classList.add("active");
// }


// let timer = 7; // sec
// const _timer = timer;


// setInterval(() => {
//   timer--;

//   if (timer < 1) {
//     nextSlide();
//     timer = _timer; 
//   }
// }, 1000); 



// $(document).ready(function() {
// $('.acc-container .acc:nth-child(1) .acc-head').addClass('active');
// $('.acc-container .acc:nth-child(1) .acc-content').slideDown();
// $('.acc-head').on('click', function() {
//     if($(this).hasClass('active')) {
//       $(this).siblings('.acc-content').slideUp();
//       $(this).removeClass('active');
//     }
//     else {
//       $('.acc-content').slideUp();
//       $('.acc-head').removeClass('active');
//       $(this).siblings('.acc-content').slideToggle();
//       $(this).toggleClass('active');
//     }
// });     
// });



// $('.owl-carousel').owlCarousel({
//   loop: true,
//   margin: 10,
//   nav: true,
//   navText: [
//     "<i class='fa fa-arrow-left'></i>",
//     "<i class='fa fa-arrow-right'></i>"
    
//   ],
//   autoplay: true,
//   autoplayHoverPause: true,
//   responsive: {

//     0: {
//       items: 1
//     },

//     600: {
//       items: 3
//     },

//     1024: {
//       items: 4
//     },

//     1366: {
//       items: 4
//     }

//     }
// })










   



