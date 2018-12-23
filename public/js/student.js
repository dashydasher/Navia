$("#komentar-submit").on("click", function() {
    var serializedData = {
        "comment": $("#commentInput").val(),
        "signature": $("#studentIdInput").val(),
    };
    $.post("./php-api/comment-add.php", serializedData)
        .done(function(data) {
            if (data.success) {
                $("#objavljeni-komentari").append( $("<li>").append(data.comment.comment) );
                $("#commentInput").val("");
            } else {
                alert(data.error);
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
            if (data.success) {
                $("#objavljena-pitanja").append( $("<li>").append(data.question.question) );
                $("#questionInput").val("");
            } else {
                alert(data.error);
            }
        });
});

$('#emoticons').on('click', '.btn-success', function () {
    $('input:radio[name=emotion]').filter("[value=1]").prop('checked', true);
});

$('#emoticons').on('click', '.btn-warning', function () {
    $('input:radio[name=emotion]').filter("[value=2]").prop('checked', true);
});

$('#emoticons').on('click', '.btn-danger', function () {
    $('input:radio[name=emotion]').filter("[value=3]").prop('checked', true);
});

$("#razlog-submit").on("click", function() {
    var emocija = $('input[name=emotion]:checked').val();
    var razlog = $('input[name=razlog]:checked').val();

    var tekst;
    var uneseno = true;
    if (!emocija) {
        tekst = "Molimo unsite trenutnu emociju";
        uneseno = false;
    } else if (!razlog) {
        tekst = "Molimo unsite razlog";
        uneseno = false;
    }

    if (!uneseno) {
        alert(tekst);
    } else {
        var serializedData = {
            "signature": $("#studentIdInput").val(),
            "emocija": emocija,
            "razlog": razlog,
        };
        console.log(serializedData);




        $('input[name=emotion]:checked').prop('checked', false);
        $('input[name=razlog]:checked').prop('checked', false);
    }
});
