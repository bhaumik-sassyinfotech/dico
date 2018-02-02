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
    
    $('.grid-details').slimScroll({
        height: '80px'
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



