$("#btnRazlozi").click(
function() {
    $("#textboxdiv").toggleClass("hide");
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

function promijeni_gumb_za_aktivaciju(tr, button, aktiviran) {
    // ovo prvo se može obrisat
    tr.attr('data-activated', aktiviran);
    tr.data('activated', aktiviran);
    if (aktiviran == 1) {
        button.text("Deaktiviraj");
    } else {
        button.text("Aktiviraj");
    }
}

$('#teacher-rooms').on('click', '.ajaxActivateRoom', function () {
    var tr = $(this).closest('tr');
    var button = $(this).closest('button');
    var activated = tr.data('activated');
    if (activated == "1") {
        if (!confirm("Deaktivirati sobu?")) {
            return false;
        }
    } else {
        if (!confirm("Aktivirati sobu?")) {
            return false;
        }
    }
    var id = tr.data('id');
    if (id) {
        var serializedData = {
            "room-id": id,
            "activate": (activated + 1) % 2,
        };
        $.post("./php-api/room-activate.php", serializedData)
            .done(function(data) {
                if (data.success) {
                    promijeni_gumb_za_aktivaciju(tr, button, data.active);
                } else {
                    alert(data.error);
                }
            });
    }
});
