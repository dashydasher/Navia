var ajaxInProgress = false;
var request;
var pause = false;
var last_timestamp;
var hidden, visibilityChange;

window.onunload = function(){
    if(typeof request !=='undefined') {
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

function poll_again(timestamp) {
    if (!pause && !ajaxInProgress) {
        request = poll(timestamp);
    }
}

function azuriraj_potpis(signature) {
    /*
    TODO ovaj "<anonimno>" ne radi !!!
    */
    if (!signature || 0 === signature.length) {
        signature = "<anonimno>";
    }
    return signature;
}

function dodaj_komentare(komentari) {
    for (var i = 0, len = komentari.length; i < len; i++) {
        var $signature = azuriraj_potpis(komentari[i].signature);
        $("#komentari").append( $("<p>").append($signature + ": " + komentari[i].comment) );
    }
}

function dodaj_pitanja(pitanja) {
    for (var i = 0, len = pitanja.length; i < len; i++) {
        var $signature = azuriraj_potpis(pitanja[i].signature);
        $("#pitanja").append( $("<p>").append($signature + ": " + pitanja[i].question) );
    }
}


function poll(timestamp) {
    ajaxInProgress = true;

    last_timestamp = timestamp;
    var queryString = {'timestamp' : timestamp};

    return $.get("php-api/long-polling.php", queryString)
        .done(function(data) {
            if (data.success) {
                dodaj_komentare(data.comments);
                dodaj_pitanja(data.questions);

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

$("document").ready(function () {
    poll_again();
});

$("#razlog-submit").on("click", function(){

var donut_chart = Morris.Donut({
element: 'chart',
data: "neki_data_iz_PHPa"
});

$('#like_form').on('submit', function(event){
event.preventDefault();
var checked = $('input[name=emotion]:checked', '#like_form').val();
if(checked == undefined)
{
alert("Molim odaberite neku od emocija!");
return false;
}
else
{
var form_data = $(this).serialize();
$.ajax({
url:"action.php",
method:"POST",
data:form_data,
dataType:"json",
success:function(data)
{
$('#like_form')[0].reset();
donut_chart.setData(data);
}
});
}
});
});
