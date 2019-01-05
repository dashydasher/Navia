var hour_start = parseInt($("#time-start").text().split(":")[0]);
var time_start = hour_start * 60 + parseInt($("#time-start").text().split(":")[1]);

var hour_end = parseInt($("#time-end").text().split(":")[0]);
var time_end = hour_end * 60 + parseInt($("#time-end").text().split(":")[1]);

$("#slider-range").slider({
    min: time_start,
    max: time_end,
    step: 15,
    values: [
        time_end
    ],
    slide: function(e, ui) {
        $('.slider-time').each(function(i) {
            var hours = ("00" + Math.floor(ui.values[i] / 60)).slice(-2);
            var mins = ("00" + (
                ui.values[i] - (hours * 60)
            )).slice(-2);
            hours %= 24;
            $(this).html(hours + ':' + mins);

            filtriraj_tablicu(hours + ':' + mins);
            filtriraj_chart(hours + ':' + mins);
        });
    }
});

function filtriraj_tablicu(vrijeme) {
    $("#pozRazlozi > table tr:gt(0), #neutrRazlozi > table tr:gt(0), #negRazlozi > table tr:gt(0)").hide().each(function () {
        var id = $(this).data("id");
        if (vrijeme in moods_intervals && id in moods_intervals[vrijeme]) {
            $(this).closest("tr").show();
        }
    });
}

function filtriraj_chart(vrijeme) {
    $("#proba tr").each(function (i) {
        var id = $(this).data("id");
        if (vrijeme in moods_intervals && id in moods_intervals[vrijeme]) {
            $(this).data("hidden", "false");
            $(this).attr('data-hidden', "false");
        } else {
            $(this).data("hidden", "true");
            $(this).attr('data-hidden', "true");
        }
    });
    google.charts.load('current', {
        packages: ['corechart'],
        callback: drawChart
    });
}

filtriraj_tablicu($("#time-end").text());

$("#end").on("click", function() {
    $("#slider-range").slider("values", 0, time_end);
    $(".slider-time").html(hour_end % 24 + ":" + time_end % 60);
});

// Load the Visualization API and the corechart package.
google.charts.load('current', {
    packages: ['corechart'],
    callback: drawChart
});

// Set a callback to run when the Google Visualization API is loaded.
//  google.charts.setOnLoadCallback(drawChart);

// Callback that creates and populates a data table,
// instantiates the pie chart, passes in the data and
// draws it.
function drawChart() {

    // Create the data table.
    var data = new google.visualization.DataTable();


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

    $("#proba tr").each(function (i) {
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
    /*
    Array.prototype.forEach.call(raspolozenja.rows, function(row) {
        // exclude column heading
        if (row.rowIndex > 0) {
            data.addRow([{
                    v: (row.cells[0].textContent || row.cells[0].innerHTML).trim()
                },
                {
                    v: 1
                }
            ]);
        }
    });
    */

    var dataSummary = google.visualization.data.group(
        data,
        [0],
        [{
            'column': 1,
            'aggregation': google.visualization.data.sum,
            'type': 'number'
        }]
    );

    // Set chart options
    var options = {
        'title': 'Raspoloženja',
        'width': 700,
        'height': 500,
        'fontSize': 25,
        'colors': ['#f0ad4e', '#5cb85c', '#d9534f']
    };

    var chart = new google.visualization.PieChart(document.getElementById('chart_div'));

    function selectHandler() {
        var selectedItem = chart.getSelection()[0];

        if (selectedItem) {
            //console.log(selectedItem);
            switch (selectedItem.row) {
                case 1:
                    $('#pozRazlozi').show();
                    $('#neutrRazlozi').hide();
                    $('#negRazlozi').hide();
                    break;
                case 0:
                    $('#pozRazlozi').hide();
                    $('#neutrRazlozi').show();
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

    // Instantiate and draw our chart, passing in some options.
    google.visualization.events.addListener(chart, 'select', selectHandler);
    chart.draw(dataSummary, options);
}
