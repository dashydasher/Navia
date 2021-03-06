$("#razlog-radio-osobni").click(function() {
    $("#textboxdiv").show();
});

$('#reasons').on('click', '.ajaxMoodReason', function() {
    $("#textboxdiv").hide();
});

function dohvati_potpis() {
    if ($('input[name=anoniman]')[0].checked) {
        return "";
    }
    return $("#studentIdInput").val();
}

function spremi_komentar() {
    if ($("#commentInput").val()) {
        var serializedData = {
            "comment": $("#commentInput").val(),
            "signature": dohvati_potpis(),
        };
        $.post("./php-api/comment-add.php", serializedData)
            .done(function(data) {
                if (data.success) {
                    $("#objavljeni-komentari").prepend($("<li class='list-group-item list-group-item-success' style='margin: 10px'>").append(data.comment.comment));
                    $("#commentInput").val("");
                } else {
                    alert(data.error);
                }
            });
    }
}

function spremi_pitanje() {
    if ($("#questionInput").val()) {
        var serializedData = {
            "question": $("#questionInput").val(),
            "signature": dohvati_potpis(),
        };
        $.post("./php-api/question-add.php", serializedData)
            .done(function(data) {
                if (data.success) {
                    $("#objavljena-pitanja").prepend($("<li class='list-group-item list-group-item-success' style='margin: 10px'>").append(data.question.question));
                    $("#questionInput").val("");
                } else {
                    alert(data.error);
                }
            });
    }
}

function spremi_razlog() {
  var rasoplozenje = $('input[name=emotion]:checked').val();
  var razlog = $('input[name=razlog]:checked').val();
  var razlog_text = $("#razlog_text").val();

  if (!razlog || (razlog == "personal" && !razlog_text)) {
      alert("Molimo unesite razlog");
  } else {
      ajax_promijeni_raspolozenje(rasoplozenje, razlog, razlog_text);
  }
}

$("#komentar-submit").on("click", function() {
    spremi_komentar();
});

$("#pitanje-submit").on("click", function() {
    spremi_pitanje();
});

$("#razlog-submit").on("click", function() {
    spremi_razlog();
});

var input1 = document.getElementById("questionInput");
input1.addEventListener("keyup", function(event) {
  event.preventDefault();
  if (event.keyCode === 13) {
      spremi_pitanje();
  }
});

var input2 = document.getElementById("commentInput");
input2.addEventListener("keyup", function(event) {
  event.preventDefault();
  if (event.keyCode === 13) {
      spremi_komentar();
  }
});

var input3 = document.getElementById("razlog_text");
input3.addEventListener("keyup", function(event) {
  event.preventDefault();
  if (event.keyCode === 13) {
      spremi_razlog();
  }
});

/*
data-type = 1 --> pozitivni razlog
data-type = 0 --> negativni razlog
rasoplozenje = 1 --> sretno
rasoplozenje = 2 --> neutralno
rasoplozenje = 3 --> tužno
*/
function filtriraj_razloge_po_raspolozenju(rasoplozenje) {
    if (rasoplozenje != trenutno_rasoplozenje) {
        if (rasoplozenje > trenutno_rasoplozenje) {
            $('.ajaxMoodReason').hide().filter(function () {
                return $(this).data('type') == 0;
            }).show();
        } else {
            $('.ajaxMoodReason').hide().filter(function () {
                return $(this).data('type') == 1;
            }).show();
        }
        $('input:radio[name=emotion]').filter("[value=" + rasoplozenje + "]").prop('checked', true);
        $('#reasons').show();
    } else {
        $('#reasons').hide();
    }
    $('input[name=razlog]:checked').prop('checked', false);
    $("#textboxdiv").hide();
}

$('#emoticons').on('click', '.btn-success', function() {
    filtriraj_razloge_po_raspolozenju(1);
});

$('#emoticons').on('click', '.btn-warning', function() {
    filtriraj_razloge_po_raspolozenju(2);
});

$('#emoticons').on('click', '.btn-danger', function() {
    filtriraj_razloge_po_raspolozenju(3);
});

function promijeni_boju_i_tekst(boja, tekst) {
    $('.current-emotion').css({
        background: boja,
    });
    $("#current-emotion-text").text(tekst);
}

function promijeni_trenutno_raspolozenje(rasoplozenje) {
    switch (rasoplozenje) {
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

function ajax_promijeni_raspolozenje(rasoplozenje, razlog, razlog_text) {
    $("#razlog-submit").prop("disabled", true);
    var serializedData = {
        "signature": dohvati_potpis(),
        "mood_option_id": rasoplozenje,
        "reason_id": razlog,
        "personal_reason": razlog_text,
    };
    $.post("./php-api/mood-add.php", serializedData)
        .done(function(data) {
            if (data.success) {
                promijeni_trenutno_raspolozenje(data.mood.mood_option_id);
                $('#reasons').hide();
                $('input[name=emotion]:checked').prop('checked', false);
                $('input[name=razlog]:checked').prop('checked', false);
                $("#razlog_text").val("");
                trenutno_rasoplozenje = data.mood.mood_option_id;
            } else {
                alert(data.error);
            }
        })
        .always(function() {
            $("#razlog-submit").prop("disabled", false);
        });
}
