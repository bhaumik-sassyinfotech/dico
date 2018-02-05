$(document).ready(function(){
    
 
    
			$("#image").change(function(){
		        readImageData(this);
		    });
});

		function readImageData(imgData){
			if (imgData.files && imgData.files[0]) {
	            var readerObj = new FileReader();
	            
	            readerObj.onload = function (element) {
	                $('#preview_img').attr('src', element.target.result);
	            }
	            
	            readerObj.readAsDataURL(imgData.files[0]);
	        }
		}

$(document).ready(function(){

$('.profile-slider').owlCarousel({
    loop:true,
    margin:40,
    autoplay: true,
    autoPlaySpeed: 1000,
    autoPlayTimeout: 1000,
    autoplayHoverPause: true,
    responsiveClass:true,
    autoHeight:true,
    responsive:{
        0:{
            items:1,
            nav:true
        },
        600:{
            items:1,
            nav:false
        },
        1000:{
            items:3,
            nav:true,
            loop:false
        }
    }
})

});

$(function(){
    $('.get-nicer').slimScroll({
        height: '350px'
    });
});

$(document).ready(function(){

/*    $(".get-nicer").niceScroll({
        cursorcolor: "#fff",
        cursoropacitymin: 0.3,
        background: "#969696",
        cursorborder: "0",
        autohidemode: false,
        cursorminheight: 30
    }); */
    
$('.post-slider').owlCarousel({
    loop:true,
    margin:40,
    autoplay: true,
    autoPlaySpeed: 1000,
    autoPlayTimeout: 1000,
    autoplayHoverPause: true,
    responsiveClass:true,
    autoHeight:true,
    responsive:{
        0:{
            items:1,
            nav:true
        },
        600:{
            items:1,
            nav:false
        },
        1000:{
            items:3,
            nav:true,
            loop:false
        }
    }
})

  /*
	upload images with title
    */ document.getElementById("uploadBtn").onchange = function () {
                document.getElementById("uploadFile").value = this.value;
            };
});

/*

equalheight = function(container){

var currentTallest = 0,
     currentRowStart = 0,
     rowDivs = new Array(),
     $el,
     topPosition = 0;
 $(container).each(function() {

   $el = $(this);
   $($el).height('auto')
   topPostion = $el.position().top;

   if (currentRowStart != topPostion) {
     for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
       rowDivs[currentDiv].height(currentTallest);
     }
     rowDivs.length = 0; // empty the array
     currentRowStart = topPostion;
     currentTallest = $el.height();
     rowDivs.push($el);
   } else {
     rowDivs.push($el);
     currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
  }
   for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
     rowDivs[currentDiv].height(currentTallest);
   }
 });
}

$(window).load(function() {
  equalheight('.equal-height-wrap .panel-body');
});




$(window).resize(function(){
  equalheight('.equal-height-wrap .panel-body');
      
});

function tabchange()
{
	setTimeout(function(){
		//$(window).trigger('resize');
		equalheight('.equal-height-wrap .panel-body');
	},100);
	  //
}
*/
