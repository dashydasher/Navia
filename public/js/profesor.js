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

$("#dodajPoz").on("click", function() {
    var serializedData = {
        "mood-reason": $("#tbPoz1").val(),
        "type": 1,
    };
    $.post("./php-api/mood-reason-add.php", serializedData)
        .done(function(data) {
            if (data.success) {
                $("#pozitivni-komentari").append( $("<li>").append(data.mood_reason.reason) );
                $("#tbPoz1").val("");
            } else {
                alert("Neuspješno dodavanje pozitivnog razloga promjene raspoloženja :(");
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
                $("#negativni-komentari").append( $("<li>").append(data.mood_reason.reason) );
                $("#tbNeg1").val("");
            } else {
                alert("Neuspješno dodavanje negativnog razloga promjene raspoloženja :(");
            }
        });
});
