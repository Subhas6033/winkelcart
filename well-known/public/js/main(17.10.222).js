$("#owl-demo-2").owlCarousel({
    loop: true,
    margin: 10,
    nav: true,
    navText: [
      "<i class='fa fa-arrow-left arr'></i>",
      "<i class='fa fa-arrow-right'></i>"
      
    ],
    autoplay: false,
    autoplayHoverPause: true,
    center: false,
   
    responsive: {
  
      0: {
        items: 1,
       
      },
  
      500:{
        items: 2
  
      },
  
      600: {
        items: 2
      },
  
      1024: {
        items: 3
       
      },
  
      1366: {
        items:3,
       
       
      }
  
      }
  })
  
  

$("#owl-demo-3").owlCarousel({
  loop: true,
  margin: 10,
  nav: true,
  navText: [
    "<i class='fa fa-arrow-left arr'></i>",
    "<i class='fa fa-arrow-right'></i>"
    
  ],
  autoplay: true,
  autoplayHoverPause: true,
  center: false,
  
 
  responsive: {

    0: {
      items:1
    },

    
    400: {
      items:1
    },

    
    500: {
      items:2
    },


    600: {
      items: 2
    },

    1024: {
      items: 3
     
    },

    1366: {
      items:4,
     
     
    }

    }
})




var slider = $('#slider');
var filterButtons = $('.filterButtons');

function flicitySlider() {
  
  slider.flickity({
   
   
    imagesLoaded: true,
    cellAlign: 'left',
    cellSelector: '.flickity',
    pageDots:true,
    prevNextButtons: false,

    
  
   
  });
}

flicitySlider();



slider.parent().find('.slider-next').on('click', function() {
  slider.flickity('next');
});


slider.parent().find('.slider-prev').on('click',function() {
slider.flickity('previous');
});




filterButtons.on( 'click', 'button', function() {
  
  var filterValue = $( this ).attr('data-filter');
  var slide = slider.find('.slide');

  if (filterValue == 'all') {
   
    slide.fadeIn(450);
    slide.addClass('flickity');
  } else {
    
    var active = $('.' + filterValue).fadeIn(450);
   
    slide.addClass('flickity');
    slide.not(active).removeClass('flickity');
    slide.not(active).hide();
  }

  
  slider.flickity('destroy');

  
  flicitySlider();
  
  $('.filterButton').removeClass('active');
  
 
  $(this).addClass('active');
  
});






var date = new Date();
var day = date.getDate();
var month = date.getMonth() + 1;
var year = date.getFullYear();
if (month < 10) month = "0" + month;
if (day < 10) day = "0" + day;
var today = year +"-" + month + "-" + day ;
document.getElementById('theDate').value = today;












$(document).ready(function(){
  $('.customer-logos').slick({
      slidesToShow: 6,
      slidesToScroll: 1,
      autoplay: true,
      autoplaySpeed: 1500,
      arrows: false,
      dots: false,
      pauseOnHover: false,
      responsive: [{
          breakpoint: 768,
          settings: {
              slidesToShow: 4
          }
      }, {
          breakpoint: 520,
          settings: {
              slidesToShow: 3
          }
      }]
  });
});




let slideIndex = 0;
showSlides();


function nextSlide() {
  slideIndex++;
  showSlides();
  timer = _timer; 
}

function prevSlide() {
  slideIndex--;
  showSlides();
  timer = _timer;
}


function currentSlide(n) {
  slideIndex = n - 1;
  showSlides();
  timer = _timer;
}

function showSlides() {
  let slides = document.querySelectorAll(".mySlides");
  let dots = document.querySelectorAll(".dots");

  if (slideIndex > slides.length - 1) slideIndex = 0;
  if (slideIndex < 0) slideIndex = slides.length - 1;
  
  
  slides.forEach((slide) => {
    slide.style.display = "none";
  });
  
  
  slides[slideIndex].style.display = "block";
  
  dots.forEach((dot) => {
    dot.classList.remove("active");
  });
  
  dots[slideIndex].classList.add("active");
}


let timer = 7;
const _timer = timer;


setInterval(() => {
  timer--;

  if (timer < 1) {
    nextSlide();
    timer = _timer; 
  }
}, 500); 




  
window.onload = function () {

 
  const menu_btn = document.querySelector('.hamburger');
  const mobile_menu = document.querySelector('.mobile-nav');
  const mobile_lay = document.querySelector('.humberger__menu__overlay');


   menu_btn.addEventListener('click', function () {
    menu_btn.classList.toggle('is-active');
    mobile_menu.classList.toggle('is-active');
    mobile_lay.classList.toggle('active');
    $('body').toggleClass('over_hid');
      
   
  });
  
}
  



$(".humberger__menu__overlay").on('click', function () {
  $(".hamburger").removeClass("is-active");
      $(".mobile-nav").removeClass("show__humberger__menu__wrapper");
      $(".humberger__menu__overlay").removeClass("active");
      $(".mobile-nav").removeClass("is-active");
      $("body").removeClass("over_hid");
  });


  


const popperButton = document.querySelector("#popper-button");
const popperPopup = document.querySelector("#popper-popup");
const popperSection = document.querySelector("#popper-section");
const popperArrow = document.querySelector("#popper-arrow");

let popperInstance = null;


function createInstance() {
popperInstance = Popper.createPopper(popperButton, popperPopup, {
 placement: "auto", 
 modifiers: [
   {
     name: "offset", 
     options: {
       offset: [0, 8]
     }
   },
   {
     name: "flip", 
     options: {
       allowedAutoPlacements: ["right", "left", "top", "bottom"],
       rootBoundary: "viewport"
     }
   }
 ]
});
}


function destroyInstance() {
if (popperInstance) {
 popperInstance.destroy();
 popperInstance = null;
}
}


function showPopper() {
popperPopup.setAttribute("show-popper", "");
popperArrow.setAttribute("data-popper-arrow", "");
createInstance();
}


function hidePopper() {
popperPopup.removeAttribute("show-popper");
popperArrow.removeAttribute("data-popper-arrow");
destroyInstance();
}


function togglePopper() {
if (popperPopup.hasAttribute("show-popper")) {
 hidePopper();
} else {
 showPopper();
}
}

popperButton.addEventListener("click", function (e) {
e.preventDefault();
togglePopper();
});



let question = document.querySelectorAll(".accordion-item-header");

question.forEach(question => {
  question.addEventListener("click", event => {
    const active = document.querySelector(".accordion-item-header.active");
    if (active && active !== question) {
      active.classList.toggle("active");
      active.nextElementSibling.style.maxHeight = 0;
    }
    question.classList.toggle("active");
    const answer = question.nextElementSibling;
    if (question.classList.contains("active")) {
      answer.style.maxHeight = answer.scrollHeight + "px";
    } else {
      answer.style.maxHeight = 0;
    }
  })
})






