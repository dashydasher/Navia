$(document).ready(function() {
    $('#teacher-rooms').DataTable({
        "dom":' <"search"f><"top"l>rt<"bottom"ip>',
        "order": [[ 2, 'desc' ]],
        "columns": [
            null,
            null,
            null,
            { "orderable": false },
            { "orderable": false },
        ],
        "lengthMenu": [[10, 15, 25, 50, 100], [10, 15, 25, 50, 100]],
        "language": {
            "lengthMenu": "Prikaži _MENU_ rezultata po stranici",
            "zeroRecords": "Nema rezultata",
            "info": "Prikazujem _START_ do _END_ (_TOTAL_ ukupno)",
            "infoEmpty": "Nema rezultata",
            "infoFiltered": "(filtrirano iz _MAX_ rezultata)",
            "search": "Pretraži:",
            "paginate": {
                "first":      "Prva",
                "last":       "Zadnja",
                "next":       "Sljedeća",
                "previous":   "Prethodna"
            }
        },
    });
});

$("#btnRazlozi").click(function() {
    $("#textboxdiv").toggleClass("hide");
});

function provjeri_je_li_razlog_prazan(razlog) {
    if (!razlog) {
        alert("Molimo unesite razlog promjene raspoloženja");
        return true;
    }
    return false;
}

$("#dodajPoz").on("click", function() {
    var razlog = $("#tbPoz1").val();

    if (!provjeri_je_li_razlog_prazan(razlog)) {
        var serializedData = {
            "mood-reason": razlog,
            "type": 1,
        };
        $.post("./php-api/mood-reason-add.php", serializedData)
            .done(function(data) {
                if (data.success) {
                    $("#pozitivni-komentari")
                        .append($('<li data-id="' + data.mood_reason.id + '" class="alert alert-success">')
                            .append(data.mood_reason.reason)
                            .append($('<button class="btn btn-default btn-sm ajaxRemoveReason btn-danger">')
                                .append($('<i class="fa fa-times" aria-hidden="true">'))));
                    $("#tbPoz1").val("");
                } else {
                    alert(data.error);
                }
            });
    }

});

$("#dodajNeg").on("click", function() {
    var razlog = $("#tbNeg1").val();

    if (!provjeri_je_li_razlog_prazan(razlog)) {
        var serializedData = {
            "mood-reason": razlog,
            "type": 0,
        };
        $.post("./php-api/mood-reason-add.php", serializedData)
            .done(function(data) {
                if (data.success) {
                    $("#negativni-komentari")
                        .append($('<li data-id="' + data.mood_reason.id + '" class="alert alert-danger">')
                            .append(data.mood_reason.reason)
                            .append($('<button class="btn btn-default btn-sm ajaxRemoveReason btn-danger">')
                                .append($('<i class="fa fa-times" aria-hidden="true">'))));
                    $("#tbNeg1").val("");
                } else {
                    alert(data.error);
                }
            });
    }
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
