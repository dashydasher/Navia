if ($.cookie('my_name') !== 'undefined' && $.trim($.cookie('my_name')) != "") {
    $("#my-name-div").text($.cookie('my_name'));
}
