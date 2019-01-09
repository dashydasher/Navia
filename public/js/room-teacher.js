var ajaxInProgress = false;
var request;
var pause = false;
var last_timestamp;
var hidden, visibilityChange;

window.onunload = function() {
    if (typeof request !== 'undefined') {
        request.abort();
    }
}

if (typeof document.hidden !== "undefined") { // Opera 12.10 and Firefox 18 and later support
    hidden = "hidden";
    visibilityChange = "visibilitychange";
} else if (typeof document.msHidden !== "undefined") {
    hidden = "msHidden";
    visibilityChange = "msvisibilitychange";
} else if (typeof document.webkitHidden !== "undefined") {
    hidden = "webkitHidden";
    visibilityChange = "webkitvisibilitychange";
}

function handleVisibilityChange() {
    if (document[hidden]) {
        pause = true;
    } else {
        pause = false;
        poll_again(last_timestamp);
    }
}

if (typeof document.addEventListener === "undefined" || hidden === undefined) {
    alert("This webpage requires a browser, such as Google Chrome or Firefox, that supports the Page Visibility API.");
} else {
    document.addEventListener(visibilityChange, handleVisibilityChange, false);
}


function oznaci_neprocitane() {
    $("#komentari > li[data-seen='0'], #pitanja > li[data-seen='0']").each(function() {
        // flashaj lol
        $(this).delay(100).fadeIn(350).fadeOut(350).fadeIn(350).fadeOut(350).fadeIn(350).fadeOut(350).fadeIn(350);
        $(this).attr('data-seen', "1");
        // ovo se može obrisat valjda
        $(this).data('seen', "1");
    });
}

/*
ovdje se oznacuju neprocitani jer se to izvodi tek kad se profesor vrati na taj tab (ako je bio odsutan)
*/
function poll_again(timestamp) {
    if (!pause && !ajaxInProgress) {
        oznaci_neprocitane();
        request = poll(timestamp);
    }
}

function get_mood_icon_by_id(mood) {
    var element_string = '<i data-id="' + mood.id + '"';

    switch (mood.mood_option_id) {
        case 1:
            element_string += ' class="fa fa-smile-o fa-1x" style="color: #00ff00;"';
            break;
        case 2:
            element_string += ' class="fa fa-meh-o fa-1x" style="color: #ff7f00;"';
            break;
        case 3:
            element_string += ' class="fa fa-frown-o fa-1x" style="color: #ff0000;"';
            break;
    }
    element_string += ' title="' + mood.signature + ': ';

    if (mood.mood_reason_id) {
        element_string += mood.mood_reason;
    } else {
        element_string += mood.personal_reason;
    }
    element_string += '"></i>';
    return element_string;
}

function dodaj_komentare(komentari) {
    for (var i = 0, len = komentari.length; i < len; i++) {
        $("#komentari")
            .prepend($("<li data-seen='0' data-id='" + komentari[i].id + "' class='list-group-item list-group-item-success' style='margin: 10px'>")
                .append(komentari[i].signature + ": " + komentari[i].comment)
                .append($('<button class="btn btn-default btn-md ajaxRemoveComment btn-danger" style="float: right; margin-top: 4px;">')
                    .append($('<i class="fa fa-times" aria-hidden="true">')))
            );
    }
}

function dodaj_pitanja(pitanja) {
    for (var i = 0, len = pitanja.length; i < len; i++) {
        $("#pitanja")
            .prepend($("<li data-seen='0' data-id='" + pitanja[i].id + "' class='list-group-item list-group-item-danger' style='margin: 10px'>")
                .append(pitanja[i].signature + ": " + pitanja[i].question)
                .append($('<button class="btn btn-default btn-md ajaxRemoveQuestion btn-danger" style="float: right; margin-top: 4px;">')
                    .append($('<i class="fa fa-times" aria-hidden="true">')))
            );
    }
}

function dodaj_rasposlozenja(moods) {
    for (var i = 0, len = moods.length; i < len; i++) {
        var mood = moods[i];
        if (mood.parent_mood_id) {
            $('#raspolozenja i[data-id="' + mood.parent_mood_id + '"]').remove();
        }
        $("#raspolozenja").append(get_mood_icon_by_id(mood));
    }
}


function poll(timestamp) {
    ajaxInProgress = true;

    last_timestamp = timestamp;
    var queryString = {
        'timestamp': timestamp
    };
    return $.get("php-api/long-polling.php", queryString)
        .done(function(data) {
            if (data.success) {
                dodaj_komentare(data.comments);
                dodaj_pitanja(data.questions);
                dodaj_rasposlozenja(data.moods);
                ajaxInProgress = false;
                last_timestamp = data.timestamp;
                poll_again(data.timestamp);
            } else {
                ajaxInProgress = false;
                poll_again(timestamp);
            }
        })
        .fail(function() {
            ajaxInProgress = false;
            poll_again(timestamp);
        })
}

$("document").ready(function() {
    poll_again();
});

$('#pitanja').on('click', '.ajaxRemoveQuestion', function() {
    if (!confirm("Obrisati zapis?")) {
        return false;
    }
    var li = $(this).closest('li');
    var id = li.data('id');
    if (id) {
        var serializedData = {
            "question_id": id,
        };
        $.post("./php-api/question-remove.php", serializedData)
            .done(function(data) {
                if (data.success) {
                    li.remove();
                } else {
                    alert(data.error);
                }
            });
    }
});

$('#komentari').on('click', '.ajaxRemoveComment', function() {
    if (!confirm("Obrisati zapis?")) {
        return false;
    }
    var li = $(this).closest('li');
    var id = li.data('id');
    if (id) {
        var serializedData = {
            "comment_id": id,
        };
        $.post("./php-api/comment-remove.php", serializedData)
            .done(function(data) {
                if (data.success) {
                    li.remove();
                } else {
                    alert(data.error);
                }
            });
    }
});
