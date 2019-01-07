google.charts.load('current', {
    packages: ['corechart'],
    callback: filtriraj_chart
});

var tablica_pitanja, tablica_komentari;

$("#slider-range").slider({
    min: 0,
    max: razlika_vremena_u_minutama,
    step: step,
    values: [
        razlika_vremena_u_minutama
    ],

    slide: function(e, ui) {
        $('.slider-time').each(function(i) {
            var time_selected = new Date(time_start.getTime() + ui.values[i]*60*1000);

            var year_selected = time_selected.getFullYear();
            var month_selected = time_selected.getMonth() + 1;
            var day_selected = time_selected.getDate();
            var hour_selected = ("00" + time_selected.getHours()).slice(-2);
            var minutes_selected = ("00" + time_selected.getMinutes()).slice(-2);

            var time_formatted = day_selected + "." + month_selected + "." + year_selected + ". " + hour_selected + ":" + minutes_selected;

            $(this).html(time_formatted);

            filtriraj_chart(time_formatted);
            filtriraj_komentare(time_formatted);
            filtriraj_pitanja(time_formatted);
        });
    }
});

function filtriraj_pitanja(vrijeme) {
    /*
    $.fn.dataTable.ext.search.push(
      function(settings, data, dataIndex) {
          console.log(new Date($(tablica_pitanja.row(dataIndex).node()).attr('data-time')));
          //return $(tablica_pitanja.row(dataIndex).node()).attr('data-time') == 5;
        }
    );
    tablica_pitanja.draw();
    */
}

function filtriraj_komentare(vrijeme) {

}

function filtriraj_tablicu(vrijeme) {
    $("#pozRazlozi > table tr:gt(0), #neutrRazlozi > table tr:gt(0), #negRazlozi > table tr:gt(0)").hide().each(function() {
        var id = $(this).data("id");
        if (vrijeme in moods_intervals && id in moods_intervals[vrijeme]) {
            $(this).closest("tr").show();
        }
    });
}

function filtriraj_chart(vrijeme) {
    if (!vrijeme) {
        vrijeme = $("#time-end").text();
    }
    var postoji_pozitivan = false;
    var postoji_neutralan = false;
    var postoji_negativan = false;

    $("#proba tr").each(function(i) {
        var id = $(this).data("id");
        if (vrijeme in moods_intervals && id in moods_intervals[vrijeme]) {
            $(this).data("hidden", "false");
            $(this).attr('data-hidden', "false");

            switch ($(this).text().trim()) {
                case "Sretno":
                    postoji_pozitivan = true;
                    break;
                case "Neutralno":
                    postoji_neutralan = true;
                    break;
                case "Tužno":
                    postoji_negativan = true;
                    break;
            }
        } else {
            $(this).data("hidden", "true");
            $(this).attr('data-hidden', "true");
        }
    });
    drawChart2(postoji_pozitivan, postoji_neutralan, postoji_negativan);
    filtriraj_tablicu(vrijeme);
}

function drawChart2(postoji_pozitivan, postoji_neutralan, postoji_negativan) {
    // Create the data table.

    var data = new google.visualization.DataTable({
        cols: [{
                label: 'Raspolozenja',
                type: 'string'
            },
            {
                label: 'Ljudi',
                type: 'number'
            }
        ]
    });

    // get html table rows
    var raspolozenja = document.getElementById('proba');

    $("#proba tr").each(function(i) {
        // exclude column heading
        if (i > 0 && ($(this).data("hidden") == false || $(this).data("hidden") == "false")) {
            data.addRow([{
                    v: ($(this).children().first().text().trim())
                },
                {
                    v: 1
                }
            ]);
        }
    });

    var dataSummary = google.visualization.data.group(
        data,
        [0],
        [{
            'column': 1,
            'aggregation': google.visualization.data.sum,
            'type': 'number'
        }]
    );

    var boje = [];
    if (!postoji_pozitivan && postoji_neutralan && !postoji_negativan) {
        boje = ['#f0ad4e'];
    } else if (postoji_pozitivan && !postoji_neutralan && !postoji_negativan) {
        boje = ['#5cb85c'];
    } else if (!postoji_pozitivan && !postoji_neutralan && postoji_negativan) {
        boje = ['#d9534f'];
    } else if (postoji_pozitivan && postoji_neutralan && !postoji_negativan) {
        boje = ['#f0ad4e', '#5cb85c'];
    } else if (!postoji_pozitivan && postoji_neutralan && postoji_negativan) {
        boje = ['#f0ad4e', '#d9534f'];
    } else if (postoji_pozitivan && !postoji_neutralan && postoji_negativan) {
        boje = ['#5cb85c', '#d9534f'];
    } else if (postoji_pozitivan && postoji_neutralan && postoji_negativan) {
        boje = ['#f0ad4e', '#5cb85c', '#d9534f'];
    }
    // Set chart options
    var options = {
        'title': 'Raspoloženja',
        'width': 800,
        'height': 400,
        'fontSize': 25,
        'colors': boje
    };

    var chart = new google.visualization.PieChart(document.getElementById('chart_div'));

    function selectHandler() {
        var selectedItem = chart.getSelection()[0];

        if (selectedItem) {
            if (!postoji_pozitivan && postoji_neutralan && !postoji_negativan) {
                switch (selectedItem.row) {
                    case 0:
                        $('#pozRazlozi').hide();
                        $('#neutrRazlozi').show();
                        $('#negRazlozi').hide();
                        break;
                    default:
                        $('#pozRazlozi').hide();
                        $('#neutrRazlozi').hide();
                        $('#negRazlozi').hide();
                        break;
                }
            } else if (postoji_pozitivan && !postoji_neutralan && !postoji_negativan) {
                switch (selectedItem.row) {
                    case 0:
                        $('#pozRazlozi').show();
                        $('#neutrRazlozi').hide();
                        $('#negRazlozi').hide();
                        break;
                    default:
                        $('#pozRazlozi').hide();
                        $('#neutrRazlozi').hide();
                        $('#negRazlozi').hide();
                        break;
                }
            } else if (!postoji_pozitivan && !postoji_neutralan && postoji_negativan) {
                switch (selectedItem.row) {
                    case 0:
                        $('#pozRazlozi').hide();
                        $('#neutrRazlozi').hide();
                        $('#negRazlozi').show();
                        break;
                    default:
                        $('#pozRazlozi').hide();
                        $('#neutrRazlozi').hide();
                        $('#negRazlozi').hide();
                        break;
                }
            } else if (postoji_pozitivan && postoji_neutralan && !postoji_negativan) {
                switch (selectedItem.row) {
                    case 0:
                        $('#pozRazlozi').hide();
                        $('#neutrRazlozi').show();
                        $('#negRazlozi').hide();
                        break;
                    case 1:
                        $('#pozRazlozi').show();
                        $('#neutrRazlozi').hide();
                        $('#negRazlozi').hide();
                        break;
                    default:
                        $('#pozRazlozi').hide();
                        $('#neutrRazlozi').hide();
                        $('#negRazlozi').hide();
                        break;
                }
            } else if (!postoji_pozitivan && postoji_neutralan && postoji_negativan) {
                switch (selectedItem.row) {
                    case 0:
                        $('#pozRazlozi').hide();
                        $('#neutrRazlozi').show();
                        $('#negRazlozi').hide();
                        break;
                    case 1:
                        $('#pozRazlozi').hide();
                        $('#neutrRazlozi').hide();
                        $('#negRazlozi').show();
                        break;
                    default:
                        $('#pozRazlozi').hide();
                        $('#neutrRazlozi').hide();
                        $('#negRazlozi').hide();
                        break;
                }
            } else if (postoji_pozitivan && !postoji_neutralan && postoji_negativan) {
                switch (selectedItem.row) {
                    case 0:
                        $('#pozRazlozi').show();
                        $('#neutrRazlozi').hide();
                        $('#negRazlozi').hide();
                        break;
                    case 1:
                        $('#pozRazlozi').hide();
                        $('#neutrRazlozi').hide();
                        $('#negRazlozi').show();
                        break;
                    default:
                        $('#pozRazlozi').hide();
                        $('#neutrRazlozi').hide();
                        $('#negRazlozi').hide();
                        break;
                }
            } else if (postoji_pozitivan && postoji_neutralan && postoji_negativan) {
                switch (selectedItem.row) {
                    case 0:
                        $('#pozRazlozi').hide();
                        $('#neutrRazlozi').show();
                        $('#negRazlozi').hide();
                        break;
                    case 1:
                        $('#pozRazlozi').show();
                        $('#neutrRazlozi').hide();
                        $('#negRazlozi').hide();
                        break;
                    case 2:
                        $('#pozRazlozi').hide();
                        $('#neutrRazlozi').hide();
                        $('#negRazlozi').show();
                        break;
                    default:
                        $('#pozRazlozi').hide();
                        $('#neutrRazlozi').hide();
                        $('#negRazlozi').hide();
                        break;
                }
            }
        }
    }
    // Instantiate and draw our chart, passing in some options.
    google.visualization.events.addListener(chart, 'select', selectHandler);
    chart.draw(dataSummary, options);
}

$(document).ready(function() {
    tablica_pitanja = $('#stats-questions').DataTable({
        "dom":' <"row search"f><"top"l>rt<"bottom"ip>',
        "order": [[ 0, 'desc' ]],
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
$(document).ready(function() {
    tablica_komentari = $('#stats-comments').DataTable({
        "dom":' <"row search"f><"top"l>rt<"bottom"ip>',
        "order": [[ 0, 'desc' ]],
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
