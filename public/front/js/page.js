// Document Ready
$(document).ready(function () {
});


// Start Equal Height
equalheight = function (container) {

    var currentTallest = 0,
        currentRowStart = 0,
        rowDivs = new Array(),
        $el,
        topPosition = 0;
    $(container).each(function () {

        $el = $(this);
        $($el).height('auto')
        topPostion = $el.position().top;

        if (currentRowStart != topPostion) {
            for (currentDiv = 0; currentDiv < rowDivs.length; currentDiv++) {
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
        for (currentDiv = 0; currentDiv < rowDivs.length; currentDiv++) {
            rowDivs[currentDiv].height(currentTallest);
        }
    });
}


// Window Resize
$(window).on("resize", function () {
    equalheight('class-name');
}).resize();


// Window Load
$(window).load(function (evt) {
    equalheight('class-name');
});


$('.selec2-fld').select2({
    width:100+'%',
    minimumResultsForSearch:6
});




(function() {
    "use strict";

    var toggles = document.querySelectorAll(".c-hamburger");

    for (var i = toggles.length - 1; i >= 0; i--) {
        var toggle = toggles[i];
        toggleHandler(toggle);
    };
    function toggleHandler(toggle) {
        toggle.addEventListener("click", function(e) {
            e.preventDefault();
            (this.classList.contains("is-active") === true) ? this.classList.remove("is-active"): this.classList.add("is-active");
        });
    }

})();

$(document).ready(function($){
    // Get current path and find target link
    var path = window.location.pathname.split("/").pop();
            // alert(path);
    // Account for home page with empty path
    if ( path == '' ) {
        path = 'front';
    }
    var target = $('.nav-links a[href="'+path+'"]');
    // Add active class to target link
    target.parent('li').addClass('current-page');
});

// Start mobile Menu Show Hide
$(".mob-menu-click").click(function () {
    $('header .header-tbl .menu-section').toggleClass('menu-section-show');
    $('body').toggleClass('menu-section-overflow');
});



$(window).on('load resize', function(){
   if($(window).width() > 1024){
       $('.member-scroll').niceScroll({horizrailenabled:false});
       $('.nice-scroll').niceScroll({horizrailenabled:false});
   }
});

//Start For Header Fixed
document.onscroll = function () {
    if ($(window).scrollTop() >= $("header").height() + 10) {
        $('header').addClass('header-is-sticky');
    } else {
        $('header').removeClass('header-is-sticky');
    }
};
//Start For Header Fixed

/*******Footer Height*********/
$(document).ready(function(){
    var winh = $(window).outerHeight() - $('footer').height();
    $(".content-wrapper").css('min-height', winh - 100+'px');
});
/*******Footer Height close*********/

/*************************Login Register Forgot*********************************/
function showRegisterForm(){
    $('.login-box').slideUp('300',function(){
        $('.register-box').slideDown('300');
        $('.forgot-box').slideUp('300');
    });
    $('.error').removeClass('alert alert-danger').html('');

}
function showLoginForm(){

    $('.register-box').slideUp('300',function(){
        $('.login-box').slideDown('300');
        $('.forgot-box').slideUp('300');
    });
    //$('').slideUp('fast');
    $('.error').removeClass('alert alert-danger').html('');
}
function showForgetForm(){

    $('.login-box').slideUp('300',function(){
        $('.forgot-box').slideDown('300');
    });
    //$('').slideUp('fast');
    $('.error').removeClass('alert alert-danger').html('');
}
function openLoginModal(){
    showLoginForm();
    setTimeout(function(){
        $('#loginModal').modal('show');
    }, 230);
}
/*************************Login Register Forgot close*********************************/
$(document).ready(function() {
    $('.user-list').click(function (event) {
        event.stopPropagation();
        $(".user-list-menu").slideToggle("fast");
        $(this).toggleClass('active');
    });
    $(".user-list-menu").on("click", function (event) {
        event.stopPropagation();
    });
    $(document).on("click", function () {
        $(".user-list-menu").fadeOut();
        $('.user-list').removeClass('active');
    });
});
