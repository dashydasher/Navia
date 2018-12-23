$("#btnRazlozi").click(
function() {
    $("#textboxdiv").toggleClass("hide");
    }
);

$("#btnActivate").click(
function() {
  var elem = document.getElementById("btnActivate");
  if (elem.innerHTML=="Aktiviraj") elem.innerHTML = "Deaktiviraj";
  else elem.innerHTML = "Aktiviraj";
    }
);

function dodaj_novi_razlog(data) {
    return $('<li data-id="' + data.mood_reason.id + '" >')
        .append(data.mood_reason.reason)
        .append($('<button class="btn btn-default btn-sm ajaxRemoveReason btn-danger">')
            .append($('<span class="glyphicon glyphicon-trash">')));
}

$("#dodajPoz").on("click", function() {
    var serializedData = {
        "mood-reason": $("#tbPoz1").val(),
        "type": 1,
    };
    $.post("./php-api/mood-reason-add.php", serializedData)
        .done(function(data) {
            if (data.success) {
                $("#pozitivni-komentari").append( dodaj_novi_razlog(data) );
                $("#tbPoz1").val("");
            } else {
                alert(data.error);
            }
        });
});

$("#dodajNeg").on("click", function() {
    var serializedData = {
        "mood-reason": $("#tbNeg1").val(),
        "type": 0,
    };
    $.post("./php-api/mood-reason-add.php", serializedData)
        .done(function(data) {
            if (data.success) {
                $("#negativni-komentari").append( dodaj_novi_razlog(data) );
                $("#tbNeg1").val("");
            } else {
                alert(data.error);
            }
        });
});

$('#textboxdiv').on('click', '.ajaxRemoveReason', function () {
    if (!confirm("Obrisati zapis?")) {
        return false;
    }
    var li = $(this).closest('li');
    var id = li.data('id');
    if (id) {
        var serializedData = {
            "mood-reason-id": id,
        };
        $.post("./php-api/mood-reason-remove.php", serializedData)
            .done(function(data) {
                if (data.success) {
                    li.remove();
                } else {
                    alert(data.error);
                }
            });
    }
});
