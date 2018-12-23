var trenutna_emocija = 2;

$("#razlog-radio-osobni").click(function() {
    $("#textboxdiv").toggleClass("hide");
});

$("#komentar-submit").on("click", function() {
    var serializedData = {
        "comment": $("#commentInput").val(),
        "signature": $("#studentIdInput").val(),
    };
    $.post("./php-api/comment-add.php", serializedData)
        .done(function(data) {
            if (data.success) {
                $("#objavljeni-komentari").append($("<li>").append(data.comment.comment));
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
                $("#objavljena-pitanja").append($("<li>").append(data.question.question));
                $("#questionInput").val("");
            } else {
                alert(data.error);
            }
        });
});

/*
data-type = 1 --> pozitivni razlog
data-type = 0 --> negativni razlog
emocija = 1 --> sretno
emocija = 2 --> neutralno
emocija = 3 --> tužno
*/
function filtriraj_razloge_po_emociji(emocija) {
    if (emocija != trenutna_emocija) {
        trenutna_emocija = emocija;
        
        if (emocija == 1) {
            $('.ajaxMoodReason').hide().filter(function () {
                return $(this).data('type') == 1;
            }).show();
        } else if (emocija == 2) {
            $('.ajaxMoodReason').show();
        } else {
            $('.ajaxMoodReason').hide().filter(function () {
                return $(this).data('type') == 0;
            }).show();
        }
        $('input[name=razlog]:checked').prop('checked', false);
        $('#reasons').show();
    }
}

$('#emoticons').on('click', '.btn-success', function() {
    $('input:radio[name=emotion]').filter("[value=1]").prop('checked', true);
    filtriraj_razloge_po_emociji(1);
});

$('#emoticons').on('click', '.btn-warning', function() {
    $('input:radio[name=emotion]').filter("[value=2]").prop('checked', true);
    filtriraj_razloge_po_emociji(2);
});

$('#emoticons').on('click', '.btn-danger', function() {
    $('input:radio[name=emotion]').filter("[value=3]").prop('checked', true);
    filtriraj_razloge_po_emociji(3);
});

function promijeni_boju_i_tekst(boja, tekst) {
    $('.current-emotion').css({
        background: boja,
    });
    $("#current-emotion-text").text(tekst);
}

function promijeni_trenutnu_emociju(emocija) {
    switch (emocija) {
        case "1":
            promijeni_boju_i_tekst("#5cb85c", "sretno");
            break;
        case "2":
            promijeni_boju_i_tekst("#f0ad4e", "neutralno");
            break;
        case "3":
            promijeni_boju_i_tekst("#d9534f", "loše");
            break;
        default:
            promijeni_boju_i_tekst("#f0ad4e", "neutralno");
    }
}

$("#razlog-submit").on("click", function() {
    var emocija = $('input[name=emotion]:checked').val();
    var razlog = $('input[name=razlog]:checked').val();

    var tekst;
    var uneseno = true;
    if (!emocija) {
        tekst = "Molimo unsite trenutnu emociju";
        uneseno = false;
    } else if (!razlog) {
        tekst = "Molimo unesite razlog";
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

        promijeni_trenutnu_emociju(emocija);
        $('#reasons').hide();




        $('input[name=emotion]:checked').prop('checked', false);
        $('input[name=razlog]:checked').prop('checked', false);
    }
});
