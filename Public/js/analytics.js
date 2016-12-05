$(document).ready(function(e) {

    var self = $('#ga-overview');

    renderTable($('#pageviews-list'), 'top-pages', function () {
        renderResponsivePie($('#visitor-breakdown'), function () {
            self.addClass('rendered').find('.overlay').remove();
        });
    });

    $('#ga-tab-pageviews').on('shown.bs.tab', function () {
        console.log('Showing pageviews');

        var self = $('#ga-pageviews');

        if (self.hasClass('rendered')) {
            return;
        }

        renderChart($('#pageviews-chart'), 'pageviews', function () {
            self.addClass('rendered').find('.overlay').remove();
        });
    });

    $('#ga-tab-uniques').on('shown.bs.tab', function () {
        console.log('Showing uniques');
        var self = $('#ga-uniques');

        if (self.hasClass('rendered')) {
            return;
        }

        renderChart($('#uniques-chart'), 'uniquePageviews', function () {
            self.addClass('rendered').find('.overlay').remove();
        });
    });

    $('#ga-tab-visitors').on('shown.bs.tab', function () {
        console.log('Showing visitors');
        var self = $('#ga-visitors');

        if (self.hasClass('rendered')) {
            return;
        }

        renderChart($('#visitors-chart'), 'visitors', function () {
            self.addClass('rendered').find('.overlay').remove();
        });
    });
});

function renderTable(div, metric, cb) {
    var colors = ['green', 'aqua', 'yellow', 'red', 'blue'];

    $.ajax({
        url: window.adminUri + '/google-analytics/' + metric
    }).done(function(data) {
        data = JSON.parse(data);

        for(var i in data) {
            var item = data[i];
            var row = '<div class="col-xs-6 col-sm-6 col-md-12 col-lg-12">' +
                '<span class="text">' + item.name + '<span class="pull-right">' + item.metric + ' views</span> </span>' +
                '<div class="progress progress-striped sm" style="margin-bottom: 10px">' +
                '<div class="progress-bar progress-bar-' + colors[i] + '" style="width: ' + item.percentage + '%;"></div>' +
                '</div></div>';

            div.append(row);
        }

        cb();
    });
}

function renderChart(chart, metric, cb) {
    var data = [], totalPoints = 30, $UpdatingChartColors = chart.css('color');

    $.ajax({
        url: window.adminUri + '/google-analytics/metric/' + metric
    }).done(function(data) {
        data = JSON.parse(data);
        var meta = data.shift();

        var ticks = [];
        for (var tick in meta.ticks) {
            ticks.push([tick, meta.ticks[tick]]);
        }

        var points = [];
        for (var i = 0; i < data.length; i++) {
            points.push([i, data[i].value]);
        }

        // Setup options
        var options = {
            yaxis : {
                min : 0,
                max : meta.max
            },
            xaxis : {
                min : 0,
                max : 30,
                ticks: ticks
            },
            colors : [$UpdatingChartColors],
            series : {
                lines : {
                    lineWidth : 1,
                    fill : true,
                    fillColor : {
                        colors : [{
                            opacity : 0.4
                        }, {
                            opacity : 0
                        }]
                    },
                    steps : false

                }
            }
        };

        var plot = $.plot(chart, [points], options);
        cb();
    });
}

function renderResponsivePie(chart, cb) {
    var points = [];
    var colors = ['rgb(0,192,239)', 'rgb(243,156,18)', 'rgb(0,166,90)'];

    $.ajax({
        url: window.adminUri + '/google-analytics/responsive'
    }).done(function(data) {
        data = JSON.parse(data);

        var j = 0;
        for(var i in data) {
            var point = { data: data[i].count, color: colors[j++] };
            points.push(point);

            $('#responsive-' + i).text(data[i].percentage);
            $('#responsive-' + i).parents('.badge').css('background', colors[j - 1]);

            $.plot(chart, points, {
                series: {
                    pie: {
                        show: true
                    }
                }
            });
        }

        cb();
    });
}
