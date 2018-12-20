$("#komentar-submit").on("click", function() {
    var serializedData = {
        "comment": $("#commentInput").val(),
        "signature": $("#studentIdInput").val(),
    };
    $.post("./php-api/comment-add.php", serializedData)
        .done(function(data) {
            if (data.success) {
                $("#objavljeni-komentari").append( $("<li>").append(data.comment) );
                $("#commentInput").val("");
            } else {
                alert("Neuspješno slanje komentara :(");
            }
        });
});

$("#pitanje-submit").on("click", function() {
    var serializedData = {
        "question": $("#questionInput").val(),
        "signature": $("#studentIdInput").val(),
    };
    $.post("./php-api/question-add.php", serializedData)
        .done(function(data) {
            console.log(data);
            if (data.success) {
                $("#objavljena-pitanja").append( $("<li>").append(data.question) );
                $("#questionInput").val("");
            } else {
                alert("Neuspješno slanje pitanja :(");
            }
        });
});
