/*enable `save` button once the page is loaded to ensure that jquery validations are applied.*/
$("#save").prop('disabled',true);
window.onload = function() {
    // console.log(EXTERNAL_SWEARS);

    setTimeout(function () {
        runProfanity();
    } , 10);
};

function runProfanity(){
    jQuery(".profanity").profanityFilter({
            replaceWith:'*',
            externalSwears: '/dico/public/profanity/swearWords.json'
            // externalSwears: EXTERNAL_SWEARS,
        });
}
/*Yajratables default parameters*/
$.extend(true, jQuery.fn.dataTable.defaults, {
    "stateSave": true,
    // "searchDelay": 2000,
});
/*validations*/
jQuery.validator.addMethod("validateName", function (value, element) {
    return this.optional(element) || /^[a-zA-Z\\.'-\s]+$/i.test(value);
}, "Please enter valid name");
jQuery.validator.addMethod("noSpecialChar", function (value, element) {
    return this.optional(element) || /^[a-zA-Z0-9]+$/i.test(value);
}, "No Special Characters");

jQuery.validator.addMethod("checkOnlySpace", function (value, element) {
//        if ( $.trim( $('#myInput').val() ) == '' ){
    if ($.trim(value) == '') {
        return value.indexOf(" ") < 0 && value != "";
    } else
        return true;
}, "Field cannot be left blank.");
jQuery.validator.addMethod("noSpace", function (value, element) {
    if (value != '') {
        return value.indexOf(" ") < 0 && value != "";
    } else
        return true;
}, "Space is not allowed");
jQuery.validator.addMethod("notNegative", function (value, element) {
    if ($.trim(value) != '') {
        if ($.trim(value) <= 0)
            return false;
        else
            return true;
    } else
        return true;
}, "Value should be greater than 0");
jQuery.validator.addMethod("alphabetNumeric", function (value, element) {
    return this.optional(element) || /^[a-z0-9\-\s]+$/i.test(value);
}, "Allow only numbers and letters");
jQuery.validator.addMethod("validateImage", function (value, element) {
    if (value) {
        var fileExtension = ['jpeg', 'jpg', 'png', 'gif', 'bmp'];
        if ($.inArray($(element).val().split('.').pop().toLowerCase(), fileExtension) == -1)
            return false;
        else
            return true;
    } else
        return true;
}, "Please enter valid image type.");
jQuery.validator.addMethod("uploadLimit", function (val, element) {
    if (typeof element.files[0] != 'undefined' && element.files[0] != '') {
        var size = element.files[0].size;
        // checks the file more than 3 MB
        if (size > 3145728) {
            return false;
        }
        else {
            return true;
        }
    } else
        return true;
}, "Please select File size less than 3 MB");
jQuery.validator.addMethod("email_valid", function (value, element) {
    return this.optional(element) || /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/.test(value);
}, "Please enter valid email address format.");
jQuery.validator.addMethod("validName", function (value, element) {
    return this.optional(element) || /^[A-Za-z0-9 _]*[A-Za-z0-9][A-Za-z0-9 _]*$/.test(value);
}, "Only Characters, Numbers and Spaces are Allowed.");


$(document).ready(function ()
{
    /*enable `save` button once the page is loaded to ensure that jquery validations are applied.*/
    
    $("#save").prop('disabled',false);

    /*Allow only one type of post to be checked*/
    $('.post_type').on('change', function() {
        $('.post_type').not(this).prop('checked', false);
    });
    $('.privacy_type').on('change', function() {
        $('.privacy_type').not(this).prop('checked', false);
    });
});
function likePost(id) {
    $.ajax({
        url: SITE_URL+'/like_post/'+id,
        type: 'GET',
        success: function(response) {
            var res = JSON.parse(response);
            var html = "";
            if(res.status == 1) {
                html += '<i class="fa fa-thumbs-up"></i>';
            } else {
                html += '<i class="fa fa-thumbs-o-up"></i>';
            }
            $('#post_like_count').html(res.likecount);
            $('#post_dislike_count').html(res.dislikecount);
            $('#like_post').html(html);
            $('#dislike_post').html('<i class="fa fa-thumbs-o-down"></i>');
        },
        error: function() {
        }
    });
}
function dislikePost(id) {
    $.ajax({
        url: SITE_URL+'/dislike_post/'+id,
        type: 'GET',
        success: function(response) {
            var res = JSON.parse(response);
            var html = "";
            if(res.status == 1) {
                html += '<i class="fa fa-thumbs-down"></i>';
            } else {
                html += '<i class="fa fa-thumbs-o-down"></i>';
            }
            $('#post_like_count').html(res.likecount);
            $('#post_dislike_count').html(res.dislikecount);
            $('#dislike_post').html(html);
            $('#like_post').html('<i class="fa fa-thumbs-o-up"></i>');
        },
        error: function() {
        }
    });
}
function like_post(id) {
    $.ajax({
        url: SITE_URL+'/like_post/'+id,
        type: 'GET',
        success: function(response) {
            var res = JSON.parse(response);
            var html = "";
            if(res.status == 1) {
                html += '<i class="fa fa-thumbs-up"></i>';
            } else {
                html += '<i class="fa fa-thumbs-o-up"></i>';
            }
            $('#post_like_count_'+id).html(res.likecount);
            $('#post_dislike_count_'+id).html(res.dislikecount);
            $('#like_post_'+id).html(html);
            $('#dislike_post_'+id).html('<i class="fa fa-thumbs-o-down"></i>');
        },
        error: function() {
        }
    });
}
function dislike_post(id) {
    $.ajax({
        url: SITE_URL+'/dislike_post/'+id,
        type: 'GET',
        success: function(response) {
            var res = JSON.parse(response);
            var html = "";
            if(res.status == 1) {
                html += '<i class="fa fa-thumbs-down"></i>';
            } else {
                html += '<i class="fa fa-thumbs-o-down"></i>';
            }
            $('#post_like_count_'+id).html(res.likecount);
            $('#post_dislike_count_'+id).html(res.dislikecount);
            $('#dislike_post_'+id).html(html);
            $('#like_post_'+id).html('<i class="fa fa-thumbs-o-up"></i>');
        },
        error: function() {
        }
    });
}

function likeComment(id) {
    $.ajax({
        url: SITE_URL+'/like_comment/'+id,
        type: 'GET',
        success: function(response) {
            var res = JSON.parse(response);
            var html = "";
            if(res.status == 1) {
                html += '<i class="fa fa-thumbs-up"></i>';
            } else {
                html += '<i class="fa fa-thumbs-o-up"></i>';
            }
            $('#comment_like_count_'+id).html(res.likecount);
            $('#comment_dislike_count_'+id).html(res.dislikecount);
            $('#like_comment_'+id).html(html);
            $('#dislike_comment_'+id).html('<i class="fa fa-thumbs-o-down"></i>');
        },
        error: function() {
        }
    });
}
function dislikeComment(id) {
    $.ajax({
        url: SITE_URL+'/dislike_comment/'+id,
        type: 'GET',
        success: function(response) {
            var res = JSON.parse(response);
            var html = "";
            if(res.status == 1) {
                html += '<i class="fa fa-thumbs-down"></i>';
            } else {
                html += '<i class="fa fa-thumbs-o-down"></i>';
            }
            $('#comment_like_count_'+id).html(res.likecount);
            $('#comment_dislike_count_'+id).html(res.dislikecount);
            $('#dislike_comment_'+id).html(html);
            $('#like_comment_'+id).html('<i class="fa fa-thumbs-o-up"></i>');
        },
        error: function() {
        }
    });
}
function cutString(text){
    alert("here");
    var wordsToCut = 55;
    var wordsArray = text.split(" ");
    if(wordsArray.length>wordsToCut){
        var strShort = "";
        for(i = 0; i < wordsToCut; i++){
            strShort += wordsArray[i] + " ";
        }   
        document.write(strShort+"...");
    }else{
        document.write(text);
    }
}
function uploadFile() {
    var file_data = $('#file_upload').prop('files')[0];
    var postId = $('#postId').val();
    var form_data = new FormData();
    var _token = CSRF_TOKEN;
    form_data.append('file_upload', file_data);
    form_data.append('post_id',postId);
    //console.log(form_data);
    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': _token
        }
    });
    $.ajax({
        url: SITE_URL + '/uploadFile',
        type:"post",
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        success: function(res) {
            $('#postAttachment').html(res);
        },
        error: function(e) {
            swal("Error", e, "error");
        }
    });
}

$(document).on('click',"#GoGrid",function(){
    // alert("clicked");
    $("div.hide-table").addClass('none');
});

$(document).on('click',"#GoList",function(){
    // alert("clicked");
    $("div.hide-table").removeClass('none');
});
