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